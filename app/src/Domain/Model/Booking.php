<?php

namespace App\Domain\Model;

use DateTime;

final class Booking
{
    private $id;
    private $idMember;
    private $idClassroom;
    private $date;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getIdMember(): int
    {
        return $this->idMember;
    }

    /**
     * @param int $idMember
     */
    public function setIdMember(int $idMember): void
    {
        $this->idMember = $idMember;
    }

    /**
     * @return int
     */
    public function getIdClassroom(): int
    {
        return $this->idClassroom;
    }

    /**
     * @param int $idClassroom
     */
    public function setIdClassroom(int $idClassroom): void
    {
        $this->idClassroom = $idClassroom;
    }

    /**
     * @return DateTime
     */
    public function getDate(): DateTime
    {
        return $this->date;
    }

    /**
     * @param DateTime $date
     */
    public function setDate(DateTime $date): void
    {
        $this->date = $date;
    }

    /**
     * @return array
     */
    public function returnArrayBooking(Booking $booking): array
    {
        $data[] = [
            'id' => $booking->getId(),
            'memberId' => $booking->getIdMember(),
            'classroomId' => $booking->getIdClassroom(),
            'date' => $booking->getDate()
        ];

        return $data;
    }

    /**
     * @return Booking
     */
    public static function returnObjBooking(Booking $booking, array $bookingArr): Booking
    {
        $booking->setIdMember($bookingArr['member_id']);
        $booking->setIdClassroom($bookingArr['classroom_id']);
        $booking->setDate(DateTime::createFromFormat('d-m-Y', $bookingArr['date']));

        return $booking;
    }
}
