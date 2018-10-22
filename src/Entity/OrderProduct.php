<?php
/**
 * Created by PhpStorm.
 * User: aprerot
 * Date: 11/07/2018
 * Time: 08:47
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
* @ORM\Table(name="orders_product")
* @ORM\Entity
*/
class OrderProduct
{
    /**
     * @var int
     * @ORM\Column(name="id", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var $serialNumber
     * @ORM\Column(type="string",name="serial_number", nullable=true)
     */
    private $serialNumber;

    /**
     * @var $quantity
     * @ORM\Column(type="string",name="quantity", nullable=true)
     */
    private $quantity;

    /**
     * @var $designation
     * @ORM\Column(type="string",name="designation", nullable=true)
     */
    private $designation;

    /**
     * @var $designation
     * @ORM\Column(type="string",name="code", nullable=true)
     */
    private $code;

    /**
     * @var $designation
     * @ORM\Column(type="string",name="weight", nullable=true)
     */
    private $weight;

    /**
     * @var $designation
     * @ORM\Column(type="string",name="height", nullable=true)
     */
    private $height;

    /**
     * @var $designation
     * @ORM\Column(type="string",name="length", nullable=true)
     */
    private $length;

    /**
     * @var $designation
     * @ORM\Column(type="string",name="width", nullable=true)
     */
    private $width;

    /**
     * @var $designation
     * @ORM\Column(type="string",name="fm0", nullable=true)
     */
    private $fm0;

    /**
     * @var $designation
     * @ORM\Column(type="string",name="fm1", nullable=true)
     */
    private $fm1;

    /**
     * @var $designation
     * @ORM\Column(type="string",name="fm2", nullable=true)
     */
    private $fm2;

    /**
     * @var $orders
     * @ORM\ManyToOne(targetEntity="Orders", inversedBy="products", cascade={"persist"})
     */
    private $orders;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return OrderProduct
     */
    public function setId(int $id): OrderProduct
    {
        $this->id = $id;
        return $this;
    }


    /**
     * @return Orders
     */
    public function getOrders()
    {
        return $this->orders;
    }

    /**
     * @param mixed $orders
     * @return self
     */
    public function setOrders($orders)
    {
        $this->orders = $orders;
        return $this;
    }


    /**
     * @return mixed
     */
    public function getSerialNumber()
    {
        return $this->serialNumber;
    }

    /**
     * @param mixed $serialNumber
     * @return OrderProduct
     */
    public function setSerialNumber($serialNumber)
    {
        $this->serialNumber = $serialNumber;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * @param mixed $quantity
     * @return OrderProduct
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDesignation()
    {
        return $this->designation;
    }

    /**
     * @param mixed $designation
     * @return OrderProduct
     */
    public function setDesignation($designation)
    {
        $this->designation = $designation;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param mixed $code
     * @return OrderProduct
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * @param mixed $weight
     * @return OrderProduct
     */
    public function setWeight($weight)
    {
        $this->weight = $weight;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * @param mixed $height
     * @return OrderProduct
     */
    public function setHeight($height)
    {
        $this->height = $height;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getLength()
    {
        return $this->length;
    }

    /**
     * @param mixed $length
     * @return OrderProduct
     */
    public function setLength($length)
    {
        $this->length = $length;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * @param mixed $width
     * @return OrderProduct
     */
    public function setWidth($width)
    {
        $this->width = $width;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getFm0()
    {
        return $this->fm0;
    }

    /**
     * @param mixed $fm0
     * @return OrderProduct
     */
    public function setFm0($fm0)
    {
        $this->fm0 = $fm0;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getFm1()
    {
        return $this->fm1;
    }

    /**
     * @param mixed $fm1
     * @return OrderProduct
     */
    public function setFm1($fm1)
    {
        $this->fm1 = $fm1;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getFm2()
    {
        return $this->fm2;
    }

    /**
     * @param mixed $fm2
     * @return OrderProduct
     */
    public function setFm2($fm2)
    {
        $this->fm2 = $fm2;

        return $this;
    }


}