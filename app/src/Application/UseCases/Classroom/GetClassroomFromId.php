<?php

namespace App\Application\UseCases\Classroom;

use App\Domain\Repository\ClassroomRepositoryInterface;
use App\Domain\Validations\ClassroomChecker;
use App\Domain\Service\SerializerInterface;

class GetClassroomFromId
{
    private $classroomRepository;
    private $classroomChecker;
    private $serializer;

    public function __construct(
        ClassroomRepositoryInterface $classroomRepository,
        ClassroomChecker $classroomChecker,
        SerializerInterface $serializer
    )
    {
        $this->classroomRepository = $classroomRepository;
        $this->classroomChecker = $classroomChecker;
        $this->serializer = $serializer;
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
    
            $classroom = json_decode($this->serializer->serialize($classroomObj), true);
    
            return ['status' => true, 'data' => $classroom];
        } catch(\Exception $e){
            return ['status' => false, 'data' => ['message' => $e->getMessage()]];
        }
    }
}
