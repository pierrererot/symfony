<?php
/**
 * Created by PhpStorm.
 * User: aprerot
 * Date: 10/04/2018
 * Time: 11:46
 */

namespace App\Service;

use App\Entity\Address;
use App\Entity\Agency;
use App\Entity\Checkpoint;
use App\Entity\Client;
use App\Entity\Contact;
use App\Entity\Orders;
use App\Entity\OrderStatus;
use App\Entity\ReferentielActivity;
use App\Entity\ReferentielBenefit;
use App\Entity\ReferentielExploitation;
use App\Entity\Trader;
use App\Repository\ClientRepository;
use App\Service\SoapComplexType\Command;

class ClientSoapService extends AbstractSoapService implements ClientServiceInterface
{
    /**
     * @param Client $client
     * @param Command[] $commands
     * @return array|null
     */
    private function serializeCommandsToOrders($client, array $commands)
    {
        $orders = [];

        if( ! count($commands) > 0){
            return null;
        }

        // Create Cascade Entities
        /**
         * @var $newOrder Orders
         */
        foreach ( $commands as $command)
        {
            // Site d'enlevement et de destination.
            $siteEnlevement = (!empty($command->siteElevement))?$this->serializeCheckpoint($command->siteElevement):null;
            $siteDestination = (!empty($command->siteDestination))?$this->serializeCheckpoint($command->siteDestination):null;

            // Status
            $status = $this->doctrine->getRepository(OrderStatus::class)->findOneBy(["name" => strtoupper($command->typ2_cst)]);

            // Activité
            $activity = $this->doctrine->getRepository(ReferentielActivity::class)->findOneBy(["code" => strtoupper($command->acti)]);

            // Portefeuille
            $portefeuille = $this->doctrine->getRepository(ReferentielExploitation::class)->findOneBy(["code" => strtoupper($command->tipf)]);

            // Prestation
            $prestation = $this->doctrine->getRepository(ReferentielBenefit::class)->findOneBy(["code" => strtoupper($command->prst)]);

            // Commercial
            $commercial = $this->doctrine->getRepository(Trader::class)->findOneBy(["code" => strtoupper($command->titr)]);

            // Agence
            $agence = $this->doctrine->getRepository(Agency::class)->findOneBy(["code" => strtoupper($command->agce)]);

            $order = new Orders();
            $order->setClient($client)
                ->setInitialCheckpoint($siteEnlevement)
                ->setFinalCheckpoint($siteDestination)
                ->setAgency($agence)
                ->setStatus($status)
                ->setActivity($activity)
                ->setExploitation($portefeuille)
                ->setBenefit($prestation)
//                ->setTrader($commercial)
            ;

            $order->setSourceReference($command->ortr);
            $order->setCreatedBy('ClientSoapService');

            // references appartenant au client
            $order->setClientOrderReference($command->refcde);
            $order->setClientOrderClient($command->cdeclt);

            // facturation & administratif
            $order->setDeal($command->affaire_id);
            $order->setBillNumber($command->fac);
            $order->setBillDate($this->extractDate($command->dtfac));
            $order->setBillPreTaxAmount($command->mtnet);
            $order->setBillingCompany($command->socf);
            $order->setFolderReference($command->refdos);
            $order->setRefot($command->refot);
            $order->setModelInternationalConsigment($command->cmr); // Lettre de voiture (Règlementation Européen pour le transport de marchandises)
            $order->setQuotationReference($command->ddev);
            $order->setRecep($command->recep);

            // Preneur et donneur d'ordre
            $order->setPrincipal($command->dord);
            $order->setContractor($command->pord);
            $order->setNPrincipal($command->ndord);
            $order->setInputOperator($command->acom);

            // Information lié à Elèvement / Départ
            $order->setInitialSeqc($command->seqce);
            $order->setInitialRegion($command->rege);
            $order->setInitialCity($command->ve);

            $order->setInitialArrivingAt($this->extractDate($command->rappDtarr_e));
            $order->setInitialLeavingAt($this->extractDate($command->rappDtdep_e));

            // Information lié à la Destination / Final
            $order->setFinalSeqc($command->seqcd);
            $order->setFinalRegion($command->regd);
            $order->setFinalCity($command->vd);
            $order->setFinalDate($this->extractDate($command->dtcd1));
            $order->setFinalArrivingAt($this->extractDate($command->rappDtarr_d));
            $order->setFinalLeavingAt($this->extractDate($command->rappDtdep_d));

            $order->setRdvAt($this->extractDate($command->rdvDate));

            /**
             * @var \App\Service\SoapComplexType\Phase $newPhase
             */
            if(!empty($command->phases)){
                foreach ($command->phases as $newPhase)
                {
                    $orderPhase = new \App\Entity\OrderPhase();

                    $orderPhase->setSourceReference($newPhase->ottp);
                    $orderPhase->setCreatedBy('ClientSoapService');

                    $orderPhase->setCodeVoya($newPhase->voya);

                    $orderPhase->setChartered($newPhase->afreete);
                    $orderPhase->setRealCo2Quantity($newPhase->co2_reel);
                    $orderPhase->setDriverOne($newPhase->cond1);
                    $orderPhase->setDriverTwo($newPhase->cond2);

                    $orderPhase->setStartedAt($this->extractDate($newPhase->dtr1));
                    $orderPhase->setArrivedAt($this->extractDate($newPhase->dtr2));

                    $orderPhase->setDayStarted($newPhase->dt2);
                    $orderPhase->setDayArrived($newPhase->dt2);
                    $orderPhase->setHourStarted($newPhase->h1);
                    $orderPhase->setHourArrived($newPhase->h2);
                    $orderPhase->setKm($newPhase->kms);
                    $orderPhase->setKmEmptyRun($newPhase->kmsv);
                    $orderPhase->setKmEmptyRunOnApproached($newPhase->kmsva);
                    $orderPhase->setMttraf($newPhase->mttraf);

                    $orderPhase->setTrailer($newPhase->remq);
                    $orderPhase->setTruck($newPhase->trac);

                    $order->addPhase($orderPhase);
                }
            }
            $orders[] = $order;
        }
        return $orders;
    }

