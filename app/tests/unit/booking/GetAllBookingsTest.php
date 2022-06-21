<?php

namespace App\Tests\Unit\Booking;

use App\Application\UseCases\Booking\GetAllBookings;
use App\Domain\Model\Booking;
use App\Domain\Validations\BookingChecker;
use App\Repository\BookingRepository;
use App\Service\CustomSerializer;
use DG\BypassFinals;
use PHPUnit\Framework\TestCase;

class GetAllBookingsTest extends TestCase
{
    protected function setUp(): void
    {
        BypassFinals::enable();
    }

    public function testGetAllBookings(): void
    {
        $bookingRepository = $this->createMock(BookingRepository::class);
        $bookingChecker = $this->createMock(BookingChecker::class);
        $customSerializer = $this->createMock(CustomSerializer::class);
        $getAllBookings = new GetAllBookings($bookingRepository, $bookingChecker, $customSerializer);
        $serializedBooking = '{"idMember" : 1, "idClassroom" : 1, "date" : "15-06-2026"}';
        $booking = new Booking();

        $bookingRepository->expects($this->any())
            ->method('findAll')
            ->willReturn([$booking])
        ;

        $customSerializer->expects($this->any())
            ->method('serialize')
            ->willReturn($serializedBooking)
        ;

        $this->assertSame(['status' => true, 'data' => [json_decode($serializedBooking, true)]], $getAllBookings->execute());
    }
}
