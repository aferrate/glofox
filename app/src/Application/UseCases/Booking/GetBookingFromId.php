<?php

namespace App\Application\UseCases\Booking;

use App\Domain\Repository\BookingRepositoryInterface;
use App\Domain\Model\Booking;
use App\Domain\Validations\BookingChecker;
use App\Domain\Service\SerializerInterface;

class GetBookingFromId
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
    
            $booking = json_decode($this->serializer->serialize($bookingObj), true);
    
            return ['status' => true, 'data' => $booking];
        } catch(\Exception $e){
            return ['status' => false, 'data' => ['message' => $e->getMessage()]];
        }
    }
}
