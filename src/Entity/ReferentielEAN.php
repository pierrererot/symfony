<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;

/**
 * Class ReferentielEAN.
 * @ORM\Table(name="referentiel_ean")
 * @ORM\Entity(repositoryClass="App\Repository\ReferentielEANRepository")
 */
class ReferentielEAN
{
    /**
     * The identifier of the referentiel_ean.
     *
     * @var int
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id = null;

    /**
     * The label of the referentiel_ean.
     *
     * @var string
     * @ORM\Column(type="string")
     */
    private $label;

    /**
     * The name of the database linked to the referentiel_ean.
     *
     * @var string
     * @ORM\Column(type="string")
     */
    private $databaseName;

    /**
     * The code of the referentiel_ean.
     *
     * @var string
     * @ORM\Column(type="string")
     */
    private $code;

    /**
     * The code of the referentiel_ean.
     *
     * @var string
     * @ORM\Column(type="string")
     */
    private $joncture = "ORTR_EAN";

    /**
     * The client code
     * @var Client[]
     * @ORM\ManyToMany(targetEntity="App\Entity\Client", mappedBy="referentielEAN")
     */
    private $clients;

    /**
     * @ORM\Column(type="string",name="fm0_apt", nullable=true)
     */
    private $fm0Apt;

    /**
     * @ORM\Column(type="string",name="fm0_spt", nullable=true)
     */
    private $fm0Spt;

    /**
     * @ORM\Column(type="string",name="weight", nullable=true)
     */
    private $weight;

    /**
     * @ORM\Column(type="string",name="length", nullable=true)
     */
    private $length;

    /**
     * @ORM\Column(type="string",name="width", nullable=true)
     */
    private $width;

    /**
     * @ORM\Column(type="string",name="height", nullable=true)
     */
    private $height;

    public function __construct()
    {
        $this->clients = new ArrayCollection();
    }

    /**
     * Get the id of the referentiel_ean.
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
        $client->addReferentielEAN($this);
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
     * @return ReferentielEAN
     */
    public function setFm0Apt($fm0Apt)
    {
        $this->fm0Apt = $fm0Apt;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getFm0Spt()
    {
        return $this->fm0Spt;
    }

    /**
     * @param mixed $fm0Spt
     * @return ReferentielEAN
     */
    public function setFm0Spt($fm0Spt)
    {
        $this->fm0Spt = $fm0Spt;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * @param mixed $weight
     * @return ReferentielEAN
     */
    public function setWeight($weight)
    {
        $this->weight = $weight;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getLength()
    {
        return $this->length;
    }

    /**
     * @param mixed $length
     * @return ReferentielEAN
     */
    public function setLength($length)
    {
        $this->length = $length;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * @param mixed $width
     * @return ReferentielEAN
     */
    public function setWidth($width)
    {
        $this->width = $width;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * @param mixed $height
     * @return ReferentielEAN
     */
    public function setHeight($height)
    {
        $this->height = $height;

        return $this;
    }


}
