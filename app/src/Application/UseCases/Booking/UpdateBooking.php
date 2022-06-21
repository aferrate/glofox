<?php

namespace App\Application\UseCases\Booking;

use App\Domain\Model\Booking;
use App\Domain\Repository\BookingRepositoryInterface;
use App\Domain\Repository\ClassroomRepositoryInterface;
use App\Domain\Repository\MemberRepositoryInterface;
use App\Domain\Service\SerializerInterface;
use App\Domain\Validations\BookingChecker;

class UpdateBooking
{
    private $bookingRepository;
    private $bookingChecker;
    private $memberRepository;
    private $classroomRepository;
    private $serializer;

    public function __construct(
        BookingRepositoryInterface $bookingRepository,
        BookingChecker $bookingChecker,
        MemberRepositoryInterface $memberRepository,
        ClassroomRepositoryInterface $classroomRepository,
        SerializerInterface $serializer
    ) {
        $this->bookingRepository = $bookingRepository;
        $this->bookingChecker = $bookingChecker;
        $this->memberRepository = $memberRepository;
        $this->classroomRepository = $classroomRepository;
        $this->serializer = $serializer;
    }

    public function execute(int $id, array $bookingArr): array
    {
        try {
            $checkId = $this->bookingChecker->checkId($id);

            if (false === $checkId['status']) {
                return ['status' => false, 'data' => ['message' => $checkId['message']]];
            }

            $checkParams = $this->bookingChecker->checkParams($bookingArr);

            if (false === $checkParams['status']) {
                return ['status' => false, 'data' => ['message' => $checkParams['message']]];
            }

            $booking = $this->bookingRepository->findOneById($id);

            if (is_null($booking)) {
                return ['status' => false, 'data' => ['message' => 'no booking found']];
            }

            if (!is_null($this->bookingRepository->findByDateMemberIdClassId($bookingArr))) {
                return ['status' => false, 'data' => ['message' => 'booking already exists']];
            }

            if (is_null($this->memberRepository->findOneById($bookingArr['idMember']))) {
                return ['status' => false, 'data' => ['message' => 'unexistent member']];
            }

            $classroom = $this->classroomRepository->findOneById($bookingArr['idClassroom']);

            if (is_null($classroom)) {
                return ['status' => false, 'data' => ['message' => 'unexistent classroom']];
            }

            if ($classroom->getCapacity() <= $this->bookingRepository->countBookings($bookingArr)) {
                return ['status' => false, 'data' => ['message' => 'no slots available']];
            }

            $this->bookingRepository->save($this->updateBookingObject($booking, $bookingArr));

            return ['status' => true, 'data' => ['message' => 'booking updated!']];
        } catch (\Exception $e) {
            return ['status' => false, 'data' => ['message' => $e->getMessage()]];
        }
    }

    private function updateBookingObject(Booking $booking, array $bookingArr): Booking
    {
        $bookingNew = $this->serializer->deserialize($bookingArr, 'booking');
        $booking->setIdMember($bookingNew->getIdMember());
        $booking->setIdClassroom($bookingNew->getIdClassroom());
        $booking->setDate($bookingNew->getDate());

        return $booking;
    }
}
