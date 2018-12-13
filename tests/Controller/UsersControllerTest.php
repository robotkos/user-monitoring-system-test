<?php

namespace App\Tests\Controller;

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
            $this->assertIsString( $item['name']);
        }
    }
}
