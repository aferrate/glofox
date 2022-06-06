<?php

namespace App\Application\UseCases\Classroom;

use App\Domain\Repository\ClassroomRepositoryInterface;
use App\Domain\Model\Classroom;
use App\Domain\Validations\ClassroomChecker;

class AddClassroom
{
    private $classroomRepository;
    private $classroomChecker;

    public function __construct(ClassroomRepositoryInterface $classroomRepository, ClassroomChecker $classroomChecker)
    {
        $this->classroomRepository = $classroomRepository;
        $this->classroomChecker = $classroomChecker;
    }

    public function execute(array $classroomArr): array
    {
        try{
            $checkParams = $this->classroomChecker->checkParams($classroomArr);

            if($checkParams['status'] === false) {
                return ['status' => false, 'data' => ['message' => $checkParams['message']]];
            }

            $classroomExist = $this->classroomRepository->findOneByNameAndDatesAndCapacity($classroomArr);

            if(!is_null($classroomExist)) {
                return ['status' => false, 'data' => ['message' => 'classroom already exists']];
            }

            $classroom = new Classroom();
            $classroom = Classroom::returnObjClassroom($classroom, $classroomArr);
    
            $id = $this->classroomRepository->save($classroom);
    
            return [
                'status' => true,
                'data' => [
                    'message' => 'classroom created!',
                    'id' => $id
                ]
            ];
        } catch(\Exception $e){
            return ['status' => false, 'data' => ['message' => $e->getMessage()]];
        }
    }
}
