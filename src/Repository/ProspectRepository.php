<?php

namespace App\Repository;

use App\Entity\Prospect;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class ProspectRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Prospect::class);
    }

    public function findByEmail($mail) {
        return $this->createQueryBuilder('p')
            ->andWhere('p.email = :mail')
            ->setParameter('mail', $mail)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    public function findByHash($hash) {
        return $this->createQueryBuilder('p')
            ->andWhere('p.hash = :hash')
            ->setParameter('hash', $hash)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }

}
