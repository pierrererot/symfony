<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ParametersRepository")
 */
class Parameters
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="boolean")
     */
    private $communicationOMP;

    public function getId()
    {
        return $this->id;
    }

    public function getCommunicationOMP(): ?bool
    {
        return $this->communicationOMP;
    }

    public function setCommunicationOMP(bool $communicationOMP): self
    {
        $this->communicationOMP = $communicationOMP;
        return $this;
    }
}
