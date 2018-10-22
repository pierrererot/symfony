<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;

/**
 * Class Trader.
 * @ORM\Table(name="trader")
 * @ORM\Entity(repositoryClass="App\Repository\TraderRepository")
 */
class Trader
{
    /**
     * The identifier of the trader.
     *
     * @var int
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id = null;

    /**
     * The label of the trader.
     *
     * @var string
     * @ORM\Column(type="string")
     */
    private $label;

    /**
     * The phone number of the trader.
     *
     * @var string
     * @ORM\Column(type="string")
     */
    private $phoneNumber;

    /**
     * The email of the trader.
     *
     * @var string
     * @ORM\Column(type="string")
     */
    private $email;

    /**
     * The fax number of the trader.
     *
     * @var string
     * @ORM\Column(type="string")
     */
    private $faxNumber;

    /**
     * The name of the database linked to the trader.
     *
     * @var string
     * @ORM\Column(type="string")
     */
    private $databaseName;

    /**
     * The code of the trader.
     *
     * @var string
     * @ORM\Column(type="string")
     */
    private $code;

    /**
     * The code of the trader.
     *
     * @var string
     * @ORM\Column(type="string")
     */
    private $joncture;

    public function __construct()
    {
    }

    /**
     * Get the id of the trader.
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
    public function getLabel(): string
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
    public function getDatabaseName(): string
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
    public function getCode(): string
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
    public function getJoncture(): string
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
     * @return string
     */
    public function getPhoneNumber(): string
    {
        return $this->phoneNumber;
    }

    /**
     * @param string $phoneNumber
     */
    public function setPhoneNumber(string $phoneNumber): void
    {
        $this->phoneNumber = $phoneNumber;
    }

    /**
     * @return string
     */
    public function getFaxNumber(): string
    {
        return $this->faxNumber;
    }

    /**
     * @param string $faxNumber
     */
    public function setFaxNumber(string $faxNumber): void
    {
        $this->faxNumber = $faxNumber;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

}
