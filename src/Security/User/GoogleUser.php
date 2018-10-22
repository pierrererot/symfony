<?php
/**
 * Created by PhpStorm.
 * User: aprerot
 * Date: 08/08/2018
 * Time: 15:33
 */

namespace App\Security\User;

use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;

final class GoogleUser  extends AbstractUser
{
    /**
     * @var UserResponseInterface
     */
    private $googleFirstName;
    private $googleLastName;
    private $googleNickName;
    private $googleRealName;
    private $googleProfilePicture;
    private $googleUsername;
    private $googleEmail;

    public function setUserResponseInterface(UserResponseInterface $googleResponse)
    {
        $this->googleEmail = $googleResponse->getEmail();
        $this->googleFirstName = $googleResponse->getFirstName();
        $this->googleLastName = $googleResponse->getLastName();
        $this->googleNickName = $googleResponse->getNickname();
        $this->googleRealName = $googleResponse->getRealName();
        $this->googleFirstName = $googleResponse->getEmail();
        $this->googleProfilePicture = $googleResponse->getProfilePicture();
        $this->googleUsername = $googleResponse->getUsername();
    }

    public function getEmail()
    {
        return $this->googleEmail;
    }

    public function getFirstName()
    {
        return $this->googleFirstName;
    }

    public function getLastName()
    {
        return $this->googleLastName;
    }

    public function getNickname()
    {
        return $this->googleNickName;
    }

    public function getRealName()
    {
        return $this->googleRealName;
    }

    public function getProfilePicture()
    {
        return $this->googleProfilePicture;
    }

    public function getUsername()
    {
        return $this->googleUsername;
    }

    public function unserialize($serialized)
    {
        list(
            $parentSerialized,
            $this->googleUsername,
            $this->googleFirstName,
            $this->googleLastName,
            $this->googleEmail,
            $this->googleRealName,
            $this->googleNickName,
            $this->googleProfilePicture
        ) = unserialize($serialized);
        parent::unserialize($parentSerialized);
    }

    public function serialize()
    {
        return serialize( [
                parent::serialize(),
                $this->googleUsername,
                $this->googleFirstName,
                $this->googleLastName,
                $this->googleEmail,
                $this->googleRealName,
                $this->googleNickName,
                $this->googleProfilePicture
            ]
        );
    }
}