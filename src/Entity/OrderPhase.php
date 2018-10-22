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
 * Class OrderStep.
 *
 * @author
 *
 * @ORM\Table(name="order_phase")
 * @ORM\Entity(repositoryClass="App\Repository\OrdersPhaseRepository")
 */
class OrderPhase extends AbstractTraceableEntity
{
    /**
     * @var int
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id = null;

    /**
     * @var Orders
     * @ORM\ManyToOne(targetEntity="App\Entity\Orders", inversedBy="phases")
     */
    private $orders;

    /**
     * @var string
     * @ORM\Column(name="code_voya", type="string", nullable=true)
     */
    private $codeVoya;

    /**
     * @var string
     * @ORM\Column(name="chartered", type="string", nullable=true)
     */
    private $chartered;

    /**
     * @var string
     * @ORM\Column(name="real_co2_quantity", type="string", nullable=true)
     */
    private $realCo2Quantity;

    /**
     * @var string
     * @ORM\Column(name="driver_one", type="string", nullable=true)
     */
    private $driverOne;

    /**
     * @var string
     * @ORM\Column(name="driver_two", type="string", nullable=true)
     */
    private $driverTwo;

    /**
     * @var string
     * @ORM\Column(name="started_at", type="datetime", nullable=true)
     */
    private $startedAt;

    /**
     * @var string
     * @ORM\Column(name="arrived_at", type="datetime", nullable=true)
     */
    private $arrivedAt;

    /**
     * @var string
     * @ORM\Column(name="day_started", type="string", nullable=true)
     */
    private $dayStarted;

    /**
     * @var string
     * @ORM\Column(name="day_arrived", type="string", nullable=true)
     */
    private $dayArrived;

    /**
     * @var string
     * @ORM\Column(name="hour_started", type="string", nullable=true)
     */
    private $hourStarted;

    /**
     * @var string
     * @ORM\Column(name="hour_arrived", type="string", nullable=true)
     */
    private $hourArrived;

    /**
     * @var string
     * @ORM\Column(name="km", type="string", nullable=true)
     */
    private $km;

    /**
     * @var string
     * @ORM\Column(name="km_empty_run", type="string", nullable=true)
     */
    private $kmEmptyRun;

    /**
     * @var string
     * @ORM\Column(name="km_empty_run_on_approached", type="string", nullable=true)
     */
    private $kmEmptyRunOnApproached;

    /**
     * @var string
     * @ORM\Column(name="mttraf", type="string", nullable=true)
     */
    private $mttraf;

    /**
     * @var string
     * @ORM\Column(name="trailer", type="string", nullable=true)
     */
    private $trailer;

    /**
     * @var string
     * @ORM\Column(name="truck", type="string", nullable=true)
     */
    private $truck;

    /**
     * PRST
     * @ORM\ManyToOne(targetEntity="App\Entity\ReferentielBenefit", inversedBy="orderPhases")
     */
    private $benefit;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return OrderPhase
     */
    public function setId(int $id): OrderPhase
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return Orders
     */
    public function getOrders(): Orders
    {
        return $this->orders;
    }

    /**
     * @param Orders $orders
     * @return OrderPhase
     */
    public function setOrders(Orders $orders): OrderPhase
    {
        $this->orders = $orders;

        return $this;
    }

    /**
     * @return string
     */
    public function getCodeVoya()
    {
        return $this->codeVoya;
    }

    /**
     * @param string $codeVoya
     */
    public function setCodeVoya($codeVoya): void
    {
        $this->codeVoya = $codeVoya;
    }

    /**
     * @return string
     */
    public function getChartered()
    {
        return $this->chartered;
    }

    /**
     * @param string $chartered
     */
    public function setChartered($chartered): void
    {
        $this->chartered = $chartered;
    }

    /**
     * @return string
     */
    public function getRealCo2Quantity()
    {
        return $this->realCo2Quantity;
    }

    /**
     * @param string $realCo2Quantity
     */
    public function setRealCo2Quantity($realCo2Quantity): void
    {
        $this->realCo2Quantity = $realCo2Quantity;
    }

