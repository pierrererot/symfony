<?php

namespace App\DataFixtures;

use App\Entity\Usergroup;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use App\DataFixtures\UsersFixtures;

class UsergroupFixtures extends Fixture implements DependentFixtureInterface
{
    public const FEATURE_USERGROUP_ADMIN_REFERENCE = 'feature-usergroup-admin';
    public const FEATURE_USERGROUP_USER_REFERENCE = 'feature-usergroup-user';

    public function load(ObjectManager $manager)
    {
        $userGroup = new Usergroup();
        $userGroup->setName('user group');
        $manager->persist($userGroup);

        $adminGroup = new Usergroup();
        $adminGroup->setName('admin group');
        $manager->persist($adminGroup);

        $otherGroup = new Usergroup();
        $otherGroup->setName('other group');
        $manager->persist($otherGroup);

        for ($i = 0; $i < 10; $i++) {
            $userGroup->addUser($this->getReference(UsersFixtures::USERGROUP_USER_REFERENCE . $i));
        }
        $adminGroup->addUser($this->getReference(UsersFixtures::ADMINGROUP_USER_REFERENCE));
        $this->addReference(self::FEATURE_USERGROUP_ADMIN_REFERENCE, $adminGroup);
        $this->addReference(self::FEATURE_USERGROUP_USER_REFERENCE, $userGroup);
        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            UsersFixtures::class,
        );
    }
}
