<?php
/**
 * Created by PhpStorm.
 * User: aprerot
 * Date: 23/03/2018
 * Time: 15:31
 */

namespace App\Entity;

use App\Annotation\ExternalUserFilterAnnotation;
use App\Annotation\InternalUserFilterAnnotation;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;


/**
 * Class Client.
 *
 * @author
 *
 * @ORM\Table(name="client")
 * @ORM\Entity(repositoryClass="App\Repository\ClientRepository")
 *
 * @ExternalUserFilterAnnotation(targetFieldName="id")
 */
class Client extends AbstractTraceableEntity implements \Serializable, \JsonSerializable
{
    const SOURCE_IDENTIFIER = 'sourceName';

    /**
     * @var int
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id = null;

    /**
     * @var Collection | Orders[]
     * @ORM\OneToMany(targetEntity="Orders", mappedBy="client", cascade={"persist"})
     */
    private $orders;

    /**
     * @var $checkpoint
     * @ORM\OneToOne(targetEntity="App\Entity\Checkpoint", cascade={"persist", "remove"})
     */
    private $checkpoint;

    /**
     * @var Users
     * @ORM\ManyToMany(targetEntity="Users", mappedBy="clients")
     */
    private $users;

    /**
     * @var Agency
     * @ORM\ManyToMany(targetEntity="Agency", inversedBy="clients")
     * @ORM\JoinTable(name="agencies_clients")
     */
    private $agencies;

    /**
     * @var Collection | ReferentielActivity[]
     * @ORM\ManyToMany(targetEntity="App\Entity\ReferentielActivity", inversedBy="clients", cascade={"persist"})
     */
    private $referentielActivity;

    /**
     * @var Collection | ReferentielBenefit[]
     * @ORM\ManyToMany(targetEntity="App\Entity\ReferentielBenefit", inversedBy="clients", cascade={"persist"})
     */
    private $referentielBenefit;

    /**
     * @var Collection | ReferentielEAN[]
     * @ORM\ManyToMany(targetEntity="App\Entity\ReferentielEAN", inversedBy="clients")
     */
    private $referentielEAN;

    /**
     * tva intra communautaire
     * @ORM\Column(type="string",name="intra_community_vat", nullable=true)
     */
    private $intraCommunityVat;

    /**
     * factureur
     * @ORM\Column(type="string", name="biller", nullable=true)
     */
    private $biller;

    /**
     * facturé
     * @ORM\Column(type="string", name="charged",nullable=true)
     */
    private $charged;

    /**
     * siret
     * @ORM\Column(type="string",name="siret",nullable=true)
     */
    private $siret;

    /**
     * siren
     * @var string
     * @ORM\Column(type="string",name="siren",nullable=true)
     */
    private $siren;

    /**
     * @var ArrayCollection | ReferentielExploitation[]
     * @ORM\ManyToMany(targetEntity="App\Entity\ReferentielExploitation", inversedBy="clients")
     */
    private $referentielExploitation;

    /**
     * Client constructor.
     */
    public function __construct()
    {
        $this->orders = new ArrayCollection();
        $this->referentielActivity = new ArrayCollection();
        $this->referentielBenefit = new ArrayCollection();
        $this->referentielEAN = new ArrayCollection();
        $this->referentielExploitation = new ArrayCollection();
        $this->users = new ArrayCollection();
        $this->agencies = new ArrayCollection();
    }

