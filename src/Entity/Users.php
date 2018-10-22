<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Users.
 * @ORM\Table(name="users")
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class Users implements \Serializable
{
    /**
     * The identifier of the user.
     *
     * @var int
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id = null;

    /**
     * The creation date of the user.
     *
     * @var \DateTime
     * @ORM\Column(type="datetime", name="created_at")
     */
    private $createdAt = null;

    /**
     * Indicate if the user is an admin.
     *
     * @var bool
     * @ORM\Column(type="boolean")
     */
    private $admin = false;

    /**
     * Indicate if the user is enabled.
     *
     * @var bool
     * @ORM\Column(type="boolean")
     */
    private $enabled = false;

    /**
     * The username of the user.
     *
     * @var string
     * @ORM\Column(type="string")
     */
    private $username;

    /**
     * The login of the user.
     *
     * @var string
     * @ORM\Column(type="string")
     */
    private $login;

    /**
     * The password of the user.
     *
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    private $password;

     /**
     * List of usergroups where the users are
     * (Owning side).
     *
     * @var UserGroup[]
     * @ORM\ManyToMany(targetEntity="Usergroup", inversedBy="users")
     * @ORM\JoinTable(name="users_usergroup")
     */
    private $usergroups;

    /**
     * The clients related to the user
     *
     * @var Collection | Client[]
     * @ORM\ManyToMany(targetEntity="Client", inversedBy="users")
     * @ORM\JoinTable(name="clients_users")
     */
    private  $clients;

    /**
     * The contacts related to the user
     *
     * @var Collection | Contact[]
     * @ORM\ManyToMany(targetEntity="Contact", inversedBy="users")
     * @ORM\JoinTable(name="contacts_users")
     */
    private  $contacts;

    /**
     * @var ArrayCollection | Agency[]
     * @ORM\ManyToMany(targetEntity="Agency", inversedBy="users")
     * @ORM\JoinTable(name="agencies_users")
     */
    private $agencies;

    /**
     * Users constructor.
     */
    public function __construct()
    {
        $this->usergroups = new ArrayCollection();
        $this->createdAt = new \DateTime();
        $this->clients = new ArrayCollection();
        $this->contacts = new ArrayCollection();
        $this->agencies = new ArrayCollection();
    }

    /**
     * @return ArrayCollection | Client[]
     */
    public function getClients(): Collection
    {
        return $this->clients;
    }

    /**
     * @return ArrayCollection | Contact[]
     */
    public function getContacts(): Collection
    {
        return $this->contacts;
    }

    /**
     * Add a usergroup in the user association.
     * (Owning side).
     *
     * @param $usergroup Usergroup add the usergroup to associate
     */
    public function addUserGroup($usergroup)
    {
        if ($this->usergroups->contains($usergroup)) {
            return;
        }

        $this->usergroups->add($usergroup);
        $usergroup->addUser($this);
    }

    /**
     * Remove a usergroup in the user association.
     * (Owning side).
     *
     * @param $usergroup Usergroup Remove the usergroup associated
     */
    public function removeUsergroup($usergroup)
    {
        if (!$this->usergroups->contains($usergroup)) {
            return;
        }

        $this->usergroups->removeElement($usergroup);
        $usergroup->removeUser($this);
    }

    /**
     * Set if the user is enable.
     *
     * @param bool $enabled
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;
    }

    /**
     * Is the user enabled?
     *
     * @return bool
     */
    public function getEnabled()
    {
        return $this->enabled;
    }

    /**
     * Alias of getEnabled.
     *
     * @return bool
     */
    public function isEnabled()
    {
        return $this->getEnabled();
    }

    /**
     * Get all associated usergroups.
     *
     * @return Usergroup[]
     */
    public function getUsergroups()
    {
        return $this->usergroups;
    }

    /**
     * Set all usergroups of the user.
     *
     * @param Usergroup[] $usergroups
     */
    public function setUsergroups($usergroups)
    {
        // This is the owning side, we have to call remove and add to have change in the usergroup side too.
        foreach ($this->getCategories() as $usergroup) {
            $this->removeUsergroup($usergroup);
        }
        foreach ($usergroups as $usergroup) {
            $this->addUsergroup($usergroup);
        }
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @return \DateTime
     */
    public function setCreatedAt(\DateTime $createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * Get the id of the user.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->admin;
    }

    /**
     * @param bool $admin
     */
    public function setAdmin(bool $admin): void
    {
        $this->admin = $admin;
    }

    /** @see \Serializable::serialize() */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->username,
            $this->password,
            $this->login,
            $this->admin,
            $this->clients,
            $this->createdAt,
            $this->enabled,
            $this->usergroups,
            $this->agencies
        ));
    }

    /** @see \Serializable::unserialize() */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->username,
            $this->password,
            $this->login,
            $this->admin,
            $this->clients,
            $this->createdAt,
            $this->enabled,
            $this->usergroups,
            $this->agencies
            ) = unserialize($serialized);
        return $this;
    }

    /**
     * @param Client $client
     * @return Bool
     */
    public function addClient(Client $client) : Bool
    {
        if( $this->clients->contains($client)) {
            return false;
        }
        $this->clients->add($client);
        $client->addUser($this);
        return true;
    }

    /**
     * @param Contact $contact
     * @return Bool
     */
    public function addContact(Contact $contact) : Bool
    {
        if( $this->contacts->contains($contact)) {
            return false;
        }
        $this->contacts->add($contact);
        $contact->addUser($this);
        return true;
    }

    /**
     * @return string|null
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * @param string $login
     * @return Users
     */
    public function setLogin(string $login): Users
    {
        $this->login = $login;
        return $this;
    }

    /**
     * @param Agency $agency
     * @return bool
     */
    public function addAgency(Agency $agency)
    {
        if( $this->agencies->contains($agency)) {
            return false;
        }
        $this->agencies->add($agency);
        $agency->addUsers($this);
        return true;
    }

    /**
     * @return ArrayCollection | Agency[]
     */
    public function getAgencies()
    {
        return $this->agencies;
    }

    public function __toString()
    {
        return $this->getUsername();
    }
}
