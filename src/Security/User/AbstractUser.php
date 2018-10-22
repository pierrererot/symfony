<?php
/**
 * Created by PhpStorm.
 * User: aprerot
 * Date: 08/08/2018
 * Time: 15:33
 */
namespace App\Security\User;

use App\Entity\Agency;
use App\Entity\Client;
use App\Entity\Users;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\User\EquatableInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class AbstractUser
 * @package App\Security\User
 */
abstract class AbstractUser implements UserInterface, EquatableInterface, \Serializable
{
    const ROLE_ADMIN = 'ROLE_ADMIN';
    const ROLE_INTERNAL_USER = 'ROLE_INTERNAL_USER';
    const ROLE_DEFAULT_USER = 'ROLE_USER';

    /**
     * @var Users
     */
    protected $users;

    protected $id;


    /**
     * AbstractUser constructor.
     * @param Users $users
     */
    public function __construct(Users $users)
    {
        $this->users = $users;
        $this->id = $users->getId();
    }

    public function getId(){
        $this->id;
    }

    /**
     * @return Users
     */
    public function getUsersEntity()
    {
        return $this->users;
    }

    /**
     * @return array
     */
    public function getSessionFilterValues()
    {
        if($this->isInternal()){
            return array_merge(
                [0],
                array_map(
                    function (Agency $agency){
                        return $agency->getId();
                    },
                    $this->users->getAgencies()->toArray()
                )
            );
        }
        return array_merge(
            [0],
            array_map(
                function (Client $client){
                    return $client->getId();
                },
                $this->users->getClients()->toArray()
            )
        );
    }

    /**
     * @return array
     */
    public function getRoles()
    {
        if ($this->users->isAdmin()) {
            return [static::ROLE_ADMIN];
        }
        if($this->users instanceof LdapUser){
            return [static::ROLE_INTERNAL_USER];
        }
        return [static::ROLE_DEFAULT_USER];
    }

    /**
     * @return bool
     */
    public function isInternal()
    {
        return false;
    }

    /**
     * Returns the password used to authenticate the user.
     *
     * This should be the encoded password. On authentication, a plain-text
     * password will be salted, encoded, and then compared to this value.
     *
     * @return string The password
     */
    public function getPassword()
    {
        return $this->users->getPassword();
    }

    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * @return string|null The salt
     */
    public function getSalt()
    {
        return null;
    }

    /**
     * Returns the username used to authenticate the user.
     *
     * @return string The username
     */
    public function getUsername()
    {
        return $this->users->getLogin();
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials()
    {

    }

    public function __toString()
    {
        return $this->users->getUsername();
    }

    /**
     * {@inheritdoc}
     */
    public function isEqualTo(UserInterface $user)
    {
        if (!$user instanceof self) {
            return false;
        }

        if ($this->getPassword() !== $user->getPassword()) {
            return false;
        }

        if ($this->__toString() !== $user->__toString()) {
            return false;
        }

        if ($this->getSalt() !== $user->getSalt()) {
            return false;
        }

        if ($this->getUsername() !== $user->getUsername()) {
            return false;
        }

        if ($this->getRoles() !== $user->getRoles()) {
            return false;
        }

        return true;
    }

    public function unserialize($serialized)
    {
        list (
            $this->users,
            $this->id
        ) = unserialize($serialized);
    }

    public function serialize()
    {
        return serialize([$this->users, $this->id]);
    }

    /**
     * @param Users $users
     * @return $this
     */
    public function replaceUsersEntity(Users $users)
    {
        $this->users = $users;
        return $this;
    }

    /**
     * @return Agency[]|array|\Doctrine\Common\Collections\Collection
     */
    public function getAgencies()
    {
        if($this->isInternal()){
            return $this->users->getAgencies();
        }else{
            $agency = [];
            foreach ($this->users->getClients() as $client){
                $agency = array_merge($agency, $client->getAgencies()->toArray());
            }
            return $agency;
        }
    }

    /**
     * @return Client[]|ArrayCollection
     */
    public function getClients()
    {
        if($this->isInternal()) {
            $clients = [];
            foreach ($this->users->getAgencies() as $agency){
                $clients = array_merge($clients, $agency->getClients()->toArray());
            }
            return $clients;
        }else{
            return $this->users->getClients();
        }
    }
}