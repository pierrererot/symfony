<?php

namespace App\DataFixtures;

use App\Entity\OrderComment;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;


class OrderCommentFixtures extends Fixture implements DependentFixtureInterface
{

    public function load(ObjectManager $manager)
    {
        $statusList = [
            'completed',
            'in_progress',
            'closed',
            'waiting'
        ];

        for ($i = 0; $i < 10; $i++) {
            $orderComment = new OrderComment();

            $orderComment->setStatus($statusList[$i % 4]);
            $orderComment->setContent("first comment");
            $orderComment->setUpdatedAt(new \DateTime());
            $orderComment->setUser($this->getReference(UsersFixtures::USERGROUP_USER_REFERENCE . rand(0, 9)));;
            $orderComment->setOrder($this->getReference(OrdersFixtures::COMMENT_ORDER_REFERENCE . $i));;
            $manager->persist($orderComment);
        }
        $manager->flush();

        for ($i = 0; $i < 10; $i++) {
            $orderComment = new OrderComment();

            $orderComment->setStatus($statusList[$i % 4]);
            $orderComment->setContent("second comment");
            $orderComment->setUpdatedAt(new \DateTime());
            $orderComment->setUser($this->getReference(UsersFixtures::USERGROUP_USER_REFERENCE . rand(0, 9)));;
            $orderComment->setOrder($this->getReference(OrdersFixtures::COMMENT_ORDER_REFERENCE . $i));;
            $manager->persist($orderComment);
        }
        $manager->flush();

    }

    /**
     * This method must return an array of fixtures classes
     * on which the implementing class depends on
     *
     * @return array
     */
    function getDependencies()
    {
        return [
            UsersFixtures::class,
            OrdersFixtures::class,
        ];
    }
}
