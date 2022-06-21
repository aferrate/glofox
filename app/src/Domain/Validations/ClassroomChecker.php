<?php

namespace App\Domain\Validations;

use DateTime;

final class ClassroomChecker
{
    public function checkId(int $id): array
    {
        if (!is_int($id) || $id < 1) {
            return ['status' => false, 'message' => 'id must be integer and greater than 0'];
        }

        return ['status' => true, 'message' => 'ok'];
    }

    public function checkParams(array $params): array
    {
        if (!$this->checkMandatoryParams($params)) {
            return ['status' => false, 'message' => 'need mandatory parameters'];
        }

        if (!$this->checkName($params['name'])) {
            return ['status' => false, 'message' => 'name parameter wrong'];
        }

        if (!$this->checkCapacity($params['capacity'])) {
            return ['status' => false, 'message' => 'capacity parameter wrong'];
        }

        if (!$this->checkStartDate($params['start_date'])) {
            return ['status' => false, 'message' => 'start date parameter wrong'];
        }

        if (!$this->checkEndDate($params['end_date'])) {
            return ['status' => false, 'message' => 'end date parameter wrong'];
        }

        if (!$this->checkStartDateLowerOrEqual($params['start_date'], $params['end_date'])) {
            return ['status' => false, 'message' => 'End date cannot be lower than start date'];
        }

        if (!$this->checkStartDateEarlierThanToday($params['start_date'])) {
            return ['status' => false, 'message' => 'start date parameter cannot be earlier than today'];
        }

        if (!$this->checkEndDateEarlierThanToday($params['end_date'])) {
            return ['status' => false, 'message' => 'end date parameter cannot be earlier than today'];
        }

        return ['status' => true, 'message' => 'ok'];
    }

    private function checkMandatoryParams(array $params): bool
    {
        if (!isset($params['name']) || !isset($params['capacity']) || !isset($params['start_date']) || !isset($params['end_date'])) {
            return false;
        }

        return true;
    }

    private function checkName(string $name): bool
    {
        if (!ctype_alpha($name)) {
            return false;
        }

        return true;
    }

    private function checkCapacity(int $capacity): bool
    {
        if ($capacity < 1 || !is_int($capacity)) {
            return false;
        }

        return true;
    }

    private function checkStartDate(string $startDate): bool
    {
        if (false === strtotime($startDate)) {
            return false;
        }

        return true;
    }

    private function checkEndDate(string $endDate): bool
    {
        if (false === strtotime($endDate)) {
            return false;
        }

        return true;
    }

    private function checkStartDateLowerOrEqual(string $startDate, string $endDate): bool
    {
        if (DateTime::createFromFormat('d-m-Y', $startDate) > DateTime::createFromFormat('d-m-Y', $endDate)) {
            return false;
        }

        return true;
    }

    private function checkStartDateEarlierThanToday(string $date): bool
    {
        $dateToday = new DateTime();

        if (DateTime::createFromFormat('d-m-Y', $date) < $dateToday->format('d-m-Y')) {
            return false;
        }

        return true;
    }

    private function checkEndDateEarlierThanToday(string $date): bool
    {
        $dateToday = new DateTime();

        if (DateTime::createFromFormat('d-m-Y', $date) < $dateToday->format('d-m-Y')) {
            return false;
        }

        return true;
    }
}
