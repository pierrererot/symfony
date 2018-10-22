<?php

namespace App\Repository;

use App\Entity\ReferentielEAN;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class ReferentielEANRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ReferentielEAN::class);
    }

    public function findAllById()
    {
        return $this->findBy(array(), array('id' => 'ASC'));
    }



}
