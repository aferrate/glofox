<?php

namespace App\Application\UseCases\Booking;

use App\Domain\Repository\BookingRepositoryInterface;
use App\Domain\Model\Booking;
use App\Domain\Validations\BookingChecker;

class GetAllBookings
{
    private $bookingRepository;
    private $bookingChecker;

    public function __construct(BookingRepositoryInterface $bookingRepository, BookingChecker $bookingChecker)
    {
        $this->bookingRepository = $bookingRepository;
        $this->bookingChecker = $bookingChecker;
    }

    public function execute(): array
    {
        try{
            $bookingsObjs = $this->bookingRepository->findAll();

            if(empty($bookingsObjs)) {
                return ['status' => true, 'data' => ['message' => 'no bookings found']];
            }

            $bookings = [];

            foreach ($bookingsObjs as $booking) {
                $bookings[] = $booking->returnArrayBooking($booking);
            }

            return ['status' => true, 'data' => $bookings];
        } catch(\Exception $e){
            return ['status' => false, 'data' => ['message' => $e->getMessage()]];
        }
    }
}
