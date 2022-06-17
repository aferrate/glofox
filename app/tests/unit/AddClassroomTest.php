<?php

namespace App\Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Repository\ClassroomRepository;
use App\Service\CustomSerializer;
use App\Domain\Validations\ClassroomChecker;
use App\Application\UseCases\Classroom\AddClassroom;
use App\Domain\Model\Classroom;
use DG\BypassFinals;

class AddClassroomTest extends TestCase
{
    protected function setUp(): void
    {
        BypassFinals::enable();
    }

    public function testAddClassroom(): void
    {
        $params = ["name" => "test", "capacity" => 8, "start_date" => "10-06-2023", "end_date" => "17-06-2023"];
        $classroomRepository = $this->createMock(ClassroomRepository::class);
        $customSerializer = $this->createMock(CustomSerializer::class);
        $classroomChecker = $this->createMock(ClassroomChecker::class);
        $addClassroom = new AddClassroom($classroomRepository, $classroomChecker, $customSerializer);
        $classroom = new Classroom();

        $classroomRepository->expects($this->any())
            ->method('save')
            ->willReturn(60)
        ;
        $classroomRepository->expects($this->any())
            ->method('findOneByNameAndDatesAndCapacity')
            ->willReturn(null)
        ;

        $customSerializer->expects($this->any())
            ->method('deserialize')
            ->willReturn($classroom)
        ;

        $classroomChecker->expects($this->any())
            ->method('checkParams')
            ->willReturn(['status' => true, 'message' => 'ok'])
        ;

        $this->assertSame(['status' => true, 'data' => ['message' => 'classroom created!', 'id' => 60]], $addClassroom->execute($params));
    }
}
