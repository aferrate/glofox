<?php

namespace App\Application\UseCases\Booking;

use App\Domain\Repository\BookingRepositoryInterface;
use App\Domain\Repository\ClassroomRepositoryInterface;
use App\Domain\Repository\MemberRepositoryInterface;
use App\Domain\Service\SerializerInterface;
use App\Domain\Validations\BookingChecker;

class AddBooking
{
    private $bookingRepository;
    private $bookingChecker;
    private $memberRepository;
    private $classroomRepository;
    private $serializer;

    public function __construct(
        BookingRepositoryInterface $bookingRepository,
        MemberRepositoryInterface $memberRepository,
        ClassroomRepositoryInterface $classroomRepository,
        BookingChecker $bookingChecker,
        SerializerInterface $serializer
    ) {
        $this->bookingRepository = $bookingRepository;
        $this->memberRepository = $memberRepository;
        $this->classroomRepository = $classroomRepository;
        $this->bookingChecker = $bookingChecker;
        $this->serializer = $serializer;
    }

    public function execute(array $bookingArr): array
    {
        try {
            $checkParams = $this->bookingChecker->checkParams($bookingArr);

            if (false === $checkParams['status']) {
                return ['status' => false, 'data' => ['message' => $checkParams['message']]];
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

            $booking = $this->serializer->deserialize($bookingArr, 'booking');

            $id = $this->bookingRepository->save($booking);

            return [
                'status' => true,
                'data' => [
                    'message' => 'booking created!',
                    'id' => $id,
                ],
            ];
        } catch (\Exception $e) {
            return ['status' => false, 'data' => ['message' => $e->getMessage()]];
        }
    }
}
