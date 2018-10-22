<?php
/**
 * Created by PhpStorm.
 * User: aprerot
 * Date: 06/09/2018
 * Time: 10:08
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
* @author
*
 * @ORM\Table(name="supply_chain_visibility_step")
* @ORM\Entity(repositoryClass="App\Repository\OrdersScvStepRepository")
*/

class ScvStep
{
    /**
     * @var int
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id = null;

    /**
     * @var SupplyChainVisibility
     * @ORM\ManyToOne(targetEntity="App\Entity\SupplyChainVisibility", inversedBy="steps")
     */
    private $supplyChainVisibility;

    /**
     * @var \DateTime
     * @ORM\Column(name="do_at",type="datetime", nullable=true)
     */
    private $doAt;

    /**
     * @var string
     * @ORM\Column(name="label", type="string", nullable=true)
     */
    private $label;

    /**
     * @var string
     * @ORM\Column(name="status_code", type="string", nullable=true)
     */
    private $statusCode;

    /**
     * @var string
     * @ORM\Column(name="status_label", type="string", nullable=true)
     */
    private $statusLabel;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return ScvStep
     */
    public function setId(int $id): ScvStep
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return SupplyChainVisibility
     */
    public function getSupplyChainVisibility()
    {
        return $this->supplyChainVisibility;
    }

    /**
     * @param SupplyChainVisibility $scv
     * @return ScvStep
     */
    public function setSupplyChainVisibility(SupplyChainVisibility $scv)
    {
        $this->supplyChainVisibility = $scv;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDoAt()
    {
        return $this->doAt;
    }

    /**
     * @param mixed $doAt
     * @return ScvStep
     */
    public function setDoAt( $doAt): ScvStep
    {
        $this->doAt = $doAt;

        return $this;
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @param string $label
     * @return ScvStep
     */
    public function setLabel(string $label): ScvStep
    {
        $this->label = $label;

        return $this;
    }

    /**
     * @return string
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * @param string $statusCode
     * @return ScvStep
     */
    public function setStatusCode(string $statusCode): ScvStep
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    /**
     * @return string
     */
    public function getStatusLabel()
    {
        return $this->statusLabel;
    }

    /**
     * @param string $statusLabel
     * @return ScvStep
     */
    public function setStatusLabel(string $statusLabel): ScvStep
    {
        $this->statusLabel = $statusLabel;

        return $this;
    }


}