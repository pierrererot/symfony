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
 * Class status orders.
 *
 * @author
 *
 * @ORM\Table(name="orders_status")
 * @ORM\Entity(repositoryClass="App\Repository\OrderStatusRepository")
 */
class OrderStatus
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
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $label;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $weight;

    /**
     * @var float
     * @ORM\Column(name="progress_bar_value", type="float")
     */
    private $progressbarValue;

    /**
     * @var bool
     */
    private $isLitige;

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
     * @return float
     */
    public function getProgressBarValue(): float
    {
        return $this->progressbarValue;
    }

    /**
     * @param string $name
     * @return OrderStatus
     */
    public function setName(string $name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @param float $progressBarValue
     * @return OrderStatus
     */
    public function setProgressBarValue(float $progressBarValue): OrderStatus
    {
        $this->progressbarValue = $progressBarValue;
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
     * @return OrderStatus
     */
    public function setLabel( $label): OrderStatus
    {
        $this->label = $label;

        return $this;
    }

    /**
     * @return string
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * @param string $weight
     * @return OrderStatus
     */
    public function setWeight( $weight): OrderStatus
    {
        $this->weight = $weight;

        return $this;
    }

    /**
     * @return bool
     */
    public function isLitige(): bool
    {
        return $this->isLitige;
    }

    /**
     * @param bool $isLitige
     * @return OrderStatus
     */
    public function setIsLitige(bool $isLitige): OrderStatus
    {
        $this->isLitige = $isLitige;

        return $this;
    }



}