<?php

namespace App\DataFixtures;

use App\Entity\CompaniesVPN;
use App\Entity\UsersVPS;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Faker\Factory;

class UsersVPSFixtures extends Fixture
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function load(ObjectManager $manager)
    {

        $companyIDs = $this->getRandomCompanyID();

        $fake = new Factory();
        for ($i = 0; $i < 10; $i++) {
            $users = new UsersVPS();
            $users->setName($fake->create()->name);
            $users->setEmail($fake->create()->email);
            $users->setCompanyId($companyIDs[array_rand($companyIDs)]);
            $manager->persist($users);
        }
        $manager->flush();
    }

    private function getRandomCompanyID(): array
    {
        $companies = $this->em->getRepository(CompaniesVPN::class)->findAll();
        $ids = [];
        foreach ($companies as $company) {
            $ids[] = $company->getId();
        }
        return $ids;

    }
}
