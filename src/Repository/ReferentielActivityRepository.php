<?php

namespace App\Repository;

use App\Entity\ReferentielActivity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class ReferentielActivityRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ReferentielActivity::class);
    }

    public function findAllById()
    {
        return $this->findBy(array(), array('id' => 'ASC'));
    }



}
