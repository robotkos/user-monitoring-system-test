<?php

namespace App\Tests\Controller;

use App\Entity\CompaniesVPN;
use App\Entity\UsersVPS;
use Faker\Factory;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;

class CompaniesControllerTest extends WebTestCase
{
    /**
     * @var Client
     */
    private $client;

    protected function setUp()
    {
        parent::setUp();
        $this->client = static::createClient();
    }

    public function testCompanyList()
    {

        $router = $this->client->getContainer()
            ->get('router');

//        $login = $router->generate(
//            'login',
//            [
//                '_username' => 'admin',
//                '_password' => 'q1w2e3'
//            ], [], [], [['headers' => ['Content-Type' => 'multipart/form-data']]]
//        );
//        $this->client->request('POST', $login);
//todo complete login

        $companies = $router->generate(
            'list.companies'
        );
        $this->client->request('GET', $companies);
        $data = json_decode(json_decode($this->client->getInternalResponse()->getContent()), true);
        foreach ($data as $item) {
            $this->assertArrayHasKey('id', $item);
            $this->assertArrayHasKey('name', $item);
            $this->assertArrayHasKey('quota', $item);

            $this->assertIsString($item['name']);
        }
    }

    public function testCompanyAdd()
    {
        $faker = new Factory();
        $router = $this->client->getContainer()
            ->get('router');
//todo complete login

        $companies = $router->generate(
            'add.companies',
            [
                'name' => $faker->create()->company,
                'quota' => rand(1,30),
            ]
        );
        $this->client->request('POST', $companies);
        $data = json_decode($this->client->getInternalResponse()->getContent(), true);
        $this->assertEquals(['message' => 'Saved'], $data);
    }


    public function testCompanyEdit()
    {
        $faker = new Factory();
        $companiesIDs = $this->getRandomCompanyID();
        $router = $this->client->getContainer()
            ->get('router');
//todo complete login

        $companies = $router->generate(
            'edit.companies',
            [
                'id' => $companiesIDs[array_rand($companiesIDs)],
                'name' => $faker->create()->company,
                'quota' => rand(1,30),
            ]
        );
        $this->client->request('POST', $companies);
        $data = json_decode($this->client->getInternalResponse()->getContent(), true);
        $this->assertEquals(['message' => 'Saved'], $data);
    }

    public function testCompanyDelete()
    {
        $companiesIDs = $this->getRandomCompanyID();
        $router = $this->client->getContainer()
            ->get('router');
//todo complete login

        $companies = $router->generate(
            'delete.companies',
            [
                'id' => $companiesIDs[array_rand($companiesIDs)],
            ]
        );
        $this->client->request('DELETE', $companies);
        $data = json_decode($this->client->getInternalResponse()->getContent(), true);
        $this->assertEquals(['message' => 'Removed'], $data);
    }

    private function getRandomCompanyID(): array
    {
        $companies = $this->client
            ->getContainer()
            ->get('doctrine')
            ->getRepository(CompaniesVPN::class)
            ->findAll();
        $ids = [];
        foreach ($companies as $company) {
            $ids[] = $company->getId();
        }
        return $ids;

    }

    private function getRandomUserID(): array
    {
        $companies = $this->client
            ->getContainer()
            ->get('doctrine')
            ->getRepository(UsersVPS::class)
            ->findAll();
        $ids = [];
        foreach ($companies as $user) {
            $ids[] = $user->getId();
        }
        return $ids;

    }
}
