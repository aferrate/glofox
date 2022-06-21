<?php

namespace App\Tests\Unit\Booking;

use App\Application\UseCases\Booking\GetBookingFromId;
use App\Domain\Model\Booking;
use App\Domain\Validations\BookingChecker;
use App\Repository\BookingRepository;
use App\Service\CustomSerializer;
use DG\BypassFinals;
use PHPUnit\Framework\TestCase;

class GetBookingFromIdTest extends TestCase
{
    protected function setUp(): void
    {
        BypassFinals::enable();
    }

    public function testGetBookingFromId(): void
    {
        $bookingRepository = $this->createMock(BookingRepository::class);
        $bookingChecker = $this->createMock(BookingChecker::class);
        $customSerializer = $this->createMock(CustomSerializer::class);
        $getBookingFromId = new GetBookingFromId($bookingRepository, $bookingChecker, $customSerializer);
        $serializedBooking = '{"idMember" : 1, "idClassroom" : 1, "date" : "15-06-2026"}';
        $booking = new Booking();

        $bookingChecker->expects($this->any())
            ->method('checkId')
            ->willReturn(['status' => true, 'message' => 'ok'])
        ;

        $bookingRepository->expects($this->any())
            ->method('findOneById')
            ->willReturn($booking)
        ;

        $customSerializer->expects($this->any())
            ->method('serialize')
            ->willReturn($serializedBooking)
        ;

        $this->assertSame(['status' => true, 'data' => json_decode($serializedBooking, true)], $getBookingFromId->execute(1));
    }
}