    /**
     * @param string $clientCode
     * @param \App\Service\SoapComplexType\Command[] $commands
     * @return bool;
     * @throws \Doctrine\ORM\ORMException
     */
    public function updateClientCommands($clientCode, array $commands)
    {
        /**
         * @var ClientRepository $clientRepository
         */
        $clientRepository = $this->doctrine->getRepository(Client::class);

        /**
         * @var Client $client
         */
        $client = $clientRepository->findOneBy(["sourceReference" => $clientCode]);
        if(!$client){
            return "Client $clientCode does not exist";
        }

        $newOrders = $this->serializeCommandsToOrders($client, $commands);
        if(!$newOrders){
            return "No Commands detected for Client $clientCode";
        }

        return $clientRepository->updateClientWithOrders( $client, $newOrders);
    }

    /**
     * @param \App\Service\SoapComplexType\Client | \App\Service\SoapComplexType\Client $client
     * @return bool
     * @throws \Doctrine\ORM\ORMException
     */
    public function upsertClient( $client)
    {
        if(!isset($client->code)){
            return "The reference 'code' must be completed (CLT?)";
        }

        /**
         * @var ClientRepository $clientRepository
         */
        $clientRepository = $this->doctrine->getRepository(Client::class);
        $clientEntity = $clientRepository->findOneBy(["sourceReference" => $client->code]);

        if( $clientEntity instanceof Client){
            // UPDATE
            $clientEntity->setUpdatedBy(ClientSoapService::class);

            $checkpoint = $clientEntity->getCheckpoint();
            if(!$checkpoint instanceof Checkpoint){
                $checkpoint = new Checkpoint();
                $checkpoint->setAddress(new Address());
                $checkpoint->setContact(new Contact());
                $checkpoint->setContactRdv(new Contact());

                $clientEntity->setCheckpoint($checkpoint);
            }
            $checkpoint->setLabel($client->informations->label);

            $address = $checkpoint->getAddress();
            $address
                ->setCountry($client->informations->country)
                ->setRecipient1($client->informations->recipient1)
                ->setRecipient2($client->informations->recipient2)
                ->setRecipient3($client->informations->recipient3)
                ->setStreet1($client->informations->street1)
                ->setStreet2($client->informations->street2)
                ->setStreet3($client->informations->street3)
                ->setPostcode($client->informations->postcode)
                ->setCity($client->informations->city)
                ->setCedex($client->informations->cedex)
            ;

            $contact = $checkpoint->getContact();
            $contact->setEmail($client->informations->contactEmail)
                ->setPhoneNumber($client->informations->contactPhoneNumber)
                ->setFaxNumber($client->informations->contactFax)
                ->setName($client->informations->contactFullName)
            ;

            $contactRdv = $checkpoint->getContactRdv();
            $contactRdv->setEmail($client->informations->contactRdvEmail)
                ->setPhoneNumber($client->informations->contactRdvPhoneNumber)
                ->setFaxNumber($client->informations->contactRdvFax)
                ->setName($client->informations->contactRdvFullName)
            ;

        } else {
            // CREATE

            $clientEntity = new Client();
            $clientEntity->setCreatedBy(ClientSoapService::class);
            $clientEntity->setSourceReference($client->code);

            $checkpoint = $this->serializeCheckpoint($client->informations);
            $clientEntity->setCheckpoint($checkpoint);

        }
        $clientEntity->setSiren($client->siren);
        $clientEntity->setSiret($client->siret);
        $clientEntity->setIntraCommunityVat($client->tvaIntraCom);
        $clientEntity->setCharged($client->facture);
        $clientEntity->setBiller($client->factureur);

        $this->doctrine->getEntityManager()->persist($clientEntity);
        $this->doctrine->getEntityManager()->flush();
        return true;
    }


}