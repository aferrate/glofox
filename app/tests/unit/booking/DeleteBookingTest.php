<?php

namespace App\Tests\Unit\Booking;

use App\Application\UseCases\Booking\DeleteBooking;
use App\Domain\Model\Booking;
use App\Domain\Validations\BookingChecker;
use App\Repository\BookingRepository;
use DG\BypassFinals;
use PHPUnit\Framework\TestCase;

class DeleteBookingTest extends TestCase
{
    protected function setUp(): void
    {
        BypassFinals::enable();
    }

    public function testDeleteMember(): void
    {
        $bookingRepository = $this->createMock(BookingRepository::class);
        $bookingChecker = $this->createMock(BookingChecker::class);
        $booking = new Booking();
        $deleteBooking = new DeleteBooking($bookingRepository, $bookingChecker);

        $bookingChecker->expects($this->any())
            ->method('checkId')
            ->willReturn(['status' => true, 'message' => 'ok'])
        ;

        $bookingRepository->expects($this->any())
            ->method('findOneById')
            ->willReturn($booking)
        ;
        $bookingRepository->expects($this->any())
            ->method('delete')
        ;

        $this->assertSame(['status' => true, 'data' => ['message' => 'booking deleted']], $deleteBooking->execute(1));
    }
}
