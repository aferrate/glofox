<?php

namespace App\Tests\Unit\Booking;

use App\Application\UseCases\Booking\AddBooking;
use App\Domain\Model\Booking;
use App\Domain\Model\Classroom;
use App\Domain\Model\Member;
use App\Domain\Validations\BookingChecker;
use App\Repository\BookingRepository;
use App\Repository\ClassroomRepository;
use App\Repository\MemberRepository;
use App\Service\CustomSerializer;
use DG\BypassFinals;
use PHPUnit\Framework\TestCase;

class AddBookingTest extends TestCase
{
    protected function setUp(): void
    {
        BypassFinals::enable();
    }

    public function testAddMember(): void
    {
        $params = ['idMember' => 1, 'idClassroom' => 1, 'date' => '15-06-2023'];
        $classroomRepository = $this->createMock(ClassroomRepository::class);
        $bookingRepository = $this->createMock(BookingRepository::class);
        $memberRepository = $this->createMock(MemberRepository::class);
        $customSerializer = $this->createMock(CustomSerializer::class);
        $bookingChecker = $this->createMock(BookingChecker::class);
        $addBooking = new AddBooking($bookingRepository, $memberRepository, $classroomRepository, $bookingChecker, $customSerializer);
        $booking = new Booking();
        $classroom = new Classroom();
        $classroom->setCapacity(9);
        $member = new Member();

        $bookingChecker->expects($this->any())
            ->method('checkParams')
            ->willReturn(['status' => true, 'message' => 'ok'])
        ;

        $bookingRepository->expects($this->any())
            ->method('findByDateMemberIdClassId')
            ->willReturn(null)
        ;
        $bookingRepository->expects($this->any())
            ->method('countBookings')
            ->willReturn(6)
        ;

        $classroomRepository->expects($this->any())
            ->method('findOneById')
            ->willReturn($classroom)
        ;

        $memberRepository->expects($this->any())
            ->method('findOneById')
            ->willReturn($member)
        ;

        $customSerializer->expects($this->any())
            ->method('deserialize')
            ->willReturn($booking)
        ;

        $bookingRepository->expects($this->any())
            ->method('save')
            ->willReturn(60)
        ;

        $this->assertSame(['status' => true, 'data' => ['message' => 'booking created!', 'id' => 60]], $addBooking->execute($params));
    }
}
