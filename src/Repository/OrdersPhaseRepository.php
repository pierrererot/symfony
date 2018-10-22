<?php
/**
 * Created by PhpStorm.
 * User: aprerot
 * Date: 07/09/2018
 * Time: 10:30
 */

namespace App\Repository;


use App\Entity\OrderPhase;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class OrdersPhaseRepository extends ServiceEntityRepository implements UpdateCascadeInterface
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, OrderPhase::class);
    }


    /**
     * @param $oldEntity OrderPhase
     * @param $newEntity OrderPhase
     * @return mixed|void
     * @throws \Doctrine\ORM\ORMException
     */
    public function update(&$oldEntity, $newEntity)
    {
        $oldEntity->setCodeVoya($newEntity->getCodeVoya());
        $oldEntity->setChartered($newEntity->getChartered());
        $oldEntity->setKm($newEntity->getKm());
        $oldEntity->setKmEmptyRun($newEntity->getKmEmptyRun());
        $oldEntity->setKmEmptyRunOnApproached($newEntity->getKmEmptyRunOnApproached());
        $oldEntity->setDayArrived($newEntity->getDayArrived());
        $oldEntity->setHourArrived($newEntity->getHourArrived());
        $oldEntity->setStartedAt($newEntity->getStartedAt());
        $oldEntity->setHourStarted($newEntity->getHourStarted());
        $oldEntity->setDriverOne($newEntity->getDriverOne());
        $oldEntity->setDriverTwo($newEntity->getDriverTwo());
        $oldEntity->setMttraf($newEntity->getMttraf());
        $oldEntity->setRealCo2Quantity($newEntity->getRealCo2Quantity());
        $oldEntity->setTrailer($newEntity->getTrailer());
        $oldEntity->setTruck($newEntity->getTruck());
        $oldEntity->setOrders($newEntity->getOrders());

        $this->getEntityManager()->persist($oldEntity);
    }
}