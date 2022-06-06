<?php

namespace App\Repository;

use App\Domain\Model\Classroom;
use App\Domain\Repository\ClassroomRepositoryInterface;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityManagerInterface;
use DateTime;

final class ClassroomRepository implements ClassroomRepositoryInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function save(Classroom $classroom): int
    {
        $this->entityManager->persist($classroom);
        $this->entityManager->flush();

        return $classroom->getId();
    }

    public function delete(Classroom $classroom): void
    {
        $this->entityManager->remove($classroom);
        $this->entityManager->flush();
    }

    public function findAll(): array
    {
        return $this->entityManager->getRepository(Classroom::class)->findAll();
    }

    public function findOneById(int $id): ?Classroom
    {
        return $this->entityManager->getRepository(Classroom::class)->findOneBy(['id' => $id]);
    }

    public function findOneByNameAndDatesAndCapacity(array $params): ?Classroom
    {
        $startDate = DateTime::createFromFormat('d-m-Y', $params['start_date']);
        $endDate = DateTime::createFromFormat('d-m-Y', $params['end_date']);

        return $this->entityManager->getRepository(Classroom::class)
            ->findOneBy(['name' => $params['name'], 'startDate' => $startDate, 'endDate' => $endDate, 'capacity' => $params['capacity']]);
    }
}
