<?php

namespace App\Tests\Feature;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ClassroomControllerTest extends WebTestCase
{
    public function testGetAllClassroomsNoRecords(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/api/v1/classrooms');

        $response = $client->getResponse();

        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame("no classrooms found", json_decode($response->getContent(), true)['message']);
    }

    public function testGetNonExistentClassroomById(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/api/v1/classroom/id/999999');

        $response = $client->getResponse();

        $this->assertSame(400, $response->getStatusCode());
        $this->assertSame("no classroom found", json_decode($response->getContent(), true)['message']);
    }

    public function testUpdateNonExistentClassroom(): void
    {
        $client = static::createClient();
        $crawler = $client->request('PUT', '/api/v1/classroom/update/999999', [], [], ['CONTENT_TYPE' => 'application/json'], '{
            "name" : "testtest",
            "capacity" : 2,
            "start_date" : "12-06-2022",
            "end_date" : "18-06-2022"
        }');

        $response = $client->getResponse();

        $this->assertSame(400, $client->getResponse()->getStatusCode());
        $this->assertSame("no classroom found", json_decode($response->getContent(), true)['message']);
    }

    public function testDeleteNonExistentClassroom(): void
    {
        $client = static::createClient();
        $crawler = $client->request('DELETE', '/api/v1/classroom/delete/999999');

        $response = $client->getResponse();

        $this->assertSame(400, $client->getResponse()->getStatusCode());
        $this->assertSame("no classroom found", json_decode($response->getContent(), true)['message']);
    }

    public function testAddClassroom(): void
    {
        $client = static::createClient();
        $crawler = $client->request('POST', '/api/v1/classroom/create', [], [], ['CONTENT_TYPE' => 'application/json'], '{
            "name" : "testclassroom",
            "capacity" : 7,
            "start_date" : "10-06-2023",
            "end_date" : "15-06-2023"
        }');

        $response = $client->getResponse();

        $this->assertSame(201, $client->getResponse()->getStatusCode());
        $this->assertSame("classroom created!", json_decode($response->getContent(), true)['message']);
    }
}
