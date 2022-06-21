<?php

namespace App\Tests\Unit\Member;

use App\Application\UseCases\Member\DeleteMember;
use App\Domain\Model\Member;
use App\Domain\Validations\MemberChecker;
use App\Repository\BookingRepository;
use App\Repository\MemberRepository;
use DG\BypassFinals;
use PHPUnit\Framework\TestCase;

class DeleteMemberTest extends TestCase
{
    protected function setUp(): void
    {
        BypassFinals::enable();
    }

    public function testDeleteMember(): void
    {
        $memberRepository = $this->createMock(MemberRepository::class);
        $bookingRepository = $this->createMock(BookingRepository::class);
        $memberChecker = $this->createMock(MemberChecker::class);
        $deleteMember = new DeleteMember($memberRepository, $bookingRepository, $memberChecker);
        $member = new Member();

        $memberChecker->expects($this->any())
            ->method('checkId')
            ->willReturn(['status' => true, 'message' => 'ok'])
        ;

        $memberRepository->expects($this->any())
            ->method('findOneById')
            ->willReturn($member)
        ;
        $memberRepository->expects($this->any())
            ->method('delete')
        ;

        $bookingRepository->expects($this->any())
            ->method('findByMemberId')
            ->willReturn([])
        ;

        $this->assertSame(['status' => true, 'data' => ['message' => 'member deleted']], $deleteMember->execute(1));
    }
}
