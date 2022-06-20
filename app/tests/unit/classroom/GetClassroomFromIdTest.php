<?php

namespace App\Tests\Unit\Classroom;

use PHPUnit\Framework\TestCase;
use App\Repository\ClassroomRepository;
use App\Service\CustomSerializer;
use App\Domain\Validations\ClassroomChecker;
use App\Application\UseCases\Classroom\GetClassroomFromId;
use App\Domain\Model\Classroom;
use DG\BypassFinals;

class GetClassroomFromIdTest extends TestCase
{
    protected function setUp(): void
    {
        BypassFinals::enable();
    }

    public function testGetClassroomFromId(): void
    {
        $classroomRepository = $this->createMock(ClassroomRepository::class);
        $customSerializer = $this->createMock(CustomSerializer::class);
        $classroomChecker = $this->createMock(ClassroomChecker::class);
        $getClassroomFromId = new GetClassroomFromId($classroomRepository, $classroomChecker, $customSerializer);
        $classroom = new Classroom();
        $serializedClassroom = '{"name": "test"}';

        $classroomChecker->expects($this->any())
            ->method('checkId')
            ->willReturn(['status' => true, 'message' => 'ok'])
        ;

        $classroomRepository->expects($this->any())
            ->method('findOneById')
            ->willReturn($classroom)
        ;

        $customSerializer->expects($this->any())
            ->method('serialize')
            ->willReturn($serializedClassroom)
        ;

        $this->assertSame(['status' => true, 'data' => json_decode($serializedClassroom, true)], $getClassroomFromId->execute(1));
    }
}
