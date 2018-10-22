<?php

namespace App\Repository;

use App\Entity\OrderComment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class OrderCommentRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, OrderComment::class);
    }

    public function findAll()
    {
        return $this->findBy(array(), array('createdAt' => 'ASC'));
    }

    public function findByOrder($order_id)
    {
        $qb = $this->createQueryBuilder('c')
            ->andWhere('c.order = :order_id')
            ->setParameter('order_id', $order_id)
            ->orderBy('c.createdAt', 'ASC')
            ->orderBy('c.updatedAt', 'ASC')
            ->orderBy('c.id', 'ASC')
            ->getQuery();

        return $qb->execute();
    }

}
