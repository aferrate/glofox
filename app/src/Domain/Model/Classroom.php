<?php

namespace App\Domain\Model;

use DateTime;

final class Classroom
{
    private $id;
    private $name;
    private $capacity;
    private $startDate;
    private $endDate;
    
    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return int
     */
    public function getCapacity(): int
    {
        return $this->capacity;
    }

    /**
     * @param int $capacity
     */
    public function setCapacity(int $capacity): void
    {
        $this->capacity = $capacity;
    }

    /**
     * @return DateTime
     */
    public function getStartDate(): DateTime
    {
        return $this->startDate;
    }

    /**
     * @param DateTime $startDate
     */
    public function setStartDate(DateTime $startDate): void
    {
        $this->startDate = $startDate;
    }

    /**
     * @return DateTime
     */
    public function getEndDate(): DateTime
    {
        return $this->endDate;
    }

    /**
     * @param DateTime $endDate
     */
    public function setEndDate(DateTime $endDate): void
    {
        $this->endDate = $endDate;
    }

    /**
     * @return array
     */
    public function returnArrayClassroom(Classroom $classroom): array
    {
        $data[] = [
            'id' => $classroom->getId(),
            'name' => $classroom->getName(),
            'capacity' => $classroom->getCapacity(),
            'startDate' => $classroom->getStartDate(),
            'endDate' => $classroom->getEndDate()
        ];

        return $data;
    }

    /**
     * @return Classroom
     */
    public static function returnObjClassroom(Classroom $classroom, array $classroomArr): Classroom
    {
        $classroom->setName($classroomArr['name']);
        $classroom->setCapacity($classroomArr['capacity']);
        $classroom->setStartDate(DateTime::createFromFormat('d-m-Y', $classroomArr['start_date']));
        $classroom->setEndDate(DateTime::createFromFormat('d-m-Y', $classroomArr['end_date']));

        return $classroom;
    }
}
