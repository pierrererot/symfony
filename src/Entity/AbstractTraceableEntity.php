<?php
/**
 * Created by PhpStorm.
 * User: aprerot
 * Date: 15/05/2018
 * Time: 17:01
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class AbstractTraceableEntity
 * @package App\Entity
 * @ORM\MappedSuperclass
 * @ORM\HasLifecycleCallbacks()
 */
abstract class AbstractTraceableEntity
{
    /**
     * @var string
     * @ORM\Column(type="string", name="source_reference")
     */
    protected $sourceReference;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime", name="created_at")
     */
    protected $createdAt;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime", name="updated_at", nullable=true)
     */
    protected $updatedAt;

    /**
     * @var string
     * @ORM\Column(type="string", name="created_by")
     */
    protected $createdBy;

    /**
     * @var string
     * @ORM\Column(type="string", name="updated_by", nullable=true)
     */
    protected $updatedBy;

    /**
     * @return string
     */
    public function getSourceReference()
    {
        return $this->sourceReference;
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
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @return string
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    /**
     * @return string
     */
    public function getUpdatedBy()
    {
        return $this->updatedBy;
    }

    /**
     * @param string $sourceReference
     * @return AbstractTraceableEntity
     */
    public function setSourceReference($sourceReference)
    {
        $this->sourceReference = $sourceReference;
        return $this;
    }

    /**
     * @param string $createdBy
     * @return AbstractTraceableEntity
     */
    public function setCreatedBy(string $createdBy): AbstractTraceableEntity
    {
        $this->createdBy = $createdBy;
        return $this;
    }

    /**
     * @param string $updatedBy
     * @return AbstractTraceableEntity
     */
    public function setUpdatedBy(string $updatedBy): AbstractTraceableEntity
    {
        $this->updatedBy = $updatedBy;
        return $this;
    }

    /**
     * Gets triggered every time on update
     * @ORM\PrePersist()
     */
    public function onPrePersist()
    {
        $this->createdAt = new \DateTime();
    }

    /**
     * Gets triggered every time on update
     * @ORM\PreUpdate
     */
    public function onPreUpdate()
    {
        $this->updatedAt = new \DateTime();
    }
}