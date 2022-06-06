<?php

namespace App\Domain\Repository;

use App\Domain\Model\Member;

interface MemberRepositoryInterface
{
    public function save(Member $member): int;
    public function delete(Member $member): void;
    public function findAll(): array;
    public function findOneById(int $id): ?Member;
    public function findOneByName(string $name): ?Member;
}
