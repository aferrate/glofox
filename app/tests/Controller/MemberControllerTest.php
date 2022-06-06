<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MemberControllerTest extends WebTestCase
{
    public function testGetAllMembers(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/api/v1/members');

        $this->assertResponseIsSuccessful();
    }

    public function testGetNonExistentMemberById(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/api/v1/member/id/999999');

        $this->assertSame(400, $client->getResponse()->getStatusCode());
    }

    public function testAddMember(): void
    {
        $client = static::createClient();
        $crawler = $client->request('POST', '/api/v1/member/create', [], [], ['CONTENT_TYPE' => 'application/json'], '{
            "name" : "membertotest"
        }');

        $this->assertSame(201, $client->getResponse()->getStatusCode());
    }

    public function testUpdateNonExistentMember(): void
    {
        $client = static::createClient();
        $crawler = $client->request('PUT', '/api/v1/member/update/999999', [], [], ['CONTENT_TYPE' => 'application/json'], '{
            "name" : "membertotestmodify"
        }');

        $this->assertSame(400, $client->getResponse()->getStatusCode());
    }

    public function testDeleteNonExistentMember(): void
    {
        $client = static::createClient();
        $crawler = $client->request('DELETE', '/api/v1/member/delete/999999');

        $this->assertSame(400, $client->getResponse()->getStatusCode());
    }
}
