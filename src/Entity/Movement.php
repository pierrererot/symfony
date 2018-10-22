<?php
/**
 * Created by PhpStorm.
 * User: aprerot
 * Date: 06/06/2018
 * Time: 11:34
 */

namespace App\Entity;

use App\Annotation\ExternalUserFilterAnnotation;
use App\Annotation\InternalUserFilterAnnotation;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="movement")
 * @ORM\Entity(repositoryClass="App\Repository\Stock\StockMovementRepository")
 *
 * @InternalUserFilterAnnotation(targetFieldName="agency_id")
 * @ExternalUserFilterAnnotation(targetFieldName="client_id")
 */
class Movement
{
    /**
     * @var int
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id = null;

    /**
     * Movement type
     * CLI / NAV
     * @var string
     * @ORM\Column(type="string", nullable=true, name="travel_type", options={"comment":"TPE_TRA"})
     */
    protected $travelType;

    /**
     * @var string
     * @ORM\ManyToOne(targetEntity="App\Entity\Agency")
     */
    protected $agency;

    /**
     * @var string
     * @ORM\ManyToOne(targetEntity="App\Entity\Client")
     */
    protected $client;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true, name="origin", options={"comment":"ORIG"})
     */
    protected $origin;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true, name="destination", options={"comment":"DEST"})
     */
    protected $destination;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true, name="internal_order_reference", options={"comment":"CDE"})
     */
    protected $internalOrderReference;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true, name="external_order_reference", options={"comment":"REF_CDE"})
     */
    protected $externalOrderReference;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime", nullable=true, name="moved_at", options={"comment":"DATE"})
     */
    protected $movedAt;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true, name="movement_type", options={"comment":"TPE"})
     */
    protected $movementType;

    /**
     * @var integer
     * @ORM\Column(type="integer", nullable=true, name="target_quantity", options={"comment":"QTE_THEO"})
     */
    protected $targetQuantity;

    /**
     * @var integer
     * @ORM\Column(type="integer", nullable=true, name="actual_quantity", options={"comment":"QTE_REEL"})
     */
    protected $actualQuantity;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime", nullable=true, name="created_at",  options={"comment":"DT_APP"})
     */
    protected $createdAt;

    /**
     * @var string
     * @ORM\Column(type="text", nullable=true, name="items", options={"comment":"ARTICLE"})
     */
    protected $items;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true, name="movement", options={"comment":"MVT"})
     */
    protected $movement;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true, name="condition",  options={"comment":"ETAT"})
     */
    protected $condition;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true, name="deal", options={"comment":"Deal"})
     */
    protected $deal;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true, name="username", options={"comment":"USER"})
     */
    protected $username;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true, name="ref1", options={"comment":"USER"})
     */
    protected $ref1;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true, name="ref2", options={"comment":"USER"})
     */
    protected $ref2;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true, name="df", options={"comment":"USER"})
     */
    protected $df;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true, name="bill_type", options={"comment":"USER"})
     */
    protected $billType;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true, name="unitary_price", options={"comment":"USER"})
     */
    protected $unitaryPrice;


    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return Movement
     */
    public function setId(int $id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getTravelType()
    {
        return $this->travelType;
    }

    /**
     * @param string $travelType
     * @return Movement
     */
    public function setTravelType(string $travelType)
    {
        $this->travelType = $travelType;

        return $this;
    }

    /**
     * @return string
     */
    public function getAgency()
    {
        return $this->agency;
    }

    /**
     * @param string $depositCode
     * @return Movement
     */
    public function setAgency(Agency $agency)
    {
        $this->agency = $agency;
        return $this;
    }

    /**
     * @return string
     */
    public function getOrigin()
    {
        return $this->origin;
    }

    /**
     * @param string $origin
     * @return Movement
     */
    public function setOrigin(string $origin)
    {
        $this->origin = $origin;

        return $this;
    }

    /**
     * @return string
     */
    public function getDestination()
    {
        return $this->destination;
    }

    /**
     * @param string $destination
     * @return Movement
     */
    public function setDestination(string $destination)
    {
        $this->destination = $destination;

        return $this;
    }

    /**
     * @return string
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * @param string $clientCode
     * @return Movement
     */
    public function setClient(Client $client)
    {
        $this->client = $client;
        return $this;
    }

    /**
     * @return string
     */
    public function getInternalOrderReference()
    {
        return $this->internalOrderReference;
    }

    /**
     * @param string $internalOrderReference
     * @return Movement
     */
    public function setInternalOrderReference(string $internalOrderReference)
    {
        $this->internalOrderReference = $internalOrderReference;

        return $this;
    }

    /**
     * @return string
     */
    public function getExternalOrderReference()
    {
        return $this->externalOrderReference;
    }

    /**
     * @param string $externalOrderReference
     * @return Movement
     */
    public function setExternalOrderReference(string $externalOrderReference)
    {
        $this->externalOrderReference = $externalOrderReference;

        return $this;
    }

    /**
     * @return \DateTime | null
     */
    public function getMovedAt()
    {
        return $this->movedAt;
    }

    /**
     * @param \DateTime $movedAt
     * @return Movement
     */
    public function setMovedAt(\DateTime $movedAt)
    {
        $this->movedAt = $movedAt;

        return $this;
    }

    /**
     * @return string
     */
    public function getMovementType()
    {
        return $this->movementType;
    }

    /**
     * @param string $movementType
     * @return Movement
     */
    public function setMovementType($movementType)
    {
        $this->movementType = $movementType;

        return $this;
    }

    /**
     * @return integer
     */
    public function getTargetQuantity()
    {
        return $this->targetQuantity;
    }

    /**
     * @param integer $targetQuantity
     * @return Movement
     */
    public function setTargetQuantity(int $targetQuantity)
    {
        $this->targetQuantity = $targetQuantity;

        return $this;
    }

    /**
     * @return integer
     */
    public function getActualQuantity()
    {
        return $this->actualQuantity;
    }

    /**
     * @param integer $actualQuantity
     * @return Movement
     */
    public function setActualQuantity(int $actualQuantity)
    {
        $this->actualQuantity = $actualQuantity;

        return $this;
    }

    /**
     * @return \DateTime | null
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     * @return Movement
     */
    public function setCreatedAt(\DateTime $createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return string
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @param string $items
     * @return Movement
     */
    public function setItems(string $items)
    {
        $this->items = $items;

        return $this;
    }

    /**
     * @return string
     */
    public function getMovement()
    {
        return $this->movement;
    }

    /**
     * @param string $movement
     * @return Movement
     */
    public function setMovement(string $movement)
    {
        $this->movement = $movement;

        return $this;
    }

    /**
     * @return string
     */
    public function getCondition()
    {
        return $this->condition;
    }

    /**
     * @param string $condition
     * @return Movement
     */
    public function setCondition(string $condition)
    {
        $this->condition = $condition;

        return $this;
    }

    /**
     * @return string
     */
    public function getDeal()
    {
        return $this->deal;
    }

    /**
     * @param string $deal
     * @return Movement
     */
    public function setDeal($deal)
    {
        $this->deal = $deal;

        return $this;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param string $username
     * @return Movement
     */
    public function setUsername(string $username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @return string
     */
    public function getRef1()
    {
        return $this->ref1;
    }

    /**
     * @param string $ref1
     * @return Movement
     */
    public function setRef1(string $ref1): Movement
    {
        $this->ref1 = $ref1;

        return $this;
    }

    /**
     * @return string
     */
    public function getRef2()
    {
        return $this->ref2;
    }

    /**
     * @param string $ref2
     * @return Movement
     */
    public function setRef2(string $ref2): Movement
    {
        $this->ref2 = $ref2;

        return $this;
    }

    /**
     * @return string
     */
    public function getDf()
    {
        return $this->df;
    }

    /**
     * @param string $df
     * @return Movement
     */
    public function setDf(string $df): Movement
    {
        $this->df = $df;

        return $this;
    }

    /**
     * @return string
     */
    public function getBillType()
    {
        return $this->billType;
    }

    /**
     * @param string $billType
     * @return Movement
     */
    public function setBillType(string $billType): Movement
    {
        $this->billType = $billType;

        return $this;
    }

    /**
     * @return string
     */
    public function getUnitaryPrice()
    {
        return $this->unitaryPrice;
    }

    /**
     * @param string $unitaryPrice
     * @return Movement
     */
    public function setUnitaryPrice(string $unitaryPrice): Movement
    {
        $this->unitaryPrice = $unitaryPrice;

        return $this;
    }

}