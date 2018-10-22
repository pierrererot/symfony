<?php

namespace App\Repository;

use App\Entity\ReferentielTrader;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class ReferentielTraderRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ReferentielTrader::class);
    }

    public function findAllById()
    {
        return $this->findBy(array(), array('id' => 'ASC'));
    }



}
