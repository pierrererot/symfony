<?php
/**
 * Created by PhpStorm.
 * User: aprerot
 * Date: 18/07/2018
 * Time: 17:02
 */

namespace App\Security;

use App\Security\User\AbstractUser;
use App\Security\User\LdapUser;
use App\Service\Ldap\LdapService;
use Symfony\Component\Security\Core\Authentication\Provider\DaoAuthenticationProvider;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationServiceException;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class ExtranetAuthenticationProvider extends DaoAuthenticationProvider
{
    private $ldapService;

    public function __construct(
        UserProviderInterface $userProvider,
        UserCheckerInterface $userChecker,
        string $providerKey,
        EncoderFactoryInterface $encoderFactory,
        bool $hideUserNotFoundExceptions = true,
        LdapService $serviceLdap
    ) {
        $this->ldapService = $serviceLdap;
        parent::__construct($userProvider, $userChecker, $providerKey, $encoderFactory, $hideUserNotFoundExceptions);
    }

    /**
     * {@inheritdoc}
     */
    protected function checkAuthentication(UserInterface $user, UsernamePasswordToken $token)
    {
        $currentUser = $token->getUser();

        if($user instanceof AbstractUser) {
            if ($currentUser instanceof UserInterface) {
                if ($currentUser->getPassword() !== $user->getPassword()) {
                    throw new BadCredentialsException('The credentials were changed from another session.');
                }
            } else {
                if ('' === ($presentedPassword = $token->getCredentials())) {
                    throw new BadCredentialsException('The presented password cannot be empty.');
                }
                if ($user instanceof LdapUser) {
                    $resource = $this->ldapService->checkAuthentication($user->getUsername(), $token->getCredentials());
                    $user->setLdapResource($resource);

                } else {
                    // CHECK DATABASE for external users (client)
                    parent::checkAuthentication($user, $token);
                }
            }
        }else{
            throw new AuthenticationServiceException("Bad instantiated user entity");
        }
    }
}