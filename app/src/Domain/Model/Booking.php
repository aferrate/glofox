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
}
