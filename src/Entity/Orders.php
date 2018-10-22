<?php

namespace App\Entity;

use App\Annotation\ExternalUserFilterAnnotation;
use App\Annotation\InternalUserFilterAnnotation;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Orders.
 *
 * @author
 *
 * @ORM\Table(name="orders")
 * @ORM\Entity(repositoryClass="App\Repository\OrdersRepository")
 *
 * @InternalUserFilterAnnotation(targetFieldName="agency_id")
 * @ExternalUserFilterAnnotation(targetFieldName="client_id")
 */
class Orders extends AbstractTraceableEntity
{
    const TABLE_NAME = "orders";

    /**
     * @var int
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id = null;

    /**
     * The order status calculated in ETL
     *
     * @var OrderStatus
     * @ORM\ManyToOne(targetEntity="OrderStatus")
     */
    private $status;

    /**
     * The final receivers of this Order, contain the last delivery Information
     *
     * @var ArrayCollection | OrderPhase[]
     * @ORM\OneToMany(targetEntity="App\Entity\OrderPhase", mappedBy="orders", cascade={"persist", "remove"})
     */
    private $phases;

    /**
     * The client code
     * @var Client
     * @ORM\ManyToOne(targetEntity="Client", inversedBy="orders")
     */
    private $client;

    /**
     * @var $deal (Affaire)
     * @ORM\Column(type="string",name="deal", nullable=true)
     */
    private $deal;

    /**
     * @var $initialCheckpoint
     * @ORM\OneToOne(targetEntity="App\Entity\Checkpoint",cascade={"all"}, orphanRemoval=true)
     */
    private $initialCheckpoint;

    /**
     * @var $finalCheckpoint
     * @ORM\OneToOne(targetEntity="App\Entity\Checkpoint",cascade={"all"}, orphanRemoval=true)
     */
    private $finalCheckpoint;

    /**
     * @var $operationCheckpoint
     * @ORM\OneToOne(targetEntity="App\Entity\Checkpoint",cascade={"all"}, orphanRemoval=true)
     */
    private $operationCheckpoint;

    /**
     * @var $agency
     * @ORM\ManyToOne(targetEntity="App\Entity\Agency")
     */
    private $agency;

    /**
     * @var $product
     * @ORM\OneToMany(targetEntity="App\Entity\OrderProduct", mappedBy="orders", cascade={"persist", "remove"} )
     */
    private $products;

    /**
     * @var File[] | ArrayCollection
     * @ORM\OneToMany(targetEntity="App\Entity\File", mappedBy="order", cascade={"persist", "remove"})
     */
    private $files;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\ReferentielActivity")
     */
    private $activity;

    /**
     * @var $exploitation
     * @ORM\ManyToOne(targetEntity="App\Entity\ReferentielExploitation")
     */
    private $exploitation;

    /**
     * @var ReferentielBenefit
     * @ORM\ManyToOne(targetEntity="App\Entity\ReferentielBenefit")
     */
    private $benefit;


    /**
     * @ORM\Column(name="client_order_reference", nullable=true, type="string")
     */
    private $clientOrderReference;

    /**
     * @ORM\Column(name="client_order_client", nullable=true, type="string")
     */
    private $clientOrderClient;

    /**
     * @ORM\Column(name="bill_number", nullable=true, type="string")
     */
    private $billNumber;

    /**
     * @ORM\Column(name="bill_date", nullable=true, type="datetime")
     */
    private $billDate;

    /**
     * @ORM\Column(name="bill_pre_tax_amount", nullable=true, type="string")
     */
    private $billPreTaxAmount;

    /**
     * @ORM\Column(name="billing_company", nullable=true, type="string")
     */
    private $billingCompany;

    /**
     * @ORM\Column(name="folder_reference", nullable=true, type="string")
     */
    private $folderReference;

    /**
     * @ORM\Column(name="refot", nullable=true, type="string")
     */
    private $refot;

