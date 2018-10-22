<?php
/**
 * Created by PhpStorm.
 * User: aprerot
 * Date: 31/07/2018
 * Time: 10:01
 */

namespace App\Service;

use App\Entity\Users;
use App\SqlFilter\SessionFilter;
use Doctrine\Common\Annotations\Reader;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class SessionFilterService
{
    private $em;
    private $token;
    private $reader;

    /**
     * SessionFilterService constructor.
     * @param EntityManager $em
     * @param TokenStorageInterface $token
     * @param Reader $reader
     */
    public function __construct(EntityManager $em, TokenStorageInterface $token, Reader $reader)
    {
        $this->em = $em;
        $this->token = $token;
        $this->reader = $reader;
    }

    /**
     * @return SessionFilter
     */
    public function getSessionFilter()
    {
        /** @var $user Users */
        $user = $this->token->getToken()->getUser();

        $sessionFilter = new SessionFilter($this->em);
        $sessionFilter->setParameter('isInternal', $user->isInternal());
        $sessionFilter->setParameter('values', json_encode($user->getSessionFilterValues()));
        $sessionFilter->setAnnotationReader($this->reader);

        return $sessionFilter;
    }
}