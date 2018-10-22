<?php
/**
 * Created by PhpStorm.
 * User: aprerot
 * Date: 31/05/2018
 * Time: 11:40
 */

namespace App\Tests\integration;

use App\Service\Ldap\LdapService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Security\Core\Exception\AuthenticationServiceException;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;

class LdapServiceTest extends KernelTestCase
{
    /**
     * @var LdapService
     */
    private $ldapService;

    protected function setUp()
    {
        $kernel = self::bootKernel();

        $this->ldapService =  $kernel->getContainer()
            ->get(LdapService::class);
    }

    /**
     * @expectedException Symfony\Component\Security\Core\Exception\BadCredentialsException
     * @expectedExceptionMessageRegExp %Error return by Ldap: Invalid credentials%
     */
    public function testReturnBadCredentialExceptionWithWrongCredentials()
    {
        $this->ldapService->checkAuthentication("wrongusername", "wrongpassword");
    }

    /**
     * @expectedException Symfony\Component\Security\Core\Exception\AuthenticationServiceException
     * @expectedExceptionMessageRegExp %Empty Username or Password. Anonymous LDAP connection is forbidden%
     */
    public function testReturnAuthenticationServiceExceptionWithAnonymousConnexion()
    {
        $this->ldapService->checkAuthentication("", "");
    }

//    /**
//     * If you want test with yours credentials ! Please, don't commit your credentials ;-)
//     */
//    public function testReturnTrueWithGoodCredentials()
//    {
//        $this->assertTrue($this->ldapService->checkAuthentication('***','***'));
//    }
}