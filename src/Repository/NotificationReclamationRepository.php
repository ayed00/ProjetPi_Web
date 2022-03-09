<?php

namespace App\Repository;

use App\Entity\NotificationReclamation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method NotificationReclamation|null find($id, $lockMode = null, $lockVersion = null)
 * @method NotificationReclamation|null findOneBy(array $criteria, array $orderBy = null)
 * @method NotificationReclamation[]    findAll()
 * @method NotificationReclamation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NotificationReclamationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, NotificationReclamation::class);
    }

    // /**
    //  * @return NotificationReclamation[] Returns an array of NotificationReclamation objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('n.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?NotificationReclamation
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
