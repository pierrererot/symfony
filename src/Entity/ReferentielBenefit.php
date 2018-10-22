<?php

namespace App\Entity;

use App\Annotation\ExternalUserFilterAnnotation;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;


/**
 * Class ReferentielBenefit.
 * @ORM\Table(name="referentiel_benefit")
 * @ORM\Entity(repositoryClass="App\Repository\ReferentielBenefitRepository")
 *
 */
class ReferentielBenefit
{
    const FM1_REPRISE = 'REP';

    /**
     * The identifier of the referentiel_benefit. PRST Prestation
     *
     * @var int
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id = null;

    /**
     * The label of the referentiel_benefit.
     *
     * @var string
     * @ORM\Column(type="string")
     */
    private $label;

    /**
     * The name of the database linked to the referentiel_benefit.
     *
     * @var string
     * @ORM\Column(type="string")
     */
    private $databaseName;

    /**
     * The code of the referentiel_benefit.
     *
     * @var string
     * @ORM\Column(type="string")
     */
    private $code;

    /**
     * The code of the referentiel_benefit.
     *
     * @var string
     * @ORM\Column(type="string")
     */
    private $joncture = "PRST";

    /**
     * @ORM\Column(type="string",name="fm0_apt", nullable=true)
     */
    private $fm0Apt;

    /**
     * @ORM\Column(type="string",name="fm1_apt", nullable=true)
     */
    private $fm1Apt;

    /**
     * The client code
     * @var Client[] | ArrayCollection
     * @ORM\ManyToMany(targetEntity="App\Entity\Client", mappedBy="referentielBenefit")
     */
    private $clients;

    /**
     * @var OrderPhase[]
     * @ORM\OneToMany(targetEntity="App\Entity\OrderPhase", mappedBy="benefit")
     */
    private $orderPhases;

    /**
     * ReferentielBenefit constructor.
     */
    public function __construct()
    {
        $this->clients = new ArrayCollection();
        $this->orderPhases = new ArrayCollection();
    }

    /**
     * Get the id of the referentiel_benefit.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return $this->getLabel();
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @param string $label
     */
    public function setLabel(string $label): void
    {
        $this->label = $label;
    }

    /**
     * @return string
     */
    public function getDatabaseName()
    {
        return $this->databaseName;
    }

    /**
     * @param string $databaseName
     */
    public function setDatabaseName(string $databaseName): void
    {
        $this->databaseName = $databaseName;
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param string $code
     */
    public function setCode(string $code): void
    {
        $this->code = $code;
    }

    /**
     * @return string
     */
    public function getJoncture()
    {
        return $this->joncture;
    }

    /**
     * @param string $joncture
     */
    public function setJoncture(string $joncture): void
    {
        $this->joncture = $joncture;
    }

    /**
     * @return Client[]
     */
    public function getClients()
    {
        return $this->clients;
    }

    /**
     * @param Client $client
     * @return bool
     */
    public function addClient(Client $client)
    {
        if($this->clients->contains($client)) {
            return false;
        }
        $this->clients->add($client);
        $client->addReferentielBenefit($this);
        return true;
    }

    /**
     * @return mixed
     */
    public function getFm0Apt()
    {
        return $this->fm0Apt;
    }

    /**
     * @param mixed $fm0Apt
     * @return ReferentielBenefit
     */
    public function setFm0Apt($fm0Apt)
    {
        $this->fm0Apt = $fm0Apt;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getFm1Apt()
    {
        return $this->fm1Apt;
    }

    /**
     * @param mixed $fm1Apt
     * @return ReferentielBenefit
     */
    public function setFm1Apt($fm1Apt)
    {
        $this->fm1Apt = $fm1Apt;

        return $this;
    }

    /**
     * @return OrderPhase[]| ArrayCollection
     */
    public function getOrderPhases()
    {
        return $this->orderPhases;
    }

    /**
     * @param OrderPhase $orderPhase
     * @return bool
     */
    public function addOrderPhase(OrderPhase $orderPhase)
    {
        if( $this->orderPhases->contains($orderPhase)) {
            return false;
        }
        $this->orderPhases->add($orderPhase);
        $orderPhase->setBenefit($this);
        return true;
    }
}
