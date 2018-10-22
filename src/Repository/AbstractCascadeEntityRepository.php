<?php
/**
 * Created by PhpStorm.
 * User: aprerot
 * Date: 15/05/2018
 * Time: 16:54
 */

namespace App\Repository;

use App\Entity\AbstractTraceableEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;

abstract class AbstractCascadeEntityRepository extends ServiceEntityRepository
{
    /**
     * @param $oldEntities AbstractTraceableEntity[] | ArrayCollection
     * @param $newEntities AbstractTraceableEntity[] | ArrayCollection
     * @throws \Doctrine\ORM\ORMException
     * @throws \Exception
     */
    protected function upsertChildCollection(&$oldEntities, $newEntities)
    {
        if (count($oldEntities) > 0 || count($newEntities) > 0) {

            $oldSourceReferences = $this->getReferencesFromCascadeEntityCollection($oldEntities);

            /**
             * @var $entityRepository AbstractCascadeEntityRepository
             */
            $entityClassName = get_class(isset($oldEntities[0]) ? $oldEntities[0] : $newEntities[0]);
            $entityRepository = $this->getEntityManager()->getRepository($entityClassName);

            if (!$entityRepository instanceof UpdateCascadeInterface) {
                throw  new \Exception("Repository of ".get_class($oldEntities[0])." not implements UpdateCascadeInterface");
            }

            /**
             * @var AbstractTraceableEntity[] $newEntities
             */
            $entityNeedUpdate = [];
            foreach ($newEntities as $newEntity) {
                if (in_array($newEntity->getSourceReference(), $oldSourceReferences)) {
                    // Check if entity need to update
                    $entityNeedUpdate[$newEntity->getSourceReference()] = $newEntity;
                } else {
                    // Add all new entity
                    $oldEntities->add($newEntity);
                }
            }

            // Update all existing Entities who need update
            foreach ($oldEntities as $entity) {
                if (array_key_exists($entity->getSourceReference(), $entityNeedUpdate)) {
                    $entityRepository->update($entity, $entityNeedUpdate[$entity->getSourceReference()]);
                }
            }
        }
    }

    /**
     * @param $collection ArrayCollection | AbstractTraceableEntity[]
     * @return array
     */
    protected function getReferencesFromCascadeEntityCollection($collection)
    {
        return array_map(
            function (AbstractTraceableEntity $entity) {
                return $entity->getSourceReference();
            },
            $collection->toArray());
    }
}