<?php

namespace App\Application\UseCases\Classroom;

use App\Domain\Repository\ClassroomRepositoryInterface;
use App\Domain\Service\SerializerInterface;

class GetAllClassrooms
{
    private $classroomRepository;
    private $serializer;

    public function __construct(ClassroomRepositoryInterface $classroomRepository, SerializerInterface $serializer)
    {
        $this->classroomRepository = $classroomRepository;
        $this->serializer = $serializer;
    }

    public function execute(): array
    {
        try {
            $classroomsObjs = $this->classroomRepository->findAll();

            if (empty($classroomsObjs)) {
                return ['status' => true, 'data' => ['message' => 'no classrooms found']];
            }

            $classrooms = [];

            foreach ($classroomsObjs as $classroom) {
                $classrooms[] = json_decode($this->serializer->serialize($classroom), true);
            }

            return ['status' => true, 'data' => $classrooms];
        } catch (\Exception $e) {
            return ['status' => false, 'data' => ['message' => $e->getMessage()]];
        }
    }
}