    /**
     * @ORM\Column(name="model_internaltional_consigment", nullable=true, type="string")
     */
    private $modelInternationalConsigment;

    /**
     * @ORM\Column(name="quotation_reference", nullable=true, type="string")
     */
    private $quotationReference;

    /**
     * @ORM\Column(name="recep", nullable=true, type="string")
     */
    private $recep;

    /**
     * @ORM\Column(name="principal", nullable=true, type="string")
     */
    private $principal;

    /**
     * @ORM\Column(name="contractor", nullable=true, type="string")
     */
    private $contractor;

    /**
     * @ORM\Column(name="n_principal", nullable=true, type="string")
     */
    private $nPrincipal;

    /**
     * @ORM\Column(name="input_operator", nullable=true, type="string")
     */
    private $inputOperator;

    /**
     * @ORM\Column(name="initial_seqc", nullable=true, type="string")
     */
    private $initialSeqc;

    /**
     * @ORM\Column(name="initial_region", nullable=true, type="string")
     */
    private $initialRegion;

    /**
     * @ORM\Column(name="initial_city", nullable=true, type="string")
     */
    private $initialCity;

    /**
     * @ORM\Column(name="initial_date", nullable=true, type="datetime")
     */
    private $initialDate;

    /**
     * @ORM\Column(name="initial_arriving_at", nullable=true, type="datetime")
     */
    private $initialArrivingAt;

    /**
     * @ORM\Column(name="initial_leaving_at", nullable=true, type="datetime")
     */
    private $initialLeavingAt;

    /**
     * @ORM\Column(name="final_seqc", nullable=true, type="string")
     */
    private $finalSeqc;

    /**
     * @ORM\Column(name="final_region", nullable=true, type="string")
     */
    private $finalRegion;

    /**
     * @ORM\Column(name="final_city", nullable=true, type="string")
     */
    private $finalCity;

    /**
     * @ORM\Column(name="final_date", nullable=true, type="datetime")
     */
    private $finalDate;

    /**
     * @ORM\Column(name="final_arriving_at", nullable=true, type="datetime")
     */
    private $finalArrivingAt;

    /**
     * @ORM\Column(name="final_leaving_at", nullable=true, type="datetime")
     */
    private $finalLeavingAt;

    /**
     * @ORM\Column(name="operation_seqc", nullable=true, type="string")
     */
    private $operationSeqc;

    /**
     * @ORM\Column(name="operation_region", nullable=true, type="string")
     */
    private $operationRegion;

    /**
     * @ORM\Column(name="operation_city", nullable=true, type="string")
     */
    private $operationCity;

    /**
     * @ORM\Column(name="operation_date", nullable=true, type="datetime")
     */
    private $operationDate;

    /**
     * @ORM\Column(name="operation_arriving_at", nullable=true, type="datetime")
     */
    private $operationArrivingAt;

    /**
     * @ORM\Column(name="operation_leaving_at", nullable=true, type="datetime")
     */
    private $operationLeavingAt;


    /**
     * @ORM\OneToOne(targetEntity="App\Entity\SupplyChainVisibility", cascade={"persist", "remove"})
     */
    private $supplyChainVisibility;

    /**
     * @ORM\Column(name="rdv", type="datetime", nullable=true)
     */
    private $rdv;


