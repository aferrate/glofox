<?php

namespace App\Application\UseCases\Classroom;

use App\Domain\Repository\ClassroomRepositoryInterface;
use App\Domain\Validations\ClassroomChecker;

class GetClassroomFromId
{
    private $classroomRepository;
    private $classroomChecker;

    public function __construct(ClassroomRepositoryInterface $classroomRepository, ClassroomChecker $classroomChecker)
    {
        $this->classroomRepository = $classroomRepository;
        $this->classroomChecker = $classroomChecker;
    }

    public function execute(int $id): array
    {
        try{
            $checkId = $this->classroomChecker->checkId($id);

            if($checkId['status'] === false) {
                return ['status' => false, 'data' => ['message' => $checkId['message']]];
            }

            $classroomObj = $this->classroomRepository->findOneById($id);

            if(is_null($classroomObj)) {
                return ['status' => false, 'data' => ['message' => 'no classroom found']];
            }
    
            $classroom = $classroomObj->returnArrayClassroom($classroomObj);
    
            return ['status' => true, 'data' => $classroom];
        } catch(\Exception $e){
            return ['status' => false, 'data' => ['message' => $e->getMessage()]];
        }
    }
}
