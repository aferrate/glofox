<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ClassroomControllerTest extends WebTestCase
{
    public function testGetAllClassrooms(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/api/v1/classrooms');

        $this->assertResponseIsSuccessful();
    }

    public function testGetNonExistentClassroomById(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/api/v1/classroom/id/999999');

        $this->assertSame(400, $client->getResponse()->getStatusCode());
    }

    public function testAddClassroom(): void
    {
        $client = static::createClient();
        $crawler = $client->request('POST', '/api/v1/classroom/create', [], [], ['CONTENT_TYPE' => 'application/json'], '{
            "name" : "test",
            "capacity" : 7,
            "start_date" : "10-06-2022",
            "end_date" : "15-06-2022"
        }');

        $this->assertSame(201, $client->getResponse()->getStatusCode());
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

        $this->assertSame(400, $client->getResponse()->getStatusCode());
    }

    public function testDeleteNonExistentClassroom(): void
    {
        $client = static::createClient();
        $crawler = $client->request('DELETE', '/api/v1/classroom/delete/999999');

        $this->assertSame(400, $client->getResponse()->getStatusCode());
    }
}
