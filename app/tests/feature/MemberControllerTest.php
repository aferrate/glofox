<?php

namespace App\Tests\Feature;

use App\Repository\MemberRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MemberControllerTest extends WebTestCase
{
    public function testGetAllMembersNoRecords(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/api/v1/members');

        $response = $client->getResponse();

        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame('no members found', json_decode($response->getContent(), true)['message']);
    }

    public function testGetNonExistentMemberById(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/api/v1/member/id/999999');

        $response = $client->getResponse();

        $this->assertSame(400, $response->getStatusCode());
        $this->assertSame('no member found', json_decode($response->getContent(), true)['message']);
    }

    public function testUpdateNonExistentMember(): void
    {
        $client = static::createClient();
        $crawler = $client->request('PUT', '/api/v1/member/update/999999', [], [], ['CONTENT_TYPE' => 'application/json'], '{
            "name" : "membertotestmodify"
        }');

        $response = $client->getResponse();

        $this->assertSame(400, $client->getResponse()->getStatusCode());
        $this->assertSame('no member found', json_decode($response->getContent(), true)['message']);
    }

    public function testDeleteNonExistentMember(): void
    {
        $client = static::createClient();
        $crawler = $client->request('DELETE', '/api/v1/member/delete/999999');

        $response = $client->getResponse();

        $this->assertSame(400, $client->getResponse()->getStatusCode());
        $this->assertSame('no member found', json_decode($response->getContent(), true)['message']);
    }

    public function testAddMember(): void
    {
        $client = static::createClient();
        $crawler = $client->request('POST', '/api/v1/member/create', [], [], ['CONTENT_TYPE' => 'application/json'], '{
            "name" : "testmember"
        }');

        $response = $client->getResponse();

        $this->assertSame(201, $client->getResponse()->getStatusCode());
        $this->assertSame('member created!', json_decode($response->getContent(), true)['message']);
    }

    public function testUpdateMember(): void
    {
        $client = static::createClient();
        $memberRepository = static::getContainer()->get(MemberRepository::class);

        $idMember = $memberRepository->findOneByName('testmember')->getId();

        $crawler = $client->request('PUT', "/api/v1/member/update/$idMember", [], [], ['CONTENT_TYPE' => 'application/json'], '{
            "name" : "testmemberupdate"
        }');

        $response = $client->getResponse();

        $this->assertSame(200, $client->getResponse()->getStatusCode());
        $this->assertSame('member updated!', json_decode($response->getContent(), true)['message']);
    }

    public function testDeleteMember(): void
    {
        $client = static::createClient();
        $memberRepository = static::getContainer()->get(MemberRepository::class);

        $idMember = $memberRepository->findOneByName('testmemberupdate')->getId();

        $crawler = $client->request('DELETE', "/api/v1/member/delete/$idMember");

        $response = $client->getResponse();

        $this->assertSame(200, $client->getResponse()->getStatusCode());
        $this->assertSame('member deleted', json_decode($response->getContent(), true)['message']);
    }
}
