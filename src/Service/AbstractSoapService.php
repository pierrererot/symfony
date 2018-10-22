<?php
/**
 * Created by PhpStorm.
 * User: aprerot
 * Date: 07/06/2018
 * Time: 09:53
 */

namespace App\Service;


use App\Entity\Address;
use App\Entity\Checkpoint;
use App\Entity\Contact;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

abstract class AbstractSoapService
{
    /**
     * @var RegistryInterface
     */
    protected $doctrine;

    /**
     * @var SessionInterface
     */
    protected $session;

    /**
     * ClientSoapService constructor.
     * @param RegistryInterface $doctrine
     * @param SessionInterface $session
     */
    public function __construct( RegistryInterface $doctrine, SessionInterface $session)
    {
        $this->doctrine = $doctrine;
        $this->session = $session;
    }

    /**
     * @param $newCheckpoint \App\Service\SoapComplexType\Checkpoint
     * @return Checkpoint
     */
    protected function serializeCheckpoint($newCheckpoint)
    {

        $address = new Address();
        $address
            ->setRecipient1($newCheckpoint->recipient1)
            ->setRecipient2($newCheckpoint->recipient2)
            ->setRecipient3($newCheckpoint->recipient3)
            ->setStreet1($newCheckpoint->street1)
            ->setStreet2($newCheckpoint->street2)
            ->setStreet3($newCheckpoint->street3)
            ->setPostcode($newCheckpoint->postcode)
            ->setCity($newCheckpoint->city)
            ->setCountry($newCheckpoint->country)
            ->setCedex($newCheckpoint->cedex)
        ;

        $contact = new Contact();
        $contact
            ->setName($newCheckpoint->contactFullName)
            ->setPhoneNumber($newCheckpoint->contactPhoneNumber)
            ->setFaxNumber($newCheckpoint->contactFax)
            ->setEmail($newCheckpoint->contactEmail)
        ;

        $contactRdv = new Contact();
        $contactRdv
            ->setName($newCheckpoint->contactRdvFullName)
            ->setPhoneNumber($newCheckpoint->contactRdvPhoneNumber)
            ->setFaxNumber($newCheckpoint->contactRdvFax)
            ->setEmail($newCheckpoint->contactRdvEmail)
        ;

        $checkpointEntity = new Checkpoint();
        $checkpointEntity
            ->setLabel($newCheckpoint->label)
            ->setAddress($address)
            ->setContact($contact)
            ->setContactRdv($contactRdv)
        ;

        return $checkpointEntity;
    }

    /**
     * @param $inputDate
     * @param int $substringLength
     * @param string $format
     * @return \DateTime|null
     */
    protected function extractDate($inputDate, $substringLength = 14, $format = 'YmdHis') {
        $rdvDate = \DateTime::createFromFormat($format, substr($inputDate,0,$substringLength));
        return ($rdvDate) ?  $rdvDate : null;
    }
}