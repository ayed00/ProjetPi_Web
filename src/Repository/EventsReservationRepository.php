<?php

namespace App\Repository;

use App\Entity\EventsReservation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method EventsReservation|null find($id, $lockMode = null, $lockVersion = null)
 * @method EventsReservation|null findOneBy(array $criteria, array $orderBy = null)
 * @method EventsReservation[]    findAll()
 * @method EventsReservation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventsReservationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EventsReservation::class);
    }

    // /**
    //  * @return EventsReservation[] Returns an array of EventsReservation objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?EventsReservation
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
