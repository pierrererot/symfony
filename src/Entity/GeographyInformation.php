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
 *
 * @ORM\Table(name="geography_information")
 * @ORM\Entity
 */
class GeographyInformation
{
    /**
     * @var int
     * @ORM\Column(name="id", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id = null;

    /**
     * @var $postcode
     * @ORM\Column(type="string",name="postcode", nullable=false)
     */
    private $postcode;

    /**
     * @var $city
     * @ORM\Column(type="string",name="city", nullable=false)
     */
    private $city;

    /**
     * @var $country
     * @ORM\Column(type="string",name="country", nullable=false)
     */
    private $country;

    /**
     * Geographic key for OMP
     * @var $clecom
     * @ORM\Column(type="string",name="clecom", nullable=false)
     */
    private $clecom;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return GeographyInformation
     */
    public function setId(int $id): GeographyInformation
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPostcode()
    {
        return $this->postcode;
    }

    /**
     * @param mixed $postcode
     * @return GeographyInformation
     */
    public function setPostcode($postcode)
    {
        $this->postcode = $postcode;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param mixed $city
     * @return GeographyInformation
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param mixed $country
     * @return GeographyInformation
     */
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getClecom()
    {
        return $this->clecom;
    }

    /**
     * @param mixed $clecom
     * @return GeographyInformation
     */
    public function setClecom($clecom)
    {
        $this->clecom = $clecom;

        return $this;
    }



}