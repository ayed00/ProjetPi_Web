<?php

namespace App\Repository;

class AvisRepository
{
    public function orderStartD()
    {
        return $this->getEntityManager()
            ->createQuery('SELECT p FROM AppBundle:avis p  ORDER BY p.rating  ASC')
            ->getResult();
    }
}