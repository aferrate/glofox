<?php

namespace App\Tests\Controller;

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

        $this->assertResponseIsSuccessful();
    }

    public function testGetNonExistentBookingById(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/api/v1/booking/id/999999');

        $this->assertSame(400, $client->getResponse()->getStatusCode());
    }

    public function testAddBooking(): void
    {
        $memberRepository = static::getContainer()->get(MemberRepository::class);
        $classroomRepository = static::getContainer()->get(ClassroomRepository::class);
        $classroom = new Classroom();
        $classroom->setName('testforbooking');
        $classroom->setCapacity(8);
        $classroom->setStartDate(DateTime::createFromFormat('d-m-Y','12-07-2022'));
        $classroom->setEndDate(DateTime::createFromFormat('d-m-Y','18-07-2022'));
        $this->idClass = $classroomRepository->save($classroom);
        $member = new Member();
        $member->setName('testforbooking');
        $this->idMember = $memberRepository->save($member);

        $client = static::createClient();
        $crawler = $client->request('POST', '/api/v1/booking/create', [], [], ['CONTENT_TYPE' => 'application/json'], '{
            "member_id" : '.$this->idMember.',
            "classroom_id" : '.$this->idClass.',
            "date" : "14-07-2022"
        }');

        $this->assertSame(200, $client->getResponse()->getStatusCode());

        $this->idBooking = json_decode($client->getResponse(), true)['id'];
    }

    public function testUpdateNonExistentBooking(): void
    {
        $client = static::createClient();
        $crawler = $client->request('PUT', '/api/v1/booking/update/999999', [], [], ['CONTENT_TYPE' => 'application/json'], '{
            "name" : "membertotestmodify"
        }');

        $this->assertSame(400, $client->getResponse()->getStatusCode());
    }

    public function testDeleteNonExistentBooking(): void
    {
        $client = static::createClient();
        $crawler = $client->request('DELETE', '/api/v1/booking/delete/999999');

        $this->assertSame(400, $client->getResponse()->getStatusCode());
    }
}
