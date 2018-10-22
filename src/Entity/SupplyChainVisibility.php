<?php
/**
 * Created by PhpStorm.
 * User: aprerot
 * Date: 06/09/2018
 * Time: 10:08
 */

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @author
 *
 * @ORM\Table(name="supply_chain_visibility")
 * @ORM\Entity(repositoryClass="App\Repository\OrdersScvRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class SupplyChainVisibility
{
    /**
     * @var int
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id = null;

    /**
     * @var ScvStep[] | ArrayCollection
     * @ORM\OneToMany( targetEntity="App\Entity\ScvStep", mappedBy="supplyChainVisibility", cascade={"persist","remove"})
     */
    private $steps;

    /**
     * @ORM\Column(name="updated_at", type="datetime")
     */
    private $updatedAt;

    /**
     * Gets triggered every time on update
     * @ORM\PrePersist()
     */
    public function onPrePersist()
    {
        $this->updatedAt = new \DateTime();
    }

    /**
     * Gets triggered every time on update
     * @ORM\PreUpdate
     */
    public function onPreUpdate()
    {
        $this->updatedAt = new \DateTime();
    }


    /**
     * SupplyChainVisibility constructor.
     */
    public function __construct()
    {
        $this->steps = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return SupplyChainVisibility
     */
    public function setId(int $id): SupplyChainVisibility
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return ScvStep[] | ArrayCollection
     */
    public function getSteps()
    {
        return $this->steps;
    }

    /**
     * @param ScvStep[] $steps
     * @return SupplyChainVisibility
     */
    public function setSteps(array $steps)
    {
        $this->steps = $steps;
        foreach ($steps as $step) {
            $step->setSupplyChainVisibility($this);
        }
        return $this;
    }



}