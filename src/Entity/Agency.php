<?php

namespace App\Entity;

use App\Annotation\InternalUserFilterAnnotation;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Agency.
 *
 * @author
 *
 * @ORM\Table(name="agency")
 * @ORM\Entity(repositoryClass="App\Repository\AgencyRepository")
 *
 * @InternalUserFilterAnnotation(targetFieldName="id")
  */
class Agency implements \Serializable
{
    const SOURCE_IDENTIFIER = 'code';

    /**
     * @var int
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id = null;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $code;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $database;

    /**
     * @var null|Checkpoint
     * @ORM\OneToOne(targetEntity="App\Entity\Checkpoint", cascade={"persist","remove"})
     */
    private $checkpoint;

    /**
     * The Trader linked to the agency
     * @var Collection | Trader[]
     * @ORM\ManyToMany(targetEntity="Trader")
     */
    private $trader;

    /**
     * The clients related to the agency
     *
     * @var Client[] | ArrayCollection
     * @ORM\ManyToMany(targetEntity="Client", mappedBy="agencies")
     */
    private $clients;

    /**
     * The client related to the agency
     * @var Users
     * @ORM\ManyToMany(targetEntity="App\Entity\Users", mappedBy="agencies")
     */
    private $users;

    /**
     * The clients related to the agency
     *
     * @var Agency[] | ArrayCollection
     * @ORM\ManyToMany(targetEntity="App\Entity\ReferentielExploitation", inversedBy="agencies")
     * @ORM\JoinTable(name="agencies_referentiel_exploitation")
     */
    private $referentielExploitation;

    /**
     * Agency constructor.
     */
    public function __construct()
    {
        $this->trader = new ArrayCollection();
        $this->users = new ArrayCollection();
        $this->clients = new ArrayCollection();
        $this->referentielExploitation = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return ArrayCollection | Contact[]
     */
    public function getTrader()
    {
        return $this->trader;
    }

    /**
     * @param Trader $trader
     * @return Bool
     */
    public function addTrader(Trader $trader): Bool
    {
        if( $this->trader->contains($trader)){
            return false;
        }
        return $this->trader->add($trader);
    }

    public function addClient(Client $client)
    {
        if( ($this->clients->contains($client))){
            return false;
        }
        $this->clients->add($client);
        $client->addAgency($this);
        return true;
    }

    /**
     * @return Client[]|ArrayCollection
     */
    public function getClients()
    {
        return $this->clients;
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
    public function setCode(string $code)
    {
        $this->code = $code;
    }

    /**
     * @return string
     */
    public function getDatabase()
    {
        return $this->database;
    }

    /**
     * @param string $database
     */
    public function setDatabase(string $database)
    {
        $this->database = $database;
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        if($this->checkpoint){
            return $this->checkpoint->getAddress()->getRecipient1();
        }else{
            return '';
        }
    }

    /**
     * @return ArrayCollection | Users[]
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * @param Users $users
     * @return bool
     */
    public function addUsers(Users $users)
    {
        if( $this->users->contains($users)){
            return false;
        }
        $this->users->add($users);
        $users->addAgency($this);
        return true;
    }


    /**
     * @return ArrayCollection | Users[]
     */
    public function getReferentielExploitation()
    {
        return $this->referentielExploitation;
    }

    /**
     * @param $referentielExploitation
     * @return bool
     */
    public function addReferentielExploitation(ReferentielExploitation $referentielExploitation)
    {
        if( $this->referentielExploitation->contains($referentielExploitation)){
            return false;
        }
        $this->referentielExploitation->add($referentielExploitation);
        $referentielExploitation->addAgencies($this);
        return true;
    }

    /**
     * @return Checkpoint|null
     */
    public function getCheckpoint(): ?Checkpoint
    {
        return $this->checkpoint;
    }

    /**
     * @param Checkpoint|null $checkpoint
     * @return Agency
     */
    public function setCheckpoint(?Checkpoint $checkpoint): Agency
    {
        $this->checkpoint = $checkpoint;
        return $this;
    }

    public function serialize()
    {
        return serialize([
            $this->id,
            $this->code,
            $this->clients
        ]);
    }

    public function unserialize($serialized)
    {
        List (
            $this->id,
            $this->code,
            $this->clients
        ) = unserialize($serialized);
    }

    public function __toString()
    {
        return $this->getLabel();
    }
}
