<?php

namespace App\Domain\Model;

use DateTime;

final class Booking
{
    private $id;
    private $idMember;
    private $idClassroom;
    private $date;

    public function getId(): int
    {
        return $this->id;
    }

    public function getIdMember(): int
    {
        return $this->idMember;
    }

    public function setIdMember(int $idMember): void
    {
        $this->idMember = $idMember;
    }

    public function getIdClassroom(): int
    {
        return $this->idClassroom;
    }

    public function setIdClassroom(int $idClassroom): void
    {
        $this->idClassroom = $idClassroom;
    }

    public function getDate(): DateTime
    {
        return $this->date;
    }

    public function setDate(DateTime $date): void
    {
        $this->date = $date;
    }
}
