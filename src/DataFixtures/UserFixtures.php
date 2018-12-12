<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {

//    for ($i=0; $i < 10; $i++) {
        $user = new User();
        $user->setUsername('admin');
        $user->setPassword($this->encoder->encodePassword($user, 'q1w2e3'));
        $user->setRoles(['USER']);
        $user->setEmail('test@mail.com');
//    }
        $manager->persist($user);
        $manager->flush();
    }
}
