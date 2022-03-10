<?php

namespace App\Repository;

use App\Entity\Coursss;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Coursss|null find($id, $lockMode = null, $lockVersion = null)
 * @method Coursss|null findOneBy(array $criteria, array $orderBy = null)
 * @method Coursss[]    findAll()
 * @method Coursss[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CoursssRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Coursss::class);
    }

    /**
     * @return Coursss[]
     */
    public function findPlanBySujet($sujet){
        return $this->createQueryBuilder('Coursss')
            ->andWhere('Coursss.nom LIKE :sujet or Coursss.description LIKE :sujet  or Coursss.id Like :sujet')
            ->setParameter('sujet', '%'.$sujet.'%')
            ->getQuery()
            ->getResult();
    }






    // /**
    //  * @return Coursss[] Returns an array of Coursss objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Coursss
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

}
