<?php
/**
 * Created by PhpStorm.
 * User: aprerot
 * Date: 08/08/2018
 * Time: 15:33
 */

namespace App\Security\User;


class DefaultUser extends AbstractUser
{
    public function getEmail()
    {
//        return $this->users->getEmail();
    }
}