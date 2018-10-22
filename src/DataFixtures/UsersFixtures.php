<?php

namespace App\DataFixtures;

use App\Entity\Agency;
use App\Entity\Users;
use App\Security\User\DefaultUser;
use App\Type\AuthenticationMethodType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;



class UsersFixtures extends Fixture implements DependentFixtureInterface
{
    public const ADMINGROUP_USER_REFERENCE = 'admingroup-user';
    public const USERGROUP_USER_REFERENCE = 'usergroup-user';

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        //STEP 1 --- create normal users
        for ($i = 0; $i < 10; $i++) {

            $user = new Users();
            $user->setCreatedAt(new \DateTime());
            $user->setEnabled(true);
            $user->setAdmin(false);
            $user->setLogin('temp' . $i);
            $user->setUsername('Username of '.$user->getLogin());

            $defaultUser = new DefaultUser($user);
            $encoded = $this->encoder->encodePassword($defaultUser, 'temp' . $i);
            $user->setPassword($encoded);

            $user->addClient($this->getReference(ClientFixtures::REF_PREFIX.rand(0,ClientFixtures::REF_MAX_NUMBER-1)));
            $user->addContact($this->getReference(ContactFixtures::REF_PREFIX.rand(0,ContactFixtures::REF_MAX_NUMBER-1)));

            $manager->persist($user);
            $this->addReference(self::USERGROUP_USER_REFERENCE . $i, $user);
        }

        //STEP 2 --- create admin users
        $this->addReference(self::ADMINGROUP_USER_REFERENCE, $user);

        $user = new Users();
        $user->setCreatedAt(new \DateTime());
        $user->setEnabled(true);
        $user->setAdmin(true);
        $user->setLogin('admin');
        $user->setUsername('Username of '.$user->getLogin());

        $defaultUser = new DefaultUser($user);
        $encoded = $this->encoder->encodePassword($defaultUser, 'admin');
        $user->setPassword($encoded);

        $user->addClient($this->getReference(ClientFixtures::REF_PREFIX.rand(0,ClientFixtures::REF_MAX_NUMBER-1)));
        $user->addContact($this->getReference(ContactFixtures::REF_PREFIX.rand(0,ContactFixtures::REF_MAX_NUMBER-1)));

        $manager->persist($user);
        $manager->flush();

    }

    public function getDependencies()
    {
        return [
            ContactFixtures::class,
            ClientFixtures::class,
            AgencyFixtures::class
        ];

    }
}
