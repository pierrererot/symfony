<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class ReferentielActivity.
 * @ORM\Table(name="referentiel_activity")
 * @ORM\Entity(repositoryClass="App\Repository\ReferentielActivityRepository")
 */
class ReferentielActivity
{
    /**
     * The identifier of the referentiel_activity.
     *
     * @var int
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id = null;

    /**
     * The label of the referentiel_activity.
     *
     * @var string
     * @ORM\Column(type="string")
     */
    private $label;

    /**
     * The name of the database linked to the referentiel_activity.
     *
     * @var string
     * @ORM\Column(type="string")
     */
    private $databaseName;

    /**
     * The code of the referentiel_activity.
     *
     * @var string
     * @ORM\Column(type="string")
     */
    private $code;

    /**
     * The code of the referentiel_activity.
     *
     * @var string
     * @ORM\Column(type="string")
     */
    private $joncture = 'ORTR_ACTI';

    /**
     * The client code
     * @var Client[] | ArrayCollection
     * @ORM\ManyToMany(targetEntity="App\Entity\Client", mappedBy="referentielActivity")
     */
    private $clients;

    /**
     * @ORM\Column(name="fm0_apt", type="string", nullable=true)
     */
    private $fm0Apt;

    /**
     * ReferentielActivity constructor.
     */
    public function __construct()
    {
        $this->clients = new ArrayCollection();
    }

    /**
     * Get the id of the referentiel_activity.
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
        $client->addReferentielActivity($this);
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
     * @return ReferentielActivity
     */
    public function setFm0Apt($fm0Apt)
    {
        $this->fm0Apt = $fm0Apt;
        return $this;
    }
}
