<?php

namespace App\Repository;

use App\Domain\Model\Booking;
use App\Domain\Repository\BookingRepositoryInterface;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityManagerInterface;
use DateTime;

final class BookingRepository implements BookingRepositoryInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function save(Booking $booking): int
    {
        $this->entityManager->persist($booking);
        $this->entityManager->flush();

        return $booking->getId();
    }

    public function delete(Booking $booking): void
    {
        $this->entityManager->remove($booking);
        $this->entityManager->flush();
    }

    public function findAll(): array
    {
        return $this->entityManager->getRepository(Booking::class)->findAll();
    }

    public function findOneById(int $id): ?Booking
    {
        return $this->entityManager->getRepository(Booking::class)->findOneBy(['id' => $id]);
    }

    public function findByDateMemberIdClassId(array $params): ?Booking
    {
        $date = DateTime::createFromFormat('d-m-Y', $params['date']);

        return $this->entityManager->getRepository(Booking::class)
            ->findOneBy(['idMember' => $params['member_id'], 'idClassroom' => $params['classroom_id'], 'date' => $date]);
    }

    public function findByClassroomId(int $id): array
    {
        $qb = $this->entityManager->createQueryBuilder();

        $bookingIds = $qb->select('b.id')
            ->from('App:Booking', 'b')
            ->where('b.idClassroom = :idClassroom')
            ->setParameter('idClassroom', $id)
            ->getQuery()
            ->getResult(AbstractQuery::HYDRATE_ARRAY)
        ;

        return $bookingIds;
    }

    public function findByMemberId(int $id): array
    {
        $qb = $this->entityManager->createQueryBuilder();

        $bookingIds = $qb->select('b.id')
            ->from('App:Booking', 'b')
            ->where('b.idMember = :idMember')
            ->setParameter('idMember', $id)
            ->getQuery()
            ->getResult(AbstractQuery::HYDRATE_ARRAY)
        ;

        return $bookingIds;
    }

    public function deleteFromArrayOfIds(array $ids): void
    {
        $qb = $this->entityManager->createQuery('DELETE FROM App:Booking b WHERE b.id in(:ids)')
            ->setParameter('ids', $ids)
            ->getResult()
        ;

        $this->entityManager->flush();
    }

    public function countBookings(array $params): int
    {
        $dateClass = DateTime::createFromFormat('d-m-Y', $params['date']);
        $count = count($this->entityManager->getRepository(Booking::class)
            ->findBy(['idClassroom' => $params['classroom_id'], 'date' => $dateClass]));

        return $count;
    }
}
