<?php

namespace App\Domain\Validations;

use DateTime;

final class BookingChecker
{
    public function checkId(int $id): array
    {
        if(!is_int($id) || $id < 1) {
            return ['status' => false, 'message' => 'id must be integer and greater than 0'];
        }

        return ['status' => true, 'message' => 'ok'];
    }

    public function checkParams(array $params): array
    {
        if(!$this->checkMandatoryParams($params)) {
            return ['status' => false, 'message' => 'need mandatory parameters'];    
        }

        if(!$this->checkMemberId($params['idMember'])) {
            return ['status' => false, 'message' => 'member id must be integer and greater than 0'];    
        }

        if(!$this->checkClassroomId($params['idClassroom'])) {
            return ['status' => false, 'message' => 'classroom id must be integer and greater than 0'];    
        }

        if(!$this->checkDate($params['date'])) {
            return ['status' => false, 'message' => 'date parameter wrong'];    
        }

        if(!$this->checkDateEarlierThanToday($params['date'])) {
            return ['status' => false, 'message' => 'date parameter cannot be earlier than today'];    
        }

        return ['status' => true, 'message' => 'ok'];
    }

    private function checkMandatoryParams(array $params): bool
    {
        if(!isset($params['idMember']) || !isset($params['idClassroom']) || !isset($params['date'])) {
            return false;
        }

        return true;
    }

    private function checkMemberId(int $id): array
    {
        if(!is_int($id) || $id < 1) {
            return ['status' => false, 'message' => ' member id must be integer and greater than 0'];
        }

        return ['status' => true, 'message' => 'ok'];
    }

    private function checkClassroomId(int $id): array
    {
        if(!is_int($id) || $id < 1) {
            return ['status' => false, 'message' => ' classroom id must be integer and greater than 0'];
        }

        return ['status' => true, 'message' => 'ok'];
    }

    private function checkDate(string $date): bool
    {
        if(DateTime::createFromFormat('d-m-Y', $date) === false) {
            return false;
        }

        return true;
    }

    private function checkDateEarlierThanToday(string $date): bool
    {
        $dateToday = new DateTime();

        if(DateTime::createFromFormat('d-m-Y', $date) < $dateToday->format('d-m-Y')) {
            return false;
        }

        return true;
    }
}
