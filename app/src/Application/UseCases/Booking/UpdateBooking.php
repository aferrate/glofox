<?php

namespace App\Application\UseCases\Booking;

use App\Domain\Repository\BookingRepositoryInterface;
use App\Domain\Repository\MemberRepositoryInterface;
use App\Domain\Repository\ClassroomRepositoryInterface;
use App\Domain\Model\Booking;
use App\Domain\Validations\BookingChecker;

class UpdateBooking
{
    private $bookingRepository;
    private $bookingChecker;
    private $memberRepository;
    private $classroomRepository;

    public function __construct(
        BookingRepositoryInterface $bookingRepository,
        BookingChecker $bookingChecker,
        MemberRepositoryInterface $memberRepository,
        ClassroomRepositoryInterface $classroomRepository
    )
    {
        $this->bookingRepository = $bookingRepository;
        $this->bookingChecker = $bookingChecker;
        $this->memberRepository = $memberRepository;
        $this->classroomRepository = $classroomRepository;
    }

    public function execute(int $id, array $bookingArr): array
    {
        try{
            $checkId = $this->bookingChecker->checkId($id);

            if($checkId['status'] === false) {
                return ['status' => false, 'data' => ['message' => $checkId['message']]];
            }

            $checkParams = $this->bookingChecker->checkParams($bookingArr);

            if($checkParams['status'] === false) {
                return ['status' => false, 'data' => ['message' => $checkParams['message']]];
            }

            $booking = $this->bookingRepository->findOneById($id);

            if(is_null($booking)) {
                return ['status' => false, 'data' => ['message' => 'no booking found']];
            }

            $bookingExist = $this->bookingRepository->findByDateMemberIdClassId($bookingArr);

            if(!is_null($bookingExist)) {
                return ['status' => false, 'data' => ['message' => 'booking already exists']];
            }

            if(is_null($this->memberRepository->findOneById($bookingArr['member_id']))) {
                return ['status' => false, 'data' => ['message' => 'unexistent member']];
            }

            $classroom = $this->classroomRepository->findOneById($bookingArr['classroom_id']);

            if(is_null($classroom)) {
                return ['status' => false, 'data' => ['message' => 'unexistent classroom']];
            }

            if($classroom->getCapacity() <= $this->bookingRepository->countBookings($bookingArr)) {
                return ['status' => false, 'data' => ['message' => 'no slots available']];
            }
    
            $booking = Booking::returnObjBooking($booking, $bookingArr);
    
            $this->bookingRepository->save($booking);
    
            return ['status' => true, 'data' => ['message' => 'booking updated!']];
        } catch(\Exception $e){
            return ['status' => false, 'data' => ['message' => $e->getMessage()]];
        }
    }
}
