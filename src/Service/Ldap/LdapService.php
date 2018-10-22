<?php
/**
 * Created by PhpStorm.
 * User: aprerot
 * Date: 25/07/2018
 * Time: 15:22
 */
namespace App\Service\Ldap;
use Symfony\Component\Security\Core\Exception\AuthenticationServiceException;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;

class LdapService
{
    const LDAP_ERRNO_BAD_CREDENTIAL = 49;
    private $host1;
    private $host2;
    private $dn;

    /**
     * LdapService constructor.
     * @param $host1
     * @param $host2
     * @param $dn
     */
    public function __construct( $host1, $host2, $dn)
    {
        $this->host1 = $host1;
        $this->host2 = $host2;
        $this->dn = $dn;
    }

    /**
     * @param $username
     * @param $password
     * @return LdapResource
     */
    public function checkAuthentication($username, $password)
    {
        if($username != "" && $password != ""){
            try{
                // Check Authentication en First Server
                return $this->bind($this->host1, $username, $password);
            }
            catch(BadCredentialsException $e){  throw $e; }
            catch (\Exception $e){
                // Technical Probem, so we check Authentication en Second Server
                return $this->bind($this->host2, $username, $password);
            }
        }
        throw new AuthenticationServiceException("Empty Username or Password. Anonymous LDAP connection is forbidden");
    }

    public function searchUser($login, $password, $usernameToSearch)
    {

    }

    /**
     * @param $host
     * @return resource
     */
    private function connect($host)
    {
        $conn =  ldap_connect($host);
        ldap_set_option($conn, LDAP_OPT_PROTOCOL_VERSION,   3);
        ldap_set_option($conn, LDAP_OPT_REFERRALS, 0);
        return $conn;
    }

    /**
     * @param $host
     * @param $username
     * @param $password
     * @return LdapResource
     */
    private function bind($host, $username, $password)
    {
        // We add @ for ignored warning from method ldap_bind() because we prefer treat them with Exceptions.
        // Test on the first Server
        $conn = $this->connect($host);
        $bind = @ldap_bind($conn, $username.'@'.$this->dn, $password);
        if($bind){
            $read = @ldap_search($conn, 'dc=domaine,dc=lan', "(samaccountname=".$username.")", ['mail', 'samaccountname','cn','sn','c','l','title','postalcode','telephonenumber','givenname','company','streetaddress','name'] );
            $data = @ldap_get_entries($conn, $read);
            return $this->buildLdapResource($data);
        }
        $this->treatLdapError($conn);
    }

    private function buildLdapResource($data)
    {
        $resource = new LdapResource();

        return $resource;
    }

    /**
     * @throws BadCredentialsException
     * @throws AuthenticationServiceException
     * @param $conn
     */
    private function treatLdapError($conn)
    {
        $errno = ldap_errno($conn);
        $msg = "Error return by Ldap: ".ldap_err2str($errno);
        Switch ($errno){
            case self::LDAP_ERRNO_BAD_CREDENTIAL:
                throw new BadCredentialsException($msg);
                break;
            default:
                throw new AuthenticationServiceException($msg);
                break;
        }
    }
}