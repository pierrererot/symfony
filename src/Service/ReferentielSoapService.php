<?php
/**
 * Created by PhpStorm.
 * User: aprerot
 * Date: 18/09/2018
 * Time: 16:07
 */

namespace App\Service;

use App\Entity\Address;
use App\Entity\Agency;
use App\Entity\Checkpoint;
use App\Entity\Contact;
use App\Entity\ReferentielActivity;
use App\Entity\ReferentielBenefit;
use App\Entity\ReferentielEAN;
use App\Entity\ReferentielExploitation;
use App\Repository\AgencyRepository;
use App\Repository\ReferentielActivityRepository;
use App\Repository\ReferentielBenefitRepository;
use App\Repository\ReferentielEANRepository;
use App\Repository\ReferentielExploitationRepository;


class ReferentielSoapService extends AbstractSoapService
{
    /**
     * @param \App\Service\SoapComplexType\ReferentielProduit[] $produitList
     * @return bool
     * @throws \Doctrine\ORM\ORMException
     */
    public function upsertProduit( array $produitList)
    {
        /**
         * @var ReferentielEANRepository $clientRepository
         */
        $productRepository = $this->doctrine->getRepository(ReferentielEAN::class);
        foreach ($produitList as $product) {

            // search existing product or initialize new product
            $entity = $productRepository->findOneBy(["code" => $product->code]);

            if (!$entity instanceof ReferentielEAN) {
                $entity = new ReferentielEAN();
            }

            $entity->setCode($product->code);
            $entity->setLabel($product->label);
            $entity->setDatabaseName($product->database);
            !empty($product->joncture) ? $entity->setJoncture($product->joncture): null;

            $entity->setHeight($product->hauteur);
            $entity->setLength($product->longueur);
            $entity->setWidth($product->largeur);
            $entity->setWeight($product->poids);
            $entity->setFm0Spt($product->fm0Spt);
            $entity->setFm0Apt($product->fm0Apt);

            $this->doctrine->getEntityManager()->persist($entity);
        }
        $this->doctrine->getEntityManager()->flush();
        return true;
    }

    /**
     * @param \App\Service\SoapComplexType\ReferentielActivite[] $activiteList
     * @return bool
     * @throws \Doctrine\ORM\ORMException
     */
    public function upsertActivite( array $activiteList)
    {
        /**
         * @var ReferentielActivityRepository $repository
         */
        $repository = $this->doctrine->getRepository(ReferentielActivity::class);
        foreach ($activiteList as $activity) {

            // search existing activity or initialize a new
            $entity = $repository->findOneBy(["code" => $activity->code]);
            if (!$entity instanceof ReferentielActivity) {
                $entity = new ReferentielActivity();
            }

            $entity->setCode($activity->code);
            $entity->setLabel($activity->label);
            $entity->setDatabaseName($activity->database);
            !empty($activity->joncture) ? $entity->setJoncture($activity->joncture): null;

            $entity->setFm0Apt($activity->fm0Apt);

            $this->doctrine->getEntityManager()->persist($entity);
        }
        $this->doctrine->getEntityManager()->flush();
        return true;
    }

    /**
     * @param \App\Service\SoapComplexType\ReferentielTipf[] $tipfList
     * @return bool
     * @throws \Doctrine\ORM\ORMException
     */
    public function upsertTipf( array $tipfList)
    {
        /**
         * @var ReferentielExploitationRepository $repository
         */
        $repository = $this->doctrine->getRepository(ReferentielExploitation::class);
        foreach ($tipfList as $tipf) {

            // search existing exploitation or initialize a new
            $entity = $repository->findOneBy(["code" => $tipf->code]);
            if (!$entity instanceof ReferentielExploitation) {
                $entity = new ReferentielExploitation();
            }

            $entity->setCode($tipf->code);
            $entity->setLabel($tipf->label);
            $entity->setDatabaseName($tipf->database);
            !empty($tipf->joncture) ? $entity->setJoncture($tipf->joncture): null;

            $entity->setLabelExtranet($tipf->label_extranet);
            $this->doctrine->getEntityManager()->persist($entity);
        }
        $this->doctrine->getEntityManager()->flush();
        return true;
    }

    /**
     * @param \App\Service\SoapComplexType\ReferentielPrestation[] $prestationList
     * @return bool
     * @throws \Doctrine\ORM\ORMException
     */
    public function upsertPrestation( array $prestationList)
    {
        /**
         * @var ReferentielBenefitRepository $repository
         */
        $repository = $this->doctrine->getRepository(ReferentielBenefit::class);
        foreach ($prestationList as $prestation) {

            // search existing benefit or initialize a new
            $entity = $repository->findOneBy(["code" => $prestation->code]);
            if (!$entity instanceof ReferentielBenefit) {
                $entity = new ReferentielBenefit();
            }

            $entity->setCode($prestation->code);
            $entity->setLabel($prestation->label);
            $entity->setDatabaseName($prestation->database);
            !empty($prestation->joncture) ? $entity->setJoncture($prestation->joncture): null;

            $entity->setFm0Apt($prestation->fm0Apt);
            $entity->setFm1Apt($prestation->fm1Apt);

            $this->doctrine->getEntityManager()->persist($entity);
        }
        $this->doctrine->getEntityManager()->flush();
        return true;
    }

    /**
     * @param \App\Service\SoapComplexType\Agence[] $agenceList
     * @return bool
     * @throws \Doctrine\ORM\ORMException
     */
    public function upsertAgence( array $agenceList)
    {
        /**
         * @var AgencyRepository $repository
         */
        $repository = $this->doctrine->getRepository(Agency::class);
        foreach ($agenceList as $agence) {

            // search existing agency or initialize a new
            $entity = $repository->findOneBy(["code" => $agence->code]);
            if (!$entity instanceof Agency) {
                $entity = new Agency();
            }

            $entity->setCode($agence->code);
            $entity->setDatabase($agence->database);
            $entity->setCheckpoint( $this->serializeCheckpoint($agence->informations));

            $this->doctrine->getEntityManager()->persist($entity);
        }
        $this->doctrine->getEntityManager()->flush();
        return true;
    }


}