<?php

namespace App\Application\UseCases\Booking;

use App\Domain\Repository\BookingRepositoryInterface;
use App\Domain\Model\Booking;
use App\Domain\Validations\BookingChecker;

class GetBookingFromId
{
    private $bookingRepository;
    private $bookingChecker;

    public function __construct(BookingRepositoryInterface $bookingRepository, BookingChecker $bookingChecker)
    {
        $this->bookingRepository = $bookingRepository;
        $this->bookingChecker = $bookingChecker;
    }

    public function execute(int $id): array
    {
        try{
            $checkId = $this->bookingChecker->checkId($id);

            if($checkId['status'] === false) {
                return ['status' => false, 'data' => ['message' => $checkId['message']]];
            }

            $bookingObj = $this->bookingRepository->findOneById($id);

            if(is_null($bookingObj)) {
                return ['status' => false, 'data' => ['message' => 'no booking found']];
            }
    
            $booking = $bookingObj->returnArrayBooking($bookingObj);
    
            return ['status' => true, 'data' => $booking];
        } catch(\Exception $e){
            return ['status' => false, 'data' => ['message' => $e->getMessage()]];
        }
    }
}