    /**
     * Orders constructor.
     */
    public function __construct()
    {
        $this->phases = new ArrayCollection();
        $this->products = new ArrayCollection();
        $this->files = new ArrayCollection();
        $this->supplyChainVisibility = new SupplyChainVisibility();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return OrderStatus
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return Client
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * @return Contact
     */
    public function getContact()
    {
        return $this->contact;
    }

    /**
     * @return ArrayCollection | OrderPhase[]
     */
    public function getPhases()
    {
        return $this->phases;
    }

    /**
     * @return string|null
     */
    public function getDeal()
    {
        return $this->deal;
    }

    /**
     * @return Checkpoint | null
     */
    public function getInitialCheckpoint()
    {
        return $this->initialCheckpoint;
    }

    /**
     * @return Checkpoint | null
     */
    public function getFinalCheckpoint()
    {
        return $this->finalCheckpoint;
    }

    /**
     * @return mixed
     */
    public function getAgency()
    {
        return $this->agency;
    }

    /**
     * @return OrderProduct[] | ArrayCollection
     */
    public function getProducts()
    {
        return $this->products;
    }

    /**
     * @param int $id
     * @return Orders
     */
    public function setId(int $id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @param OrderPhase $ordersPhase
     * @return bool
     */
    public function addPhase(OrderPhase $ordersPhase)
    {
        if($this->phases->contains($ordersPhase)){
            return false;
        }
        $this->phases->add($ordersPhase);
        $ordersPhase->setOrders($this);
        return true;
    }

    /**
     * @param OrderStatus $status
     * @return Orders
     */
    public function setStatus(OrderStatus $status=null)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @param Client $client
     * @return Orders
     */
    public function setClient(Client $client=null)
    {
        $this->client = $client;
        return $this;
    }

    /**
     * @param mixed $deal
     * @return self
     */
    public function setDeal($deal)
    {
        $this->deal = $deal;
        return $this;
    }

    /**
     * @param mixed $initialCheckpoint
     * @return Orders
     */
    public function setInitialCheckpoint($initialCheckpoint)
    {
        $this->initialCheckpoint = $initialCheckpoint;
        return $this;
    }

    /**
     * @param mixed $finalCheckpoint
     * @return Orders
     */
    public function setFinalCheckpoint($finalCheckpoint)
    {
        $this->finalCheckpoint = $finalCheckpoint;

        return $this;
    }

    /**
     * @param mixed $agency
     * @return Orders
     */
    public function setAgency($agency)
    {
        $this->agency = $agency;

        return $this;
    }

    /**
     * Clean and add all new Products
     * @param OrderProduct[] $products
     * @return Orders
     */
    public function setProducts( $products)
    {
        $this->products = $products;
        foreach ($products as $product)
        {
            $product->setOrders($this);
        }

        return $this;
    }

    public function __toString()
    {
        return 'Order n°' . strval($this->getId());
    }

    /**
     * @return mixed
     */
    public function getFiles()
    {
        return $this->files;
    }

    /**
     * @param mixed $file
     * @return bool
     */
    public function addFile(File $file)
    {
        if($this->files->contains($file)){
            return false;
        }
        $this->files->add($file);
        $file->setOrder($this);
        return true;
    }

    /**
     * @return mixed
     */
    public function getActivity()
    {
        return $this->activity;
    }

    /**
     * @param mixed $activity
     * @return Orders
     */
    public function setActivity($activity)
    {
        $this->activity = $activity;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getExploitation()
    {
        return $this->exploitation;
    }

    /**
     * @param mixed $exploitation
     * @return Orders
     */
    public function setExploitation($exploitation)
    {
        $this->exploitation = $exploitation;

        return $this;
    }/**
     * @return mixed
     */
    public function getClientOrderReference()
    {
        return $this->clientOrderReference;
    }/**
     * @param mixed $clientOrderReference
     * @return Orders
     */
    public function setClientOrderReference($clientOrderReference)
    {
        $this->clientOrderReference = $clientOrderReference;

        return $this;
    }/**
     * @return mixed
     */
    public function getClientOrderClient()
    {
        return $this->clientOrderClient;
    }/**
     * @param mixed $clientOrderClient
     * @return Orders
     */
    public function setClientOrderClient($clientOrderClient)
    {
        $this->clientOrderClient = $clientOrderClient;

        return $this;
    }/**
     * @return mixed
     */
    public function getBillNumber()
    {
        return $this->billNumber;
    }/**
     * @param mixed $billNumber
     * @return Orders
     */
    public function setBillNumber($billNumber)
    {
        $this->billNumber = $billNumber;

        return $this;
    }/**
     * @return mixed
     */
    public function getBillDate()
    {
        return $this->billDate;
    }/**
     * @param mixed $billDate
     * @return Orders
     */
    public function setBillDate($billDate)
    {
        $this->billDate = $billDate;

        return $this;
    }/**
     * @return mixed
     */
    public function getBillPreTaxAmount()
    {
        return $this->billPreTaxAmount;
    }/**
     * @param mixed $billPreTaxAmount
     * @return Orders
     */
    public function setBillPreTaxAmount($billPreTaxAmount)
    {
        $this->billPreTaxAmount = $billPreTaxAmount;

        return $this;
    }/**
     * @return mixed
     */
    public function getBillingCompany()
    {
        return $this->billingCompany;
    }/**
     * @param mixed $billingCompany
     * @return Orders
     */
    public function setBillingCompany($billingCompany)
    {
        $this->billingCompany = $billingCompany;

        return $this;
    }/**
     * @return mixed
     */
    public function getFolderReference()
    {
        return $this->folderReference;
    }/**
     * @param mixed $folderReference
     * @return Orders
     */
    public function setFolderReference($folderReference)
    {
        $this->folderReference = $folderReference;

        return $this;
    }/**
     * @return mixed
     */
    public function getRefot()
    {
        return $this->refot;
    }/**
     * @param mixed $refot
     * @return Orders
     */
    public function setRefot($refot)
    {
        $this->refot = $refot;

        return $this;
    }/**
     * @return mixed
     */
    public function getModelInternationalConsigment()
    {
        return $this->modelInternationalConsigment;
    }/**
     * @param mixed $modelInternationalConsigment
     * @return Orders
     */
    public function setModelInternationalConsigment($modelInternationalConsigment)
    {
        $this->modelInternationalConsigment = $modelInternationalConsigment;

        return $this;
    }/**
     * @return mixed
     */
    public function getQuotationReference()
    {
        return $this->quotationReference;
    }/**
     * @param mixed $quotationReference
     * @return Orders
     */
    public function setQuotationReference($quotationReference)
    {
        $this->quotationReference = $quotationReference;

        return $this;
    }/**
     * @return mixed
     */
    public function getRecep()
    {
        return $this->recep;
    }/**
     * @param mixed $recep
     * @return Orders
     */
    public function setRecep($recep)
    {
        $this->recep = $recep;

        return $this;
    }/**
     * @return mixed
     */
    public function getPrincipal()
    {
        return $this->principal;
    }/**
     * @param mixed $principal
     * @return Orders
     */
    public function setPrincipal($principal)
    {
        $this->principal = $principal;

        return $this;
    }/**
     * @return mixed
     */
    public function getContractor()
    {
        return $this->contractor;
    }/**
     * @param mixed $contractor
     * @return Orders
     */
    public function setContractor($contractor)
    {
        $this->contractor = $contractor;

        return $this;
    }/**
     * @return mixed
     */
    public function getNPrincipal()
    {
        return $this->nPrincipal;
    }/**
     * @param mixed $nPrincipal
     * @return Orders
     */
    public function setNPrincipal($nPrincipal)
    {
        $this->nPrincipal = $nPrincipal;

        return $this;
    }/**
     * @return mixed
     */
    public function getInputOperator()
    {
        return $this->inputOperator;
    }/**
     * @param mixed $inputOperator
     * @return Orders
     */
    public function setInputOperator($inputOperator)
    {
        $this->inputOperator = $inputOperator;

        return $this;
    }/**
     * @return mixed
     */
    public function getInitialSeqc()
    {
        return $this->initialSeqc;
    }/**
     * @param mixed $initialSeqc
     * @return Orders
     */
    public function setInitialSeqc($initialSeqc)
    {
        $this->initialSeqc = $initialSeqc;

        return $this;
    }/**
     * @return mixed
     */
    public function getInitialRegion()
    {
        return $this->initialRegion;
    }/**
     * @param mixed $initialRegion
     * @return Orders
     */
    public function setInitialRegion($initialRegion)
    {
        $this->initialRegion = $initialRegion;

        return $this;
    }/**
     * @return mixed
     */
    public function getInitialCity()
    {
        return $this->initialCity;
    }/**
     * @param mixed $initialCity
     * @return Orders
     */
    public function setInitialCity($initialCity)
    {
        $this->initialCity = $initialCity;

        return $this;
    }/**
     * @return mixed
     */
    public function getInitialDate()
    {
        return $this->initialDate;
    }/**
     * @param mixed $initialDate
     * @return Orders
     */
    public function setInitialDate($initialDate)
    {
        $this->initialDate = $initialDate;

        return $this;
    }/**
     * @return mixed
     */
    public function getInitialArrivingAt()
    {
        return $this->initialArrivingAt;
    }/**
     * @param mixed $initialArrivingAt
     * @return Orders
     */
    public function setInitialArrivingAt($initialArrivingAt)
    {
        $this->initialArrivingAt = $initialArrivingAt;

        return $this;
    }/**
     * @return mixed
     */
    public function getInitialLeavingAt()
    {
        return $this->initialLeavingAt;
    }/**
     * @param mixed $initialLeavingAt
     * @return Orders
     */
    public function setInitialLeavingAt($initialLeavingAt)
    {
        $this->initialLeavingAt = $initialLeavingAt;

        return $this;
    }/**
     * @return mixed
     */
    public function getFinalSeqc()
    {
        return $this->finalSeqc;
    }/**
     * @param mixed $finalSeqc
     * @return Orders
     */
    public function setFinalSeqc($finalSeqc)
    {
        $this->finalSeqc = $finalSeqc;

        return $this;
    }/**
     * @return mixed
     */
    public function getFinalRegion()
    {
        return $this->finalRegion;
    }/**
     * @param mixed $finalRegion
     * @return Orders
     */
    public function setFinalRegion($finalRegion)
    {
        $this->finalRegion = $finalRegion;

        return $this;
    }/**
     * @return mixed
     */
    public function getFinalCity()
    {
        return $this->finalCity;
    }/**
     * @param mixed $finalCity
     * @return Orders
     */
    public function setFinalCity($finalCity)
    {
        $this->finalCity = $finalCity;

        return $this;
    }/**
     * @return mixed
     */
    public function getFinalDate()
    {
        return $this->finalDate;
    }/**
     * @param mixed $finalDate
     * @return Orders
     */
    public function setFinalDate($finalDate)
    {
        $this->finalDate = $finalDate;

        return $this;
    }/**
     * @return mixed
     */
    public function getFinalArrivingAt()
    {
        return $this->finalArrivingAt;
    }/**
     * @param mixed $finalArrivingAt
     * @return Orders
     */
    public function setFinalArrivingAt($finalArrivingAt)
    {
        $this->finalArrivingAt = $finalArrivingAt;

        return $this;
    }/**
     * @return mixed
     */
    public function getFinalLeavingAt()
    {
        return $this->finalLeavingAt;
    }/**
     * @param mixed $finalLeavingAt
     * @return Orders
     */
    public function setFinalLeavingAt($finalLeavingAt)
    {
        $this->finalLeavingAt = $finalLeavingAt;

        return $this;
    }

    /**
     * @return SupplyChainVisibility
     */
    public function getSupplyChainVisibility()
    {
        return $this->supplyChainVisibility;
    }

    /**
     * @param mixed $supplyChainVisibility
     * @return Orders
     */
    public function setSupplyChainVisibility($supplyChainVisibility)
    {
        $this->supplyChainVisibility = $supplyChainVisibility;

        return $this;
    }

    /**
     * @return Checkpoint|null
     */
    public function getOperationCheckpoint()
    {
        return $this->operationCheckpoint;
    }

    /**
     * @return ReferentielBenefit|null
     */
    public function getBenefit()
    {
        return $this->benefit;
    }

    /**
     * @param mixed $benefit
     * @return Orders
     */
    public function setBenefit($benefit)
    {
        $this->benefit = $benefit;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getOperationSeqc()
    {
        return $this->operationSeqc;
    }

    /**
     * @param mixed $operationSeqc
     * @return Orders
     */
    public function setOperationSeqc($operationSeqc)
    {
        $this->operationSeqc = $operationSeqc;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getOperationRegion()
    {
        return $this->operationRegion;
    }

    /**
     * @param mixed $operationRegion
     * @return Orders
     */
    public function setOperationRegion($operationRegion)
    {
        $this->operationRegion = $operationRegion;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getOperationCity()
    {
        return $this->operationCity;
    }

    /**
     * @param mixed $operationCity
     * @return Orders
     */
    public function setOperationCity($operationCity)
    {
        $this->operationCity = $operationCity;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getOperationDate()
    {
        return $this->operationDate;
    }

    /**
     * @param mixed $operationDate
     * @return Orders
     */
    public function setOperationDate($operationDate)
    {
        $this->operationDate = $operationDate;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getOperationArrivingAt()
    {
        return $this->operationArrivingAt;
    }

    /**
     * @param mixed $operationArrivingAt
     * @return Orders
     */
    public function setOperationArrivingAt($operationArrivingAt)
    {
        $this->operationArrivingAt = $operationArrivingAt;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getOperationLeavingAt()
    {
        return $this->operationLeavingAt;
    }

    /**
     * @param mixed $operationLeavingAt
     * @return Orders
     */
    public function setOperationLeavingAt($operationLeavingAt)
    {
        $this->operationLeavingAt = $operationLeavingAt;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getRdvAt()
    {
        return $this->rdv;
    }

    /**
     * @param \DateTime| null $rdv
     * @return Orders
     */
    public function setRdvAt($rdv)
    {
        $this->rdv = $rdv;

        return $this;
    }



    /**
     * Gets triggered every time on update
     * @ORM\PrePersist()
     */
    public function onPrePersist()
    {
        parent::onPrePersist();
        $this->upsertOperationData();
    }

    /**
     * Gets triggered every time on update
     * @ORM\PreUpdate
     */
    public function onPreUpdate()
    {
        parent::onPreUpdate();
        $this->upsertOperationData(true);
    }

    private function upsertOperationData($isUpdate = false)
    {
        if ($this->benefit && $this->benefit->getFm1Apt() === ReferentielBenefit::FM1_REPRISE) {
            $checkpoint = $this->getInitialCheckpoint();
            $this->setOperationArrivingAt($this->initialArrivingAt);
            $this->setOperationLeavingAt($this->initialLeavingAt);
            $this->setOperationCity($this->initialCity);
            $this->setOperationRegion($this->initialRegion);
            $this->setOperationDate($this->initialDate);
            $this->setOperationSeqc($this->initialSeqc);

        } else {
            $checkpoint = $this->getFinalCheckpoint();
            $this->setOperationArrivingAt($this->finalArrivingAt);
            $this->setOperationLeavingAt($this->finalLeavingAt);
            $this->setOperationCity($this->finalCity);
            $this->setOperationRegion($this->finalRegion);
            $this->setOperationDate($this->finalDate);
            $this->setOperationSeqc($this->finalSeqc);
        }
        if ($checkpoint) {
            if ($isUpdate) {
                $this->getOperationCheckpoint()->setAddress(clone $checkpoint->getAddress());
                $this->getOperationCheckpoint()->setContact(clone $checkpoint->getContact());
                $this->getOperationCheckpoint()->setContactRdv(clone $checkpoint->getContactRdv());
                $this->getOperationCheckpoint()->setLabel($checkpoint->getLabel());
            } else {
                $checkpoint = clone $checkpoint;
                $checkpoint->setAddress(clone $checkpoint->getAddress());
                $checkpoint->setContact(clone $checkpoint->getContact());
                $checkpoint->setContactRdv(clone $checkpoint->getContactRdv());
                $this->operationCheckpoint = $checkpoint;
            }
        }
    }
}
