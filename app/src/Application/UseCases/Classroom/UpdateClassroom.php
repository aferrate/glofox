<?php

namespace App\Application\UseCases\Classroom;

use App\Domain\Model\Classroom;
use App\Domain\Repository\BookingRepositoryInterface;
use App\Domain\Repository\ClassroomRepositoryInterface;
use App\Domain\Service\SerializerInterface;
use App\Domain\Validations\ClassroomChecker;
use DateTime;

class UpdateClassroom extends AbstractUseCaseClassroom
{
    private $classroomRepository;
    private $bookingRepository;
    private $classroomChecker;
    private $serializer;

    public function __construct(
        ClassroomRepositoryInterface $classroomRepository,
        BookingRepositoryInterface $bookingRepository,
        ClassroomChecker $classroomChecker,
        SerializerInterface $serializer
    ) {
        $this->classroomRepository = $classroomRepository;
        $this->classroomChecker = $classroomChecker;
        $this->bookingRepository = $bookingRepository;
        $this->serializer = $serializer;
    }

    public function execute(int $id, array $classroomArr): array
    {
        try {
            $checkId = $this->classroomChecker->checkId($id);

            if (false === $checkId['status']) {
                return ['status' => false, 'data' => ['message' => $checkId['message']]];
            }

            $checkParams = $this->classroomChecker->checkParams($classroomArr);

            if (false === $checkParams['status']) {
                return ['status' => false, 'data' => ['message' => $checkParams['message']]];
            }

            $classroom = $this->classroomRepository->findOneById($id);

            if (is_null($classroom)) {
                return ['status' => false, 'data' => ['message' => 'no classroom found']];
            }

            if (!is_null($this->classroomRepository->findOneByNameAndDatesAndCapacity($classroomArr))) {
                return ['status' => false, 'data' => ['message' => 'classroom already exists']];
            }

            $this->checkBookingsDate($classroom, $classroomArr);
            $this->checkBookingsCapacity($classroom, $classroomArr);

            $this->classroomRepository->save($this->updateClassroomObject($classroom, $classroomArr));

            return ['status' => true, 'data' => ['message' => 'classroom updated!']];
        } catch (\Exception $e) {
            return ['status' => false, 'data' => ['message' => $e->getMessage()]];
        }
    }

    private function checkBookingsDate(Classroom $classroom, array $classroomArr): void
    {
        if (DateTime::createFromFormat('d-m-Y', $classroomArr['start_date']) > $classroom->getStartDate() ||
            DateTime::createFromFormat('d-m-Y', $classroomArr['end_date']) < $classroom->getEndDate()) {
            $this->deleteBookingsByClassroomId($classroom->getId(), $this->bookingRepository);
        }
    }

    private function checkBookingsCapacity(Classroom $classroom, array $classroomArr): void
    {
        if ($classroomArr['capacity'] < $classroom->getCapacity()) {
            $this->deleteBookingsByClassroomId($classroom->getId(), $this->bookingRepository);
        }
    }

    private function updateClassroomObject(Classroom $classroom, array $classroomArr): Classroom
    {
        $classroomNew = $this->serializer->deserialize($classroomArr, 'classroom');
        $classroom->setName($classroomNew->getName());
        $classroom->setCapacity($classroomNew->getCapacity());
        $classroom->setStartDate($classroomNew->getStartDate());
        $classroom->setEndDate($classroomNew->getEndDate());

        return $classroom;
    }
}
