<?php

namespace App\Application\UseCases\Classroom;

use App\Domain\Repository\ClassroomRepositoryInterface;
use App\Domain\Model\Classroom;

class GetAllClassrooms
{
    private $classroomRepository;

    public function __construct(ClassroomRepositoryInterface $classroomRepository)
    {
        $this->classroomRepository = $classroomRepository;
    }

    public function execute(): array
    {
        try{
            $classroomsObjs = $this->classroomRepository->findAll();

            if(empty($classroomsObjs)) {
                return ['status' => true, 'data' => ['message' => 'no classrooms found']];
            }

            $classrooms = [];

            foreach ($classroomsObjs as $classroom) {
                $classrooms[] = $classroom->returnArrayClassroom($classroom);
            }

            return ['status' => true, 'data' => $classrooms];
        } catch(\Exception $e){
            return ['status' => false, 'data' => ['message' => $e->getMessage()]];
        }
    }
}
