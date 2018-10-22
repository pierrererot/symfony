<?php

namespace App\Repository;

use App\Entity\Agency;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class AgencyRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Agency::class);
    }

    public function findAllById()
    {
        return $this->findBy(array(), array('id' => 'ASC'));
    }

    public function getAgencies($client_id) {
        $qb = $this->createQueryBuilder('a')
            ->select('a, cl, tr')
            ->leftJoin('a.client', 'cl')
            ->leftJoin('a.trader', 'tr')
            ->where('a.client = :client_id')
            ->setParameter('client_id', $client_id)
            ->getQuery();

        if (sizeof($qb->execute())) {
            return $qb->execute();
        } else {
            return null;
        }
    }



}
