<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\User;

class UserFixtures extends Fixture
{
	private $passwordEncoder;

	public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setEmail('admin@art.com');
        $password = $this->passwordEncoder->encodePassword($user, '123456');
        $user->setPassword($password);
        $user->setRoles(['ROLE_USER', 'ROLE_ADMIN']);
        $manager->persist($user);

        $user2 = new User();
        $user2->setEmail('user@art.com');
        $password = $this->passwordEncoder->encodePassword($user2, '123456');
        $user2->setPassword($password);
        $user2->setRoles(['ROLE_USER']);
        $manager->persist($user2);
        
        $manager->flush();
    }
}
