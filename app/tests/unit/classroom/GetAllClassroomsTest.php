<?php

namespace App\Tests\Unit\Classroom;

use PHPUnit\Framework\TestCase;
use App\Repository\ClassroomRepository;
use App\Service\CustomSerializer;
use App\Application\UseCases\Classroom\GetAllClassrooms;
use App\Domain\Model\Classroom;
use DG\BypassFinals;

class GetAllClassroomsTest extends TestCase
{
    protected function setUp(): void
    {
        BypassFinals::enable();
    }

    public function testGetAllClassrooms(): void
    {
        $classroomRepository = $this->createMock(ClassroomRepository::class);
        $customSerializer = $this->createMock(CustomSerializer::class);
        $getAllClassrooms = new GetAllClassrooms($classroomRepository, $customSerializer);
        $serializedClassroom = '{"name": "test"}';
        $classroom = new Classroom();

        $classroomRepository->expects($this->any())
            ->method('findAll')
            ->willReturn([$classroom])
        ;

        $customSerializer->expects($this->any())
            ->method('serialize')
            ->willReturn($serializedClassroom)
        ;

        $this->assertSame(['status' => true, 'data' => [json_decode($serializedClassroom, true)]], $getAllClassrooms->execute());
    }
}
