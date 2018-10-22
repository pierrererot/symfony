<?php
/**
 * Created by PhpStorm.
 * User: aprerot
 * Date: 08/08/2018
 * Time: 14:46
 */

namespace App\Security\User;

use App\Entity\Users;
use App\Type\AuthenticationMethodType;
use Doctrine\Common\Persistence\ManagerRegistry;
use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use HWI\Bundle\OAuthBundle\Security\Core\User\OAuthAwareUserProviderInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;


final class ExtranetUserProvider implements UserProviderInterface, OAuthAwareUserProviderInterface
{
    private $registry;
    private $managerName;
    private $classOrAlias;
    private $properties;

    /**
     * Duplicate parent construct because attributes are private instead of protected and parent class is on Vendor
     * UserProvider constructor.
     * @param ManagerRegistry $registry
     * @param string $classOrAlias
     * @param array $properties
     * @param string|null $managerName
     */
    public function __construct(ManagerRegistry $registry, string $classOrAlias, array $properties, string $managerName = null)
    {
        $this->registry = $registry;
        $this->managerName = $managerName;
        $this->classOrAlias = $classOrAlias;
        $this->properties = $properties;
    }

    /**
     * Load Oauth User  (First, for external User by google)
     * @param UserResponseInterface $response
     * @return GoogleUser|null|UserInterface
     */
    public function loadUserByOAuthUserResponse(UserResponseInterface $response)
    {
        $usersEntity = $this->findOneUserByUsernameAndAuthenticationMethods( $response->getUsername(), AuthenticationMethodType::AUTHENTICATED_BY_GOOGLE);
        if($usersEntity instanceof Users)
        {
            $user = new GoogleUser($usersEntity);
            $user->setUserResponseInterface($response);
            return $user;
        }
        return null;
    }

    /**
     * load External User with BDD password OR check Ldap for internal Users
     * @param $username
     * @return DefaultUser|LdapUser|null|object|UserInterface
     */
    public function loadUserByUsername($username)
    {
        $usersEntity = $this->findOneUserByUsernameAndAuthenticationMethods($username, [AuthenticationMethodType::AUTHENTICATED_BY_DEFAULT, AuthenticationMethodType::AUTHENTICATED_BY_LDAP]);
        if($usersEntity instanceof Users){
            $user = new DefaultUser($usersEntity);
            return $user;
        }
        return null;
    }

    /**
     * @param array $criteriaList
     * @return null|Users
     */
    private function findOneUserByCriteria( array $criteriaList)
    {
        $manager = $this->registry->getManager($this->managerName);
        $repository = $manager->getRepository($this->classOrAlias);

        return $repository->findOneBy($criteriaList);
    }

    /**
     * @param $username
     * @param $authenticationMethods
     * @return null|Users
     */
    private function findOneUserByUsernameAndAuthenticationMethods( $username, $authenticationMethods)
    {
        return $this->findOneUserByCriteria([
            $this->properties["login"] => $username,
//            $this->properties["auth_method"] => $authenticationMethods
        ]);
    }

    /**
     * @param $id
     * @return null|Users
     */
    private function findOneUserById($id)
    {
        return $this->findOneUserByCriteria(['id' => $id]);
    }
    /**
     * @brief Based on Symfony\Bridge\Doctrine\Security\User\EntityUserProvider
     * @param UserInterface $user
     * @return null|object|UserInterface
     */
    public function refreshUser(UserInterface $user)
    {
        if($user instanceof AbstractUser){
            $id = ($user->getUsersEntity()) ? $user->getUsersEntity()->getId() : $user->getId();
            $refreshedUsersEntity = $this->findOneUserById( $id);

            if (null === $refreshedUsersEntity) {
                throw new UsernameNotFoundException(sprintf('User with id %s not found', json_encode($id)));
            }

            $refreshedUser = $user->replaceUsersEntity($refreshedUsersEntity);
            return $refreshedUser;
        }
        throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
    }

    /**
     * Whether this provider supports the given user class.
     *
     * @param string $class
     *
     * @return bool
     */
    public function supportsClass($class)
    {
        return AbstractUser::class;
    }
}