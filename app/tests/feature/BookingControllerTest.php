<?php

namespace App\Tests\Feature;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Repository\MemberRepository;
use App\Repository\ClassroomRepository;
use App\Domain\Model\Classroom;
use App\Domain\Model\Member;
use DateTime;

class BookingControllerTest extends WebTestCase
{
    private $idClass;
    private $idMember;
    private $idBooking;

    public function testGetAllBookings(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/api/v1/bookings');

        $response = $client->getResponse();

        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame("no bookings found", json_decode($response->getContent(), true)['message']);
    }

    public function testGetNonExistentBookingById(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/api/v1/booking/id/999999');

        $response = $client->getResponse();

        $this->assertSame(400, $response->getStatusCode());
        $this->assertSame("no booking found", json_decode($response->getContent(), true)['message']);
    }

    public function testUpdateNonExistentBooking(): void
    {
        $client = static::createClient();
        $crawler = $client->request('PUT', '/api/v1/booking/update/999999', [], [], ['CONTENT_TYPE' => 'application/json'], '{
            "idMember" : 1,
            "idClassroom" : 1,
            "date" : "14-07-2022"
        }');

        $response = $client->getResponse();

        $this->assertSame(400, $client->getResponse()->getStatusCode());
        $this->assertSame("no booking found", json_decode($response->getContent(), true)['message']);
    }

    public function testDeleteNonExistentBooking(): void
    {
        $client = static::createClient();
        $crawler = $client->request('DELETE', '/api/v1/booking/delete/999999');

        $response = $client->getResponse();

        $this->assertSame(400, $client->getResponse()->getStatusCode());
        $this->assertSame("no booking found", json_decode($response->getContent(), true)['message']);
    }

    public function testAddBooking(): void
    {
        $client = static::createClient();

        $memberRepository = static::getContainer()->get(MemberRepository::class);
        $classroomRepository = static::getContainer()->get(ClassroomRepository::class);

        $member = $memberRepository->findOneByName('testmember');
        $params = ['name' => 'testclassroom', 'start_date' => '10-06-2023', 'end_date' => '15-06-2023', 'capacity' => 7];
        $classroom = $classroomRepository->findOneByNameAndDatesAndCapacity($params);

        $this->idMember = $member->getId();
        $this->idClass = $classroom->getId();
        
        $crawler = $client->request('POST', '/api/v1/booking/create', [], [], ['CONTENT_TYPE' => 'application/json'], '{
            "idMember" : '.$this->idMember.',
            "idClassroom" : '.$this->idClass.',
            "date" : "14-07-2023"
        }');

        $response = $client->getResponse();

        $this->assertSame(201, $client->getResponse()->getStatusCode());
        $this->assertSame("booking created!", json_decode($response->getContent(), true)['message']);
    }
}
