<?php
/**
 * Created by PhpStorm.
 * User: aprerot
 * Date: 23/03/2018
 * Time: 15:31
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Checkpoint. Contains All checkpoint Contact Information and Address Information
 *
 * @author
 *
 * @ORM\Table(name="checkpoint")
 * @ORM\Entity
 */
class Checkpoint
{
    /**
     * @var int
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id = null;


    /**
     * The delivery Address of this receiver
     * @var Address
     * @ORM\ManyToOne(targetEntity="Address", cascade={"persist","remove"})
     */
    private $address;


    /**
     * The delivery Contact of this receiver
     * @var Contact
     * @ORM\ManyToOne(targetEntity="Contact", cascade={"persist","remove"} )
     */
    private $contact;

    /**
     * The Rdv Contact of this receiver
     * @var Contact
     * @ORM\ManyToOne(targetEntity="Contact", cascade={"persist","remove"} )
     */
    private $contactRdv;

    /**
     * @var string
     * @ORM\Column(type="string", name="label", nullable=true)
     */
    private $label;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return Address
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @return Contact
     */
    public function getContact()
    {
        return $this->contact;
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @param Address $address
     * @return Checkpoint
     */
    public function setAddress(Address $address)
    {
        $this->address = $address;
        return $this;
    }

    /**
     * @param Contact $contact
     * @return Checkpoint
     */
    public function setContact(Contact $contact)
    {
        $this->contact = $contact;
        return $this;
    }

    /**
     * @param string $label
     * @return Checkpoint
     */
    public function setLabel($label)
    {
        $this->label = $label;
        return $this;
    }

    /**
     * @return Contact
     */
    public function getContactRdv()
    {
        return $this->contactRdv;
    }

    /**
     * @param Contact $contactRdv
     * @return Checkpoint
     */
    public function setContactRdv(Contact $contactRdv): Checkpoint
    {
        $this->contactRdv = $contactRdv;

        return $this;
    }


}