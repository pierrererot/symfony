<?php

namespace App\Repository;

use App\Entity\Users;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class UserRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Users::class);
    }

    public function getClients($username) {
        $qb = $this->createQueryBuilder('u')
            ->select('u, cl, ag')
            ->leftJoin('u.clients', 'cl')
            ->leftJoin('App:Agency', 'ag', 'WITH', 'ag.client = cl.id')
            ->where('u.username = :username')
            ->setParameter('username', $username)
            ->getQuery();

        if (sizeof($qb->execute())) {
            return $qb->execute()[0]->getClients();
        } else {
            return null;
        }
    }

    public function findByLogin($login) {
        return $this->createQueryBuilder('u')
            ->andWhere('u.login = :login')
            ->setParameter('login', $login)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }

    public function findByUername($username) {
        return $this->createQueryBuilder('u')
            ->andWhere('u.username = :username')
            ->setParameter('username', $username)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }
}
