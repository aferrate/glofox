<?php

namespace App\Application\UseCases\Classroom;

use App\Domain\Repository\ClassroomRepositoryInterface;
use App\Domain\Repository\BookingRepositoryInterface;
use App\Domain\Validations\ClassroomChecker;
use App\Application\UseCases\Classroom\AbstractUseCaseClassroom;

class DeleteClassroom extends AbstractUseCaseClassroom
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

    public function execute(int $id): array
    {
        try{
            $checkId = $this->classroomChecker->checkId($id);

            if($checkId['status'] === false) {
                return ['status' => false, 'data' => ['message' => $checkId['message']]];
            }

            $classroom = $this->classroomRepository->findOneById($id);

            if(is_null($classroom)) {
                return ['status' => false, 'data' => ['message' => 'no classroom found']];
            }
    
            $this->classroomRepository->delete($classroom);
            $this->deleteBookingsByClassroomId($id, $this->bookingRepository);
    
            return ['status' => false, 'data' => ['message' => 'classroom deleted']];
        } catch(\Exception $e){
            return ['status' => false, 'data' => ['message' => $e->getMessage()]];
        }
    }
}
