<?php
/**
 * Created by PhpStorm.
 * User: aprerot
 * Date: 23/03/2018
 * Time: 15:31
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Class Contact.
 *
 * @author
 *
 * @ORM\Table(name="contact")
 * @ORM\Entity(repositoryClass="App\Repository\ContactRepository")
 *
 */
class Contact
{

    /**
     * @var int
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id = null;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    private $name;

    /**
     * @var Users
     * @ORM\ManyToMany(targetEntity="Users", mappedBy="contacts")
     */
    private $users;

    /**
     * @var string
     * @ORM\Column(type="string", name="phone_number", nullable=true)
     */
    private $phoneNumber;

    /**
     * @var string
     * @ORM\Column(type="string",name="fax_number", nullable=true)
     */
    private $faxNumber;

    /**
     * @var string
     * @ORM\Column(type="string",name="email", nullable=true)
     */
    private $email;

    public function __construct()
    {
        $this->users = new ArrayCollection();
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
     * @param Users $user
     * @return Bool
     */
    public function addUser(Users $user) : Bool
    {
        if($this->users->contains($user)) {
            return false;
        }
        $this->users->add($user);
        $user->addContact($this);
        return true;
    }

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
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Contact
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getPhoneNumber()
    {
        return $this->phoneNumber;
    }

    /**
     * @param string $phoneNumber
     * @return Contact
     */
    public function setPhoneNumber($phoneNumber)
    {
        $this->phoneNumber = $phoneNumber;
        return $this;
    }

    /**
     * @return string
     */
    public function getFaxNumber()
    {
        return $this->faxNumber;
    }

    /**
     * @param string $faxNumber
     * @return Contact
     */
    public function setFaxNumber($faxNumber): Contact
    {
        $this->faxNumber = $faxNumber;
        return $this;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return Contact
     */
    public function setEmail($email): Contact
    {
        $this->email = $email;
        return $this;
    }

    public function __toString()
    {
        if (strlen($this->getName())){
            return $this->getName();
        } else {
            return '';
        }
    }


}