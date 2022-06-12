<?php

namespace App\Application\UseCases\Booking;

use App\Domain\Repository\BookingRepositoryInterface;
use App\Domain\Model\Booking;
use App\Domain\Validations\BookingChecker;

class DeleteBooking
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

            $booking = $this->bookingRepository->findOneById($id);

            if(is_null($booking)) {
                return ['status' => false, 'data' => ['message' => 'no booking found']];
            }
    
            $this->bookingRepository->delete($booking);
    
            return ['status' => true, 'data' => ['message' => 'booking deleted']];
        } catch(\Exception $e){
            return ['status' => false, 'data' => ['message' => $e->getMessage()]];
        }
    }
}
