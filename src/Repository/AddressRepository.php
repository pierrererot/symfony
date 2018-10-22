<?php

namespace App\Repository;

use App\Entity\Address;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

class AddressRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Address::class);
    }

    public function getDefaultAddress() {
        $qb = $this->createQueryBuilder('a')
            ->select()
            ->getQuery();
        return $qb->execute();
    }




}