    /**
     * @return Users
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * @param Users[] $users
     * @return Client
     */
    public function setUsers(array $users): Client
    {
        $this->users = $users;
        return $this;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return Orders[]|ArrayCollection
     */
    public function getOrders()
    {
        return $this->orders;
    }

    /**
     * @param $orders Orders
     * @return bool
     */
    public function addOrder(Orders $orders)
    {
        if($this->orders->contains($orders)){
            return false;
        }
        $orders->setClient($this);
        $this->orders->add($orders);
        return true;
    }

    /**
     * @param Orders[] $orders
     */
    public function setOrders($orders)
    {
        $this->orders = $orders;
    }

    /**
     * @return ReferentielActivity[]|ArrayCollection
     */
    public function getReferentielActivity() {
        return $this->referentielActivity;
    }

    /**
     * @param $referentielActivity ReferentielActivity
     * @return bool
     */
    public function addReferentielActivity(ReferentielActivity $referentielActivity) {
        if($this->referentielActivity->contains($referentielActivity)){
            return false;
        }
        $this->referentielActivity->add($referentielActivity);
        $referentielActivity->addClient($this);
        return true;
    }

    /**
     * @param ReferentielActivity[] $referentielActivity
     */
    public function setReferentielActivity($referentielActivity) {
        $this->referentielActivity = $referentielActivity;
    }

    /**
     * @return ReferentielBenefit[]|ArrayCollection
     */
    public function getReferentielBenefit() {
        return $this->referentielBenefit;
    }

    /**
     * @param $referentielBenefit ReferentielBenefit
     * @return bool
     */
    public function addReferentielBenefit(ReferentielBenefit $referentielBenefit) {
        if($this->referentielBenefit->contains($referentielBenefit)){
            return false;
        }
        $this->referentielBenefit->add($referentielBenefit);
        $referentielBenefit->addClient($this);

        return true;
    }

    /**
     * @param ReferentielBenefit[] $referentielBenefit
     */
    public function setReferentielBenefit($referentielBenefit) {
        $this->referentielBenefit = $referentielBenefit;
    }

    /**
     * @return ReferentielEAN[]|ArrayCollection
     */
    public function getReferentielEAN() {
        return $this->referentielEAN;
    }

    /**
     * @param $referentielEAN ReferentielEAN
     * @return bool
     */
    public function addReferentielEAN(ReferentielEAN $referentielEAN) {
        if($this->referentielEAN->contains($referentielEAN)){
            return false;
        }
        $this->referentielEAN->add($referentielEAN);
        $referentielEAN->addClient($this);
        return true;
    }

    /**
     * @return ReferentielEAN[]|ArrayCollection
     */
    public function getReferentielExploitation() {
        return $this->referentielExploitation;
    }

    /**
     * @param $referentielExploitation ReferentielExploitation
     * @return bool
     */
    public function addReferentielExploitation(ReferentielExploitation $referentielExploitation) {
        if($this->referentielExploitation->contains($referentielExploitation)){
            return false;
        }
        $referentielExploitation->addClient($this);
        $this->referentielExploitation->add($referentielExploitation);
        return true;
    }

    /**
     * @param Users $user
     * @return Bool
     */
    public function addUser(Users $user) : Bool
    {
        if($this->users->contains($user)) {
            return false;
        }
        $this->users->add($user);
        $user->addClient($this);
        return true;
    }

    /**
     * @param Agency $agency
     * @return bool
     */
    public function addAgency(Agency $agency)
    {
        if($this->agencies->contains($agency)) {
            return false;
        }
        $this->agencies->add($agency);
        $agency->addClient($this);
        return true;
    }

    public function getAgencies()
    {
        return $this->agencies;
    }

    public function serialize()
    {
        return serialize([
            $this->id,
            $this->agencies
        ]);
    }

    public function unserialize($serialized)
    {
        List (
            $this->id,
            $this->agencies
            ) = unserialize($serialized);
    }


    public function __toString()
    {
        return $this->getSourceReference();
    }

    /**
     * @return Checkpoint
     */
    public function getCheckpoint()
    {
        return $this->checkpoint;
    }

    /**
     * @param mixed $checkpoint
     * @return Client
     */
    public function setCheckpoint($checkpoint)
    {
        $this->checkpoint = $checkpoint;

        return $this;
    }

    /**
     * @param $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getIntraCommunityVat()
    {
        return $this->intraCommunityVat;
    }

    /**
     * @param mixed $intraCommunityVat
     * @return Client
     */
    public function setIntraCommunityVat($intraCommunityVat)
    {
        $this->intraCommunityVat = $intraCommunityVat;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getBiller()
    {
        return $this->biller;
    }

    /**
     * @param mixed $biller
     * @return Client
     */
    public function setBiller($biller)
    {
        $this->biller = $biller;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCharged()
    {
        return $this->charged;
    }

    /**
     * @param mixed $charged
     * @return Client
     */
    public function setCharged($charged)
    {
        $this->charged = $charged;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getSiret()
    {
        return $this->siret;
    }

    /**
     * @param mixed $siret
     * @return Client
     */
    public function setSiret($siret)
    {
        $this->siret = $siret;

        return $this;
    }

    /**
     * @return string
     */
    public function getSiren()
    {
        return $this->siren;
    }

    /**
     * @param string $siren
     * @return Client
     */
    public function setSiren($siren): Client
    {
        $this->siren = $siren;

        return $this;
    }



    /**
     * Specify data which should be serialized to JSON
     * @link https://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {

        $address = ($this->getCheckpoint())?$this->getCheckpoint()->getAddress():new Address();
        return [
            "reference" => $this->getSourceReference(),
            "siret" => $this->getSiret(),
            "recipient1" => $address->getRecipient1(),
            "recipient2" => $address->getRecipient2(),
            "recipient3" => $address->getRecipient3(),
            "street1" => $address->getStreet1(),
            "street2" => $address->getStreet2(),
            "street3" => $address->getStreet3(),
            "postCode" => $address->getPostcode(),
            "city" => $address->getCity(),
            "country" => $address->getCountry(),
        ];
    }
}