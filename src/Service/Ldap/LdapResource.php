<?php
namespace App\Service\Ldap;
/**
 * Created by PhpStorm.
 * User: aprerot
 * Date: 09/08/2018
 * Time: 10:48
 */
class LdapResource implements \Serializable
{
    private $login;

    private $firstName;

    private $lastName;

    private $fullName;

    private $phoneNumber;

    private $email;

    private $streetAddress;

    private $postalCode;

    private $city;

    private $country;

    /**
     * @return mixed
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * @param mixed $login
     * @return LdapResource
     */
    public function setLogin($login)
    {
        $this->login = $login;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param mixed $firstName
     * @return LdapResource
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param mixed $lastName
     * @return LdapResource
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFullName()
    {
        return $this->fullName;
    }

    /**
     * @param mixed $fullName
     * @return LdapResource
     */
    public function setFullName($fullName)
    {
        $this->fullName = $fullName;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPhoneNumber()
    {
        return $this->phoneNumber;
    }

    /**
     * @param mixed $phoneNumber
     * @return LdapResource
     */
    public function setPhoneNumber($phoneNumber)
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     * @return LdapResource
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getStreetAddress()
    {
        return $this->streetAddress;
    }

    /**
     * @param mixed $streetAddress
     * @return LdapResource
     */
    public function setStreetAddress($streetAddress)
    {
        $this->streetAddress = $streetAddress;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPostalCode()
    {
        return $this->postalCode;
    }

    /**
     * @param mixed $postalCode
     * @return LdapResource
     */
    public function setPostalCode($postalCode)
    {
        $this->postalCode = $postalCode;

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
     * @return LdapResource
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
     * @return LdapResource
     */
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }


    /**
     * String representation of object
     * @link http://php.net/manual/en/serializable.serialize.php
     * @return string the string representation of the object or null
     * @since 5.1.0
     */
    public function serialize()
    {
        return serialize([
            $this->login,
            $this->city,
            $this->country,
            $this->email,
            $this->firstName,
            $this->lastName,
            $this->fullName,
            $this->phoneNumber,
            $this->postalCode,
        ]);
    }

    /**
     * Constructs the object
     * @link http://php.net/manual/en/serializable.unserialize.php
     * @param string $serialized <p>
     * The string representation of the object.
     * </p>
     * @return void
     * @since 5.1.0
     */
    public function unserialize($serialized)
    {
        list (
            $this->login,
            $this->city,
            $this->country,
            $this->email,
            $this->firstName,
            $this->lastName,
            $this->fullName,
            $this->phoneNumber,
            $this->postalCode
        ) = unserialize($serialized);
    }
}