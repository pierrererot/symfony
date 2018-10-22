<?php

namespace App\Repository;

use App\Entity\Trader;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class TraderRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Trader::class);
    }

    public function findAllById()
    {
        return $this->findBy(array(), array('id' => 'ASC'));
    }

    public function findByCode($code)
    {
        $qb = $this->createQueryBuilder('t')
            ->andWhere('t.code = :code')
            ->setParameter('code', $code)
            ->getQuery();

        return $qb->execute();
    }



}
