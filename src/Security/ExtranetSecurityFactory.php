<?php
/**
 * Created by PhpStorm.
 * User: aprerot
 * Date: 18/07/2018
 * Time: 17:12
 */

namespace App\Security;

use Symfony\Bundle\SecurityBundle\DependencyInjection\Security\Factory\FormLoginFactory;
use Symfony\Component\DependencyInjection\ChildDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class ExtranetSecurityFactory extends FormLoginFactory
{
    public function getKey()
    {
        return 'form-login-extranet';
    }

    protected function createAuthProvider(ContainerBuilder $container, $id, $config, $userProviderId)
    {
        $provider = 'app.security.'.$id;
        $container
            ->setDefinition($provider, new ChildDefinition(ExtranetAuthenticationProvider::class))
            ->replaceArgument(0, new Reference($userProviderId))
            ->replaceArgument(1, new Reference('security.user_checker.'.$id))
            ->replaceArgument(2, $id)
        ;
        return $provider;
    }
}