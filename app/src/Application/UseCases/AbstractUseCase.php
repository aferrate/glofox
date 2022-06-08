<?php

namespace App\Application\UseCases;

use App\Domain\Repository\BookingRepositoryInterface;

abstract class AbstractUseCase
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
