<?php

namespace App\Tests\Unit\Booking;

use App\Application\UseCases\Booking\UpdateBooking;
use App\Domain\Model\Booking;
use App\Domain\Model\Classroom;
use App\Domain\Model\Member;
use App\Domain\Validations\BookingChecker;
use App\Repository\BookingRepository;
use App\Repository\ClassroomRepository;
use App\Repository\MemberRepository;
use App\Service\CustomSerializer;
use DateTime;
use DG\BypassFinals;
use PHPUnit\Framework\TestCase;

class UpdateBookingTest extends TestCase
{
    protected function setUp(): void
    {
        BypassFinals::enable();
    }

    public function testUpdateBooking(): void
    {
        $params = ['idMember' => 1, 'idClassroom' => 1, 'date' => '15-06-2023'];
        $classroomRepository = $this->createMock(ClassroomRepository::class);
        $bookingRepository = $this->createMock(BookingRepository::class);
        $memberRepository = $this->createMock(MemberRepository::class);
        $customSerializer = $this->createMock(CustomSerializer::class);
        $bookingChecker = $this->createMock(BookingChecker::class);
        $bookingMock = $this->createMock(Booking::class);
        $updateBooking = new UpdateBooking($bookingRepository, $bookingChecker, $memberRepository, $classroomRepository, $customSerializer);
        $booking = new Booking();
        $booking->setIdMember(1);
        $booking->setIdClassroom(1);
        $booking->setDate(DateTime::createFromFormat('d-m-Y', '15-06-2023'));
        $classroom = new Classroom();
        $classroom->setCapacity(9);
        $member = new Member();

        $bookingChecker->expects($this->any())
            ->method('checkId')
            ->willReturn(['status' => true, 'message' => 'ok'])
        ;
        $bookingChecker->expects($this->any())
            ->method('checkParams')
            ->willReturn(['status' => true, 'message' => 'ok'])
        ;

        $bookingRepository->expects($this->any())
            ->method('findOneById')
            ->willReturn($booking)
        ;
        $bookingRepository->expects($this->any())
            ->method('findByDateMemberIdClassId')
            ->willReturn(null)
        ;
        $bookingRepository->expects($this->any())
            ->method('countBookings')
            ->willReturn(6)
        ;
        $bookingRepository->expects($this->any())
            ->method('save')
            ->willReturn(60)
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

        $bookingMock->expects($this->any())
            ->method('getId')
            ->willReturn(1)
        ;

        $this->assertSame(['status' => true, 'data' => ['message' => 'booking updated!']], $updateBooking->execute(1, $params));
    }
}
