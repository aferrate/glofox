<?php

namespace App\Domain\Repository;

use App\Domain\Model\Classroom;

interface ClassroomRepositoryInterface
{
    public function save(Classroom $class): int;
    public function delete(Classroom $class): void;
    public function findAll(): array;
    public function findOneById(int $id): ?Classroom;
    public function findOneByNameAndDatesAndCapacity(array $params): ?Classroom;
}
