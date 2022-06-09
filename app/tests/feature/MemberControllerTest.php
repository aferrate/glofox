<?php

namespace App\Tests\Feature;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MemberControllerTest extends WebTestCase
{
    public function testGetAllMembersNoRecords(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/api/v1/members');

        $response = $client->getResponse();

        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame("no members found", json_decode($response->getContent(), true)['message']);
    }

    public function testGetNonExistentMemberById(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/api/v1/member/id/999999');

        $response = $client->getResponse();

        $this->assertSame(400, $response->getStatusCode());
        $this->assertSame("no member found", json_decode($response->getContent(), true)['message']);
    }

    public function testUpdateNonExistentMember(): void
    {
        $client = static::createClient();
        $crawler = $client->request('PUT', '/api/v1/member/update/999999', [], [], ['CONTENT_TYPE' => 'application/json'], '{
            "name" : "membertotestmodify"
        }');

        $response = $client->getResponse();

        $this->assertSame(400, $client->getResponse()->getStatusCode());
        $this->assertSame("no member found", json_decode($response->getContent(), true)['message']);
    }

    public function testDeleteNonExistentMember(): void
    {
        $client = static::createClient();
        $crawler = $client->request('DELETE', '/api/v1/member/delete/999999');

        $response = $client->getResponse();

        $this->assertSame(400, $client->getResponse()->getStatusCode());
        $this->assertSame("no member found", json_decode($response->getContent(), true)['message']);
    }

    public function testAddMember(): void
    {
        $client = static::createClient();
        $crawler = $client->request('POST', '/api/v1/member/create', [], [], ['CONTENT_TYPE' => 'application/json'], '{
            "name" : "testmember"
        }');

        $response = $client->getResponse();

        $this->assertSame(201, $client->getResponse()->getStatusCode());
        $this->assertSame("member created!", json_decode($response->getContent(), true)['message']);
    }
}
