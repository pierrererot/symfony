<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Usergroup.
 *
 * @ORM\Table(name="usergroup")
 * @ORM\Entity
 */
class Usergroup
{
    /**
     * The identifier of the usergroup.
     *
     * @var int
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id = null;

    /**
     * The usergroup name.
     *
     * @var string
     * @ORM\Column(type="string")
     */
    protected $name;

    /**
     * User in the usergroup.
     *
     * @var Users[]
     * @ORM\ManyToMany(targetEntity="Users", mappedBy="usergroups")
     **/
    protected $users;

    /**
     * The usergroup parent.
     *
     * @var Usergroup
     * @ORM\ManyToOne(targetEntity="Usergroup")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", nullable=true)
     **/
    protected $parent;

    /**
     * List of features where the users are
     *
     * @var Feature[] | ArrayCollection
     * @ORM\ManyToMany(targetEntity="Feature", inversedBy="usergroups")
     * @ORM\JoinTable(name="usergroups_feature")
     */
    protected $features;


    public function __construct()
    {
        $this->users = new ArrayCollection();
    }

    /**
     * @return Feature[] | ArrayCollection
     */
    public function getFeatures(): array
    {
        return $this->features;
    }

    /**
     * @param Feature[] $features
     */
    public function setFeatures(array $features): void
    {
        $this->features = $features;
    }

    /**
     * @param $feature Feature
     */
    public function addFeature($feature)
    {
        if ($this->features->contains($feature)) {
            return;
        }

        $this->features->add($feature);
        $feature->addUsergroup($this);
    }

    /**
     * Remove a usergroup in the user association.
     * (Owning side).
     *
     * @param $feature Feature Remove the usergroup associated
     */
    public function removeFeature($feature)
    {
        if (!$this->features->contains($feature)) {
            return;
        }

        $this->features->removeElement($feature);
        $feature->removeUsergroup($this);
    }



    public function __toString()
    {
        return $this->getName();
    }

    /**
     * Get the id of the usergroup.
     * Return null if the usergroup is new and not saved.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the name of the usergroup.
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Get the name of the usergroup.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the parent usergroup.
     *
     * @param Usergroup $parent
     */
    public function setParent($parent)
    {
        $this->parent = $parent;
    }

    /**
     * Get the parent usergroup.
     *
     * @return Usergroup
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Return all users associated to the usergroup.
     *
     * @return Users[]
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * Set all users in the usergroup.
     *
     * @param Users[] $users
     */
    public function setUsers($users)
    {
        $this->users->clear();
        $this->users = new ArrayCollection($users);
    }

    /**
     * Add a user in the usergroup.
     *
     * @param $user Users The user to associate
     */
    public function addUser($user)
    {
        if ($this->users->contains($user)) {
            return;
        }

        $this->users->add($user);
        $user->addUserGroup($this);
    }

    /**
     * @param Users $user
     */
    public function removeUser($user)
    {
        if (!$this->users->contains($user)) {
            return;
        }

        $this->users->removeElement($user);
        $user->removeUsergroup($this);
    }
}
