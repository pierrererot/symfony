<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class OrderComment.
 * @ORM\Table(name="orders_comment")
 * @ORM\Entity(repositoryClass="App\Repository\OrderCommentRepository")
 */
class OrderComment
{
    /**
     * @var int
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id = null;

    /**
     * The creation date of the comment.
     *
     * @var \DateTime
     * @ORM\Column(type="datetime", name="created_at")
     */
    private $createdAt = null;

    /**
     * The creation date of the comment.
     *
     * @var \DateTime
     * @ORM\Column(type="datetime", name="updated_at")
     */
    private $updatedAt = null;

    /**
     * The status of the comment.
     *
     * @var string
     * @ORM\Column(type="string")
     */
    private $status;

    /**
     * The content of the comment.
     *
     * @var string
     * @ORM\Column(type="string")
     */
    private $content;

    /**
     * The user of this comment.
     * @var Users
     * @ORM\ManyToOne(targetEntity="Users")
     */
    private $user;

    /**
     * The order of this comment.
     * @var Orders
     * @ORM\ManyToOne(targetEntity="Orders")
     */
    private $order;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
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
    public function setCreatedAt(\DateTime $createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
    }

    /**
     * @param \DateTime $updatedAt
     */
    public function setUpdatedAt(\DateTime $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * Get the id of the comment.
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
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param string $status
     */
    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param string $content
     */
    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    /**
     * @return Users
     */
    public function getUser(): Users
    {
        return $this->user;
    }

    /**
     * @param Users $user
     */
    public function setUser(Users $user): void
    {
        $this->user = $user;
    }

    /**
     * @return Orders
     */
    public function getOrder(): Orders
    {
        return $this->order;
    }

    /**
     * @param Orders $order
     */
    public function setOrder(Orders $order): void
    {
        $this->order = $order;
    }
}
