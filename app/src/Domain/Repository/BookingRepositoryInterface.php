<?php

namespace App\Domain\Repository;

use App\Domain\Model\Booking;

interface BookingRepositoryInterface
{
    public function save(Booking $booking): int;
    public function delete(Booking $booking): void;
    public function deleteFromArrayOfIds(array $ids): void;
    public function findAll(): array;
    public function findByDateMemberIdClassId(array $params): ?Booking;
    public function findOneById(int $id): ?Booking;
    public function findByClassroomId(int $id): array;
    public function findByMemberId(int $id): array;
    public function countBookings(array $params): int;
}
