<?php

namespace App\Application\UseCases\Classroom;

use App\Domain\Repository\ClassroomRepositoryInterface;
use App\Domain\Repository\BookingRepositoryInterface;
use App\Domain\Model\Classroom;
use App\Domain\Validations\ClassroomChecker;
use DateTime;
use App\Application\UseCases\Classroom\AbstractClassroom;

class UpdateClassroom extends AbstractClassroom
{
    private $classroomRepository;
    private $bookingRepository;
    private $classroomChecker;

    public function __construct(
        ClassroomRepositoryInterface $classroomRepository,
        BookingRepositoryInterface $bookingRepository,
        ClassroomChecker $classroomChecker
    )
    {
        $this->classroomRepository = $classroomRepository;
        $this->classroomChecker = $classroomChecker;
        $this->bookingRepository = $bookingRepository;
    }

    public function execute(int $id, array $classroomArr): array
    {
        try{
            $checkId = $this->classroomChecker->checkId($id);

            if($checkId['status'] === false) {
                return ['status' => false, 'data' => ['message' => $checkId['message']]];
            }

            $checkParams = $this->classroomChecker->checkParams($classroomArr);

            if($checkParams['status'] === false) {
                return ['status' => false, 'data' => ['message' => $checkParams['message']]];
            }

            $classroom = $this->classroomRepository->findOneById($id);

            if(is_null($classroom)) {
                return ['status' => false, 'data' => ['message' => 'no classroom found']];
            }

            $classroomExist = $this->classroomRepository->findOneByNameAndDatesAndCapacity($classroomArr);

            if(!is_null($classroomExist)) {
                return ['status' => false, 'data' => ['message' => 'classroom already exists']];
            }

            $this->checkBookingsDate($classroom, $classroomArr);
            $this->checkBookingsCapacity($classroom, $classroomArr);
    
            $classroom = Classroom::returnObjClassroom($classroom, $classroomArr);
    
            $this->classroomRepository->save($classroom);
    
            return ['status' => true, 'data' => ['message' => 'classroom updated!']];
        } catch(\Exception $e){
            return ['status' => false, 'data' => ['message' => $e->getMessage()]];
        }
    }

    private function checkBookingsDate(Classroom $classroom, array $classroomArr): void
    {
        if(DateTime::createFromFormat('d-m-Y', $classroomArr['start_date']) > $classroom->getStartDate() || 
            DateTime::createFromFormat('d-m-Y', $classroomArr['end_date']) < $classroom->getEndDate()) {
                $this->deleteBookingsByClassroomId($classroom->getId(), $this->bookingRepository);
        }
    }

    private function checkBookingsCapacity(Classroom $classroom, array $classroomArr): void
    {
        if($classroomArr['capacity'] < $classroom->getCapacity()) {
            $this->deleteBookingsByClassroomId($classroom->getId(), $this->bookingRepository);
        }
    }
}
