<?php

namespace App\Tests\Unit\Classroom;

use PHPUnit\Framework\TestCase;
use App\Repository\ClassroomRepository;
use App\Repository\BookingRepository;
use App\Service\CustomSerializer;
use App\Domain\Validations\ClassroomChecker;
use App\Application\UseCases\Classroom\UpdateClassroom;
use App\Domain\Model\Classroom;
use DG\BypassFinals;
use DateTime;

class UpdateClassroomTest extends TestCase
{
    protected function setUp(): void
    {
        BypassFinals::enable();
    }

    public function testUpdateClassroom(): void
    {
        $params = ["name" => "test", "capacity" => 8, "start_date" => "10-06-2023", "end_date" => "17-06-2023"];
        $classroomRepository = $this->createMock(ClassroomRepository::class);
        $bookingRepository = $this->createMock(BookingRepository::class);
        $customSerializer = $this->createMock(CustomSerializer::class);
        $classroomChecker = $this->createMock(ClassroomChecker::class);
        $classroomMock = $this->createMock(Classroom::class);
        $updateClassroom = new UpdateClassroom($classroomRepository, $bookingRepository, $classroomChecker, $customSerializer);
        $classroom = new Classroom();
        $classroom->setName('test');
        $classroom->setCapacity(8);
        $classroom->setStartDate(DateTime::createFromFormat('d-m-Y', '10-06-2023'));
        $classroom->setEndDate(DateTime::createFromFormat('d-m-Y', '17-06-2023'));

        $classroomChecker->expects($this->any())
            ->method('checkId')
            ->willReturn(['status' => true, 'message' => 'ok'])
        ;
        $classroomChecker->expects($this->any())
            ->method('checkParams')
            ->willReturn(['status' => true, 'message' => 'ok'])
        ;

        $classroomRepository->expects($this->any())
            ->method('findOneById')
            ->willReturn($classroom)
        ;
        $classroomRepository->expects($this->any())
            ->method('findOneByNameAndDatesAndCapacity')
            ->willReturn(null)
        ;
        $classroomRepository->expects($this->any())
            ->method('save')
            ->willReturn(1)
        ;

        $bookingRepository->expects($this->any())
            ->method('findByClassroomId')
            ->willReturn([])
        ;

        $classroomMock->expects($this->any())
            ->method('getId')
            ->willReturn(1)
        ;

        $customSerializer->expects($this->any())
            ->method('deserialize')
            ->willReturn($classroom)
        ;

        $this->assertSame(['status' => true, 'data' => ['message' => 'classroom updated!']], $updateClassroom->execute(1, $params));
    }
}
