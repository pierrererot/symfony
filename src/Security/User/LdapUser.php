<?php
/**
 * Created by PhpStorm.
 * User: aprerot
 * Date: 08/08/2018
 * Time: 15:33
 */

namespace App\Security\User;


use App\Service\Ldap\LdapResource;

class LdapUser  extends AbstractUser
{
    /**
     * @var LdapResource
     */
    private $ldapResource;

    public function setLdapResource(LdapResource $resource)
    {
        $this->ldapResource = $resource;
    }

    public function isInternal()
    {
        return true;
    }

    public function getEmail()
    {
        $this->ldapResource->getEmail();
    }

    public function getFirstName()
    {
        $this->ldapResource->getFirstName();
    }

    public function getLastName()
    {
        $this->ldapResource->getLastName();
    }

    public function getFullName()
    {
        $this->ldapResource->getFullName();
    }

    public function getLogin()
    {
        $this->ldapResource->getLogin();
    }

    public function getPhoneNumber()
    {
        $this->ldapResource->getPhoneNumber();
    }

    public function getStreetAddress()
    {
        $this->ldapResource->getStreetAddress();
    }

    public function getPostalCode()
    {
        $this->ldapResource->getPostalCode();
    }

    public function getCity()
    {
        $this->ldapResource->getCity();
    }

    public function getCountry()
    {
        $this->ldapResource->getCountry();
    }

    public function unserialize($serialized)
    {
        list(
            $parentSerialized,
            $this->ldapResource,
        ) = unserialize($serialized);
        parent::unserialize($parentSerialized);
    }

    public function serialize()
    {
        return serialize( [
                parent::serialize(),
                $this->ldapResource
            ]
        );
    }

}