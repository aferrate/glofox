<?php

namespace App\Application\UseCases\Classroom;

use App\Domain\Repository\BookingRepositoryInterface;

abstract class AbstractUseCaseClassroom
{
    protected function deleteBookingsByClassroomId(int $id, BookingRepositoryInterface $bookingRepository): void
    {
        $bookingIds = $bookingRepository->findByClassroomId($id);
        $arrIds = [];

        foreach($bookingIds as $id) {
            $arrIds[] = intval($id['id']);
        }

        if(!empty($bookingIds)) {
            $bookingRepository->deleteFromArrayOfIds($arrIds);
        }
    }
}