    /**
     * @return string
     */
    public function getDriverOne()
    {
        return $this->driverOne;
    }

    /**
     * @param string $driverOne
     */
    public function setDriverOne($driverOne): void
    {
        $this->driverOne = $driverOne;
    }

    /**
     * @return string
     */
    public function getDriverTwo()
    {
        return $this->driverTwo;
    }

    /**
     * @param string $driverTwo
     */
    public function setDriverTwo($driverTwo): void
    {
        $this->driverTwo = $driverTwo;
    }

    /**
     * @return string
     */
    public function getStartedAt()
    {
        return $this->startedAt;
    }

    /**
     * @param string $startedAt
     */
    public function setStartedAt($startedAt): void
    {
        $this->startedAt = $startedAt;
    }

    /**
     * @return string
     */
    public function getArrivedAt()
    {
        return $this->arrivedAt;
    }

    /**
     * @param string $arrivedAt
     */
    public function setArrivedAt($arrivedAt): void
    {
        $this->arrivedAt = $arrivedAt;
    }

    /**
     * @return string
     */
    public function getDayStarted()
    {
        return $this->dayStarted;
    }

    /**
     * @param string $dayStarted
     */
    public function setDayStarted($dayStarted): void
    {
        $this->dayStarted = $dayStarted;
    }

    /**
     * @return string
     */
    public function getDayArrived()
    {
        return $this->dayArrived;
    }

    /**
     * @param string $dayArrived
     */
    public function setDayArrived($dayArrived): void
    {
        $this->dayArrived = $dayArrived;
    }

    /**
     * @return string
     */
    public function getHourStarted()
    {
        return $this->hourStarted;
    }

    /**
     * @param string $hourStarted
     */
    public function setHourStarted($hourStarted): void
    {
        $this->hourStarted = $hourStarted;
    }

    /**
     * @return string
     */
    public function getHourArrived()
    {
        return $this->hourArrived;
    }

    /**
     * @param string $hourArrived
     */
    public function setHourArrived($hourArrived): void
    {
        $this->hourArrived = $hourArrived;
    }

    /**
     * @return string
     */
    public function getKm()
    {
        return $this->km;
    }

    /**
     * @param string $km
     */
    public function setKm($km): void
    {
        $this->km = $km;
    }

    /**
     * @return string
     */
    public function getKmEmptyRun()
    {
        return $this->kmEmptyRun;
    }

    /**
     * @param string $kmEmptyRun
     */
    public function setKmEmptyRun($kmEmptyRun): void
    {
        $this->kmEmptyRun = $kmEmptyRun;
    }

    /**
     * @return string
     */
    public function getKmEmptyRunOnApproached()
    {
        return $this->kmEmptyRunOnApproached;
    }

    /**
     * @param string $kmEmptyRunOnApproached
     */
    public function setKmEmptyRunOnApproached($kmEmptyRunOnApproached): void
    {
        $this->kmEmptyRunOnApproached = $kmEmptyRunOnApproached;
    }

    /**
     * @return string
     */
    public function getMttraf()
    {
        return $this->mttraf;
    }

    /**
     * @param string $mttraf
     */
    public function setMttraf($mttraf): void
    {
        $this->mttraf = $mttraf;
    }

    /**
     * @return string
     */
    public function getTrailer()
    {
        return $this->trailer;
    }

    /**
     * @param string $trailer
     */
    public function setTrailer($trailer): void
    {
        $this->trailer = $trailer;
    }

    /**
     * @return string
     */
    public function getTruck()
    {
        return $this->truck;
    }

    /**
     * @param string $truck
     */
    public function setTruck($truck): void
    {
        $this->truck = $truck;
    }

    /**
     * @return mixed
     */
    public function getBenefit()
    {
        return $this->benefit;
    }

    /**
     * @param mixed $benefit
     * @return OrderPhase
     */
    public function setBenefit($benefit)
    {
        $this->benefit = $benefit;

        return $this;
    }


}