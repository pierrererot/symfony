<?php
/**
 * Created by PhpStorm.
 * User: aprerot
 * Date: 30/07/2018
 * Time: 09:35
 */

namespace App\EventListener;

use App\Entity\Users;
use App\Security\User\AbstractUser;
use App\SqlFilter\SessionFilter;
use Doctrine\Common\Annotations\Reader;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;



class BeforeRequestListener
{
    private $em;
    private $tokenStorage;
    private $reader;

    /**
     * BeforeRequestListener constructor.
     * @param EntityManager $em
     * @param TokenStorage $token
     * @param Reader $reader
     */
    public function __construct(EntityManager $em, TokenStorage $token, Reader $reader)
    {
        $this->em = $em;
        $this->tokenStorage = $token;
        $this->reader = $reader;
    }


    public function onKernelRequest(GetResponseEvent $event)
    {
        if($user = $this->getUser()){
            if( !in_array(AbstractUser::ROLE_ADMIN, $user->getRoles()))
            {
                /** @var $filter SessionFilter */
                $filter = $this->em->getFilters()->enable('session_filter');
                $filter->setParameter('isInternal', $user->isInternal());
                $filter->setParameter('values', json_encode($user->getSessionFilterValues()));
                $filter->setAnnotationReader($this->reader);
            }
        }
    }

    /**
     * @return null|AbstractUser
     */
    private function getUser()
    {
        $token = $this->tokenStorage->getToken();

        if (!$token) {
            return null;
        }

        $user = $token->getUser();

        if (!($user instanceof AbstractUser)) {
            return null;
        }

        return $user;
    }

}