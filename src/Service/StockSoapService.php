<?php
/**
 * Created by PhpStorm.
 * User: aprerot
 * Date: 07/06/2018
 * Time: 09:53
 */

namespace App\Service;

use App\Entity\Agency;
use App\Entity\Client;
use App\Service\SoapComplexType\Movement as MovementComplexType;
use App\Entity\Movement as MovementEntity;
use App\Repository\Stock\StockMovementRepository;

/**
 * Class StockSoapService
 * @package App\Service
 */
class StockSoapService extends AbstractSoapService
{
    /**
     * @param MovementComplexType $movement
     * @return bool
     * @throws \Doctrine\ORM\ORMException
     */
    public function create( MovementComplexType $movement){

        /**
         * @var $repository StockMovementRepository
         */
        $repository = $this->doctrine->getRepository(MovementEntity::class);

        $movementEntity = $this->serializeMovementToMovementEntities($movement, new MovementEntity());
        $repository->replace($movementEntity);

        return true;
    }

    /**
     * @param $cde
     * @param MovementComplexType $movement
     * @throws \Doctrine\ORM\ORMException
     */
    public function updateByCDE($cde, MovementComplexType $movement){
        /**
         * @var $repository StockMovementRepository
         */
        $repository = $this->doctrine->getRepository(MovementEntity::class);

        /**
         * @var $initialMovementEntity MovementEntity
         */
        $initialMovementEntity = $repository->findOneBy(
            [ "internalOrderReference" => $cde ],
            ["id" => "DESC"]
        );
        if(is_null($initialMovementEntity)) {
            throw new \Exception("Movement with CDE equal to $cde does not exist");
        }

        $movementEntity = $this->serializeMovementToMovementEntities($movement, $initialMovementEntity);
        $repository->replace($movementEntity);
    }

    /**
     * @param $date
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function deleteByCreatedAt($date){

        /**
         * @var $repository StockMovementRepository
         */
        $repository = $this->doctrine->getRepository(MovementEntity::class);
        $entitiesToDelete = $repository->findBy(["createdAt" => new \DateTime($date)]);

        $repository->deleteEntities($entitiesToDelete);
    }

    /**
     * @param MovementComplexType $movement
     * @param MovementEntity $initialMovementEntity
     * @return MovementEntity
     */
    private function serializeMovementToMovementEntities(MovementComplexType $movement, MovementEntity $initialMovementEntity){

        $movementEntity  = $initialMovementEntity;

        $movementEntity->setClient($this->getOneEntityByCriteria(Client::class, ["sourceReference" => $movement->CLIENT_CODE]));
        $movementEntity->setAgency($this->getOneEntityByCriteria(Agency::class, ["code" => $movement->AGENCE_CODE]));

        ($movement->TPE_TRA) ? $movementEntity->setTravelType($movement->TPE_TRA):null;
        ($movement->ORIG) ? $movementEntity->setOrigin($movement->ORIG):null;
        ($movement->DEST) ? $movementEntity->setDestination($movement->DEST):null;
        ($movement->CDE) ? $movementEntity->setInternalOrderReference($movement->CDE):null;
        ($movement->REF_CDE) ? $movementEntity->setExternalOrderReference($movement->REF_CDE):null;
        ($movement->DATE) ? $movementEntity->setMovedAt(new \DateTime($movement->DATE)):null;
        ($movement->TPE) ? $movementEntity->setMovementType($movement->TPE):null;
        ($movement->QTE_THEO) ? $movementEntity->setTargetQuantity($movement->QTE_THEO):null;
        ($movement->QTE_REEL) ? $movementEntity->setActualQuantity($movement->QTE_REEL):null;
        ($movement->DT_APP) ? $movementEntity->setCreatedAt(new \DateTime($movement->DT_APP)):null;
        ($movement->ARTICLE) ? $movementEntity->setItems($movement->ARTICLE):null;
        ($movement->MVT) ? $movementEntity->setMovement($movement->MVT):null;
        ($movement->ETAT) ? $movementEntity->setCondition($movement->ETAT):null;
        ($movement->AFFAIRE) ? $movementEntity->setDeal($movement->AFFAIRE):null;
        ($movement->USER) ? $movementEntity->setUsername($movement->USER):null;
        ($movement->REF1) ? $movementEntity->setRef1($movement->REF1):null;
        ($movement->REF2) ? $movementEntity->setRef2($movement->REF2):null;
        ($movement->DF) ? $movementEntity->setDf($movement->DF):null;
        ($movement->TYPE_FACTU) ? $movementEntity->setBillType($movement->TYPE_FACTU):null;
        ($movement->PU) ? $movementEntity->setUnitaryPrice($movement->PU):null;

        return $movementEntity;
    }

    /**
     * @param $className
     * @param array $criteria
     * @return null|object | Client | Agency
     */
    public function getOneEntityByCriteria($className, array $criteria)
    {
        /**
         * @var $repository StockMovementRepository
         */
        $repository = $this->doctrine->getRepository($className);

        /**
         * @var $initialMovementEntity MovementEntity
         */
        return $repository->findOneBy(
            $criteria,
            ["id" => "DESC"]
        );
    }
}