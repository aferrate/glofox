<?php

namespace App\Application\UseCases\Classroom;

use App\Domain\Repository\ClassroomRepositoryInterface;
use App\Domain\Service\SerializerInterface;
use App\Domain\Validations\ClassroomChecker;

class AddClassroom extends AbstractUseCaseClassroom
{
    private $classroomRepository;
    private $classroomChecker;
    private $serializer;

    public function __construct(
        ClassroomRepositoryInterface $classroomRepository,
        ClassroomChecker $classroomChecker,
        SerializerInterface $serializer
    ) {
        $this->classroomRepository = $classroomRepository;
        $this->classroomChecker = $classroomChecker;
        $this->serializer = $serializer;
    }

    public function execute(array $classroomArr): array
    {
        try {
            $checkParams = $this->classroomChecker->checkParams($classroomArr);

            if (false === $checkParams['status']) {
                return ['status' => false, 'data' => ['message' => $checkParams['message']]];
            }

            if (!is_null($this->classroomRepository->findOneByNameAndDatesAndCapacity($classroomArr))) {
                return ['status' => false, 'data' => ['message' => 'classroom already exists']];
            }

            $classroom = $this->serializer->deserialize($classroomArr, 'classroom');

            $id = $this->classroomRepository->save($classroom);

            return [
                'status' => true,
                'data' => [
                    'message' => 'classroom created!',
                    'id' => $id,
                ],
            ];
        } catch (\Exception $e) {
            return ['status' => false, 'data' => ['message' => $e->getMessage()]];
        }
    }
}
