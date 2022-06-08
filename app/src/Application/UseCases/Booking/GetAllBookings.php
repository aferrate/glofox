<?php

namespace App\Application\UseCases\Booking;

use App\Domain\Repository\BookingRepositoryInterface;
use App\Domain\Model\Booking;
use App\Domain\Validations\BookingChecker;
use App\Domain\Service\SerializerInterface;

class GetAllBookings
{
    private $bookingRepository;
    private $bookingChecker;
    private $serializer;

    public function __construct(
        BookingRepositoryInterface $bookingRepository,
        BookingChecker $bookingChecker,
        SerializerInterface $serializer
    )
    {
        $this->bookingRepository = $bookingRepository;
        $this->bookingChecker = $bookingChecker;
        $this->serializer = $serializer;
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
                $bookings[] = json_decode($this->serializer->serialize($booking), true);
            }

            return ['status' => true, 'data' => $bookings];
        } catch(\Exception $e){
            return ['status' => false, 'data' => ['message' => $e->getMessage()]];
        }
    }
}
