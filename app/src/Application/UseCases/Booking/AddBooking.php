<?php

namespace App\Application\UseCases\Booking;

use App\Domain\Repository\BookingRepositoryInterface;
use App\Domain\Repository\MemberRepositoryInterface;
use App\Domain\Repository\ClassroomRepositoryInterface;
use App\Domain\Model\Booking;
use App\Domain\Validations\BookingChecker;

class AddBooking
{
    private $bookingRepository;
    private $bookingChecker;
    private $memberRepository;
    private $classroomRepository;

    public function __construct(
        BookingRepositoryInterface $bookingRepository,
        MemberRepositoryInterface $memberRepository,
        ClassroomRepositoryInterface $classroomRepository,
        BookingChecker $bookingChecker
    )
    {
        $this->bookingRepository = $bookingRepository;
        $this->bookingChecker = $bookingChecker;
        $this->memberRepository = $memberRepository;
        $this->classroomRepository = $classroomRepository;
    }

    public function execute(array $bookingArr): array
    {
        try{
            $checkParams = $this->bookingChecker->checkParams($bookingArr);

            if($checkParams['status'] === false) {
                return ['status' => false, 'data' => ['message' => $checkParams['message']]];
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

            $booking = new Booking();
            $booking = Booking::returnObjBooking($booking, $bookingArr);
    
            $id = $this->bookingRepository->save($booking);
    
            return [
                'status' => true,
                'data' => [
                    'message' => 'booking created!',
                    'id' => $id
                ]
            ];
        } catch(\Exception $e){
            return ['status' => false, 'data' => ['message' => $e->getMessage()]];
        }
    }
}
