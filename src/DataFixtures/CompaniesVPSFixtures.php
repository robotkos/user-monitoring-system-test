<?php

namespace App\DataFixtures;

use App\Entity\CompaniesVPN;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;

class CompaniesVPSFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $fake = new Factory();
        for ($i=0; $i < 10; $i++) {
            $company = new CompaniesVPN();
            $company->setName($fake->create()->company);
            $company->setQuota(rand(1,30));
            $manager->persist($company);
        }
        $manager->flush();
    }
}
