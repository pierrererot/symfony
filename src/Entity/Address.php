<?php
/**
 * Created by PhpStorm.
 * User: aprerot
 * Date: 23/03/2018
 * Time: 15:38
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Address.
 *
 * @author
 *
 * @ORM\Table(name="address")
 * @ORM\Entity
 */
class Address
{
    /**
     * @var int
     * @ORM\Column(name="id", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id = null;


    /**
     * The recipient
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    protected $recipient1;

    /**
     * The recipient
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    protected $recipient2;

    /**
     * The recipient
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    protected $recipient3;


    /**
     * The street name and street number
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    protected $street1;

    /**
     * The street name and street number
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    protected $street2;

    /**
     * The street name and street number
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    protected $street3;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    protected $postcode;

    /**
     * @var string
     * @ORM\Column(type="string",nullable=true)
     */
    protected $city;

    /**
     * @var string
     * @ORM\Column(type="string",nullable=true)
     */
    protected $country;

    /**
     * @ORM\Column(type="string",nullable=true)
     */
    protected $cedex;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getRecipient1()
    {
        return $this->recipient1;
    }

    /**
     * @return string
     */
    public function getRecipient2()
    {
        return $this->recipient2;
    }

    /**
     * @return string
     */
    public function getRecipient3()
    {
        return $this->recipient3;
    }

    /**
     * @return string
     */
    public function getStreet1()
    {
        return $this->street1;
    }

    /**
     * @return string
     */
    public function getStreet2()
    {
        return $this->street2;
    }

    /**
     * @return string
     */
    public function getStreet3()
    {
        return $this->street3;
    }

    /**
     * @return string
     */
    public function getPostcode()
    {
        return $this->postcode;
    }

    /**
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param string $glue
     * @return string
     */
    public function getFullRecipient($glue = " ")
    {
        return trim(implode($glue, [$this->recipient1, $this->recipient2, $this->recipient3]));
    }

    /**
     * @param string $glue
     * @return string
     */
    public function getFullStreet($glue = " ")
    {
        return trim(implode($glue, [$this->street1, $this->street2, $this->street3]));
    }

    /**
     * @param string $recipient1
     * @return Address
     */
    public function setRecipient1( $recipient1)
    {
        $this->recipient1 = $recipient1;
        return $this;
    }

    /**
     * @param string $recipient2
     * @return Address
     */
    public function setRecipient2( $recipient2)
    {
        $this->recipient2 = $recipient2;
        return $this;
    }

    /**
     * @param string $recipient3
     * @return Address
     */
    public function setRecipient3( $recipient3)
    {
        $this->recipient3 = $recipient3;
        return $this;
    }

    /**
     * @param string $street
     * @return Address
     */
    public function setStreet1( $street)
    {
        $this->street1 = $street;
        return $this;
    }

    /**
     * @param string $street2
     * @return Address
     */
    public function setStreet2( $street2)
    {
        $this->street2 = $street2;
        return $this;
    }

    /**
     * @param string $street3
     * @return Address
     */
    public function setStreet3( $street3)
    {
        $this->street3 = $street3;
        return $this;
    }

    /**
     * @param string $postcode
     * @return Address
     */
    public function setPostcode( $postcode)
    {
        $this->postcode = $postcode;
        return $this;
    }

    /**
     * @param string $city
     * @return Address
     */
    public function setCity( $city)
    {
        $this->city = $city;
        return $this;
    }

    /**
     * @param string $country
     * @return Address
     */
    public function setCountry( $country)
    {
        $this->country = $country;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCedex()
    {
        return $this->cedex;
    }

    /**
     * @param mixed $cedex
     * @return Address
     */
    public function setCedex($cedex)
    {
        $this->cedex = $cedex;

        return $this;
    }


    public function __toString() {
        return $this->getRecipient1() . ' ' . $this->getStreet1() . ' ' . $this->getCity();
    }
}