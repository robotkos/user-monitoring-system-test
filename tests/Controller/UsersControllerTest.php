<?php

namespace App\Tests\Controller;

use App\Entity\CompaniesVPN;
use App\Entity\UsersVPS;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;

class UsersControllerTest extends WebTestCase
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

    public function testUserList()
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

        $users = $router->generate(
            'list.users'
        );
        $this->client->request('GET', $users);
        $data = json_decode(json_decode($this->client->getInternalResponse()->getContent()), true);
        foreach ($data as $item) {
            $this->assertArrayHasKey('id', $item);
            $this->assertArrayHasKey('name', $item);
            $this->assertArrayHasKey('email', $item);
            $this->assertArrayHasKey('companyId', $item);

            $this->assertContains('@', $item['email']);
            $this->assertIsString($item['name']);
        }
    }

    public function testUserAdd()
    {
        $companyIDs = $this->getRandomCompanyID();
        $router = $this->client->getContainer()
            ->get('router');
//todo complete login

        $users = $router->generate(
            'add.users',
            [
                'name' => 'Test Test',
                'email' => 'test@test.com',
                'company' => $companyIDs[array_rand($companyIDs)]
            ]
        );
        $this->client->request('POST', $users);
        $data = json_decode($this->client->getInternalResponse()->getContent(), true);
        $this->assertEquals(['message' => 'Saved'], $data);
    }


    public function testUserEdit()
    {
        $companyIDs = $this->getRandomCompanyID();
        $userIDs = $this->getRandomUserID();
        $router = $this->client->getContainer()
            ->get('router');
//todo complete login

        $users = $router->generate(
            'edit.users',
            [
                'id' => $userIDs[array_rand($userIDs)],
                'name' => 'Pablo Edited',
                'email' => 'fefwef@edited.com',
                'company' => $companyIDs[array_rand($companyIDs)]
            ]
        );
        $this->client->request('POST', $users);
        $data = json_decode($this->client->getInternalResponse()->getContent(), true);
        $this->assertEquals(['message' => 'Saved'], $data);
    }

    public function testUserDelete()
    {
        $userIDs = $this->getRandomUserID();
        $router = $this->client->getContainer()
            ->get('router');
//todo complete login

        $users = $router->generate(
            'delete.users',
            [
                'id' => $userIDs[array_rand($userIDs)],
            ]
        );
        $this->client->request('DELETE', $users);
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
        $users = $this->client
            ->getContainer()
            ->get('doctrine')
            ->getRepository(UsersVPS::class)
            ->findAll();
        $ids = [];
        foreach ($users as $user) {
            $ids[] = $user->getId();
        }
        return $ids;

    }
}
