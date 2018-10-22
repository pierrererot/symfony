<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @UniqueEntity("email")
 * @ORM\Entity(repositoryClass="App\Repository\ProspectRepository")
 */
class Prospect
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $hash;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @ORM\Column(name="email", type="string", length=255, unique=true)
     * @Assert\Email(
     *     message = "Le format '{{ value }}' n'est pas un email valide",
     *     checkMX = true
     * )
     */
    private $email;

    public function getId()
    {
        return $this->id;
    }

    public function getHash()
    {
        return $this->hash;
    }

    public function setHash(string $hash)
    {
        $this->hash = $hash;

        return $this;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword(string $password)
    {
        $this->password = $password;

        return $this;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail(string $email)
    {
        $this->email = $email;

        return $this;
    }
}
