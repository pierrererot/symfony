<?php

namespace App\Repository;

use App\Entity\Contact;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class ContactRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Contact::class);
    }


    public function findLimit($limit)
    {
        $qb = $this->createQueryBuilder('c')
            ->setMaxResults($limit)
            ->getQuery()
        ;

        return $qb->execute();
    }

    public function findByUser($userId)
    {
        $qb = $this->createQueryBuilder('c')
            ->select('c, u')
            ->leftJoin('c.users', 'u')
            ->where('u.id = :userId')
            ->setParameter('userId', $userId)
            ->getQuery()
        ;

        return $qb->execute();
    }



}
