<?php

namespace App\Tests\Unit\Classroom;

use App\Application\UseCases\Classroom\DeleteClassroom;
use App\Domain\Model\Classroom;
use App\Domain\Validations\ClassroomChecker;
use App\Repository\BookingRepository;
use App\Repository\ClassroomRepository;
use DG\BypassFinals;
use PHPUnit\Framework\TestCase;

class DeleteClassroomTest extends TestCase
{
    protected function setUp(): void
    {
        BypassFinals::enable();
    }

    public function testDeleteClassroom(): void
    {
        $classroomRepository = $this->createMock(ClassroomRepository::class);
        $bookingRepository = $this->createMock(BookingRepository::class);
        $classroomChecker = $this->createMock(ClassroomChecker::class);
        $deleteClassroom = new DeleteClassroom($classroomRepository, $bookingRepository, $classroomChecker);
        $classroom = new Classroom();

        $classroomChecker->expects($this->any())
            ->method('checkId')
            ->willReturn(['status' => true, 'message' => 'ok'])
        ;

        $classroomRepository->expects($this->any())
            ->method('findOneById')
            ->willReturn($classroom)
        ;
        $classroomRepository->expects($this->any())
            ->method('delete')
        ;

        $bookingRepository->expects($this->any())
            ->method('findByClassroomId')
            ->willReturn([])
        ;

        $this->assertSame(['status' => true, 'data' => ['message' => 'classroom deleted']], $deleteClassroom->execute(1));
    }
}
