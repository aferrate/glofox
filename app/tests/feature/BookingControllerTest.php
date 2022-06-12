<?php

namespace App\Tests\Feature;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Repository\MemberRepository;
use App\Repository\ClassroomRepository;
use App\Repository\BookingRepository;
use App\Domain\Model\Classroom;
use App\Domain\Model\Member;
use DateTime;

class BookingControllerTest extends WebTestCase
{
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
            "date" : "14-07-2023"
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
        $member = new Member();
        $member->setName('testmember');
        $classroom = new Classroom();
        $classroom->setName('testclassroom');
        $classroom->setCapacity(7);
        $classroom->setStartDate(DateTime::createFromFormat('d-m-Y', '10-06-2023'));
        $classroom->setEndDate(DateTime::createFromFormat('d-m-Y', '15-06-2023'));
        $idMember = $memberRepository->save($member);
        $idClassroom = $classroomRepository->save($classroom);
        
        $crawler = $client->request('POST', '/api/v1/booking/create', [], [], ['CONTENT_TYPE' => 'application/json'], '{
            "idMember" : '.$idMember.',
            "idClassroom" : '.$idClassroom.',
            "date" : "14-06-2023"
        }');

        $response = $client->getResponse();

        $this->assertSame(201, $client->getResponse()->getStatusCode());
        $this->assertSame("booking created!", json_decode($response->getContent(), true)['message']);
    }

    public function testUpdateBooking(): void
    {
        $client = static::createClient();

        $memberRepository = static::getContainer()->get(MemberRepository::class);
        $classroomRepository = static::getContainer()->get(ClassroomRepository::class);
        $bookingRepository = static::getContainer()->get(BookingRepository::class);
        $idMember = $memberRepository->findOneByName('testmember')->getId();
        $idClassroom = $classroomRepository->findOneByNameAndDatesAndCapacity([
            'name' => 'testclassroom',
            'capacity' => 7,
            'start_date' => '10-06-2023',
            'end_date' => '15-06-2023'
        ])->getId();
        $idBooking = $bookingRepository->findByDateMemberIdClassId([
            'idMember' => $idMember, 'idClassroom' => $idClassroom,
            'date' => '14-06-2023'
        ])->getId();

        $crawler = $client->request('PUT', "/api/v1/booking/update/$idBooking", [], [], ['CONTENT_TYPE' => 'application/json'], '{
            "idMember" : '.$idMember.',
            "idClassroom" : '.$idClassroom.',
            "date" : "13-06-2023"
        }');

        $response = $client->getResponse();

        $this->assertSame(200, $client->getResponse()->getStatusCode());
        $this->assertSame("booking updated!", json_decode($response->getContent(), true)['message']);
    }

    public function testDeleteBooking(): void
    {
        $client = static::createClient();

        $memberRepository = static::getContainer()->get(MemberRepository::class);
        $classroomRepository = static::getContainer()->get(ClassroomRepository::class);
        $bookingRepository = static::getContainer()->get(BookingRepository::class);

        $idMember = $memberRepository->findOneByName('testmember')->getId();
        $idClassroom = $classroomRepository->findOneByNameAndDatesAndCapacity([
            'name' => 'testclassroom',
            'capacity' => 7,
            'start_date' => '10-06-2023',
            'end_date' => '15-06-2023'
        ])->getId();
        $idBooking = $bookingRepository->findByDateMemberIdClassId([
            'idMember' => $idMember, 'idClassroom' => $idClassroom,
            'date' => '13-06-2023'
        ])->getId();
        
        $crawler = $client->request('DELETE', "/api/v1/booking/delete/$idBooking");

        $response = $client->getResponse();

        $this->assertSame(200, $client->getResponse()->getStatusCode());
        $this->assertSame("booking deleted", json_decode($response->getContent(), true)['message']);
    }
}
