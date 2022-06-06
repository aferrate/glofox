<?php

namespace App\Repository;

use App\Domain\Model\Member;
use App\Domain\Repository\MemberRepositoryInterface;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityManagerInterface;

final class MemberRepository implements MemberRepositoryInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function save(Member $member): int
    {
        $this->entityManager->persist($member);
        $this->entityManager->flush();

        return $member->getId();
    }

    public function delete(Member $member): void
    {
        $this->entityManager->remove($member);
        $this->entityManager->flush();
    }

    public function findAll(): array
    {
        return $this->entityManager->getRepository(Member::class)->findAll();
    }

    public function findOneById(int $id): ?Member
    {
        return $this->entityManager->getRepository(Member::class)->findOneBy(['id' => $id]);
    }

    public function findOneByName(string $name): ?Member
    {
        return $this->entityManager->getRepository(Member::class)->findOneBy(['name' => $name]);
    }
}
