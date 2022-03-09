<?php

namespace App\Repository;

use App\Entity\Formmattion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Formmattion|null find($id, $lockMode = null, $lockVersion = null)
 * @method Formmattion|null findOneBy(array $criteria, array $orderBy = null)
 * @method Formmattion[]    findAll()
 * @method Formmattion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FormmattionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Formmattion::class);
    }

    // /**
    //  * @return Formmattion[] Returns an array of Formmattion objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Formmattion
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    public function search($term,$description) {
        if($term !==null && $description==null){
       return $this->createQueryBuilder('Formmattion')
            ->andWhere('Formmattion.nom LIKE :nomm')
            ->setParameter('nomm', '%'.$term.'%')
            ->getQuery()
            ->getResult();

        }
        elseif ($term ==null && $description!==null){
            return $this->createQueryBuilder('Formmattion')

                ->andWhere('Formmattion.description LIKE :description')
                ->setParameter('description', '%'.$description.'%')

                ->getQuery()
                ->getResult();



        }else{
            return $this->createQueryBuilder('Formmattion')
                ->andWhere('Formmattion.nom LIKE :nomm')
                ->andWhere('Formmattion.description LIKE :description')
                ->setParameter('description', '%'.$description.'%')
                ->setParameter('nomm', '%'.$term.'%')
                ->getQuery()
                ->getResult();


        }
    }
    public function orderByPrix()
    {
        return $this->createQueryBuilder('Formmattion')
            ->orderBy('Formmattion.prix', 'ASC')
            ->getQuery()->getResult();
    }


}
