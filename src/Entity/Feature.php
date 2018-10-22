<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Usergroup.
 *
 * @author
 *
 * @ORM\Table(name="feature")
 * @ORM\Entity
 */
class Feature
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
     * The feature name.
     *
     * @var string
     * @ORM\Column(type="string")
     */
    protected $name;

    /**
     * The feature name.
     *
     * @var string
     * @ORM\Column(type="string")
     */
    protected $path;

    /**
     * Indicate if the user is enabled.
     *
     * @var bool
     * @ORM\Column(type="boolean")
     */
    private $enabled = false;

    /**
     * Usergroup in the feature.
     *
     * @var Usergroup[]
     * @ORM\ManyToMany(targetEntity="Usergroup", mappedBy="features")
     **/
    protected $usergroups;

    public function __construct()
    {
        $this->usergroups = new ArrayCollection();
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
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param string $path
     */
    public function setPath(string $path)
    {
        $this->path = $path;
    }

    /**
     * @return bool
     */
    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    /**
     * @param bool $enabled
     */
    public function setEnabled(bool $enabled): void
    {
        $this->enabled = $enabled;
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
     * Return all users associated to the usergroup.
     *
     * @return User[]
     */
    public function getUsergroups()
    {
        return $this->usergroups;
    }

    /**
     * Set all users in the usergroup.
     *
     * @param User[] $users
     */
    public function setUsergroups($usergroups)
    {
        $this->usergroups->clear();
        $this->usergroups = new ArrayCollection($usergroups);
    }

    /**
     * Add a usergroup in the feature.
     *
     * @param $usergroup Usergroup to associate
     */
    public function addUsergroup($usergroup)
    {
        if ($this->usergroups->contains($usergroup)) {
            return;
        }

        $this->usergroups->add($usergroup);
        $usergroup->addFeature($this);
    }

    /**
     * @param Usergroup $usergroup
     */
    public function removeUsergroup($usergroup)
    {
        if (!$this->usergroups->contains($usergroup)) {
            return;
        }

        $this->usergroups->removeElement($usergroup);
        $usergroup->removeFeature($this);
    }
}
