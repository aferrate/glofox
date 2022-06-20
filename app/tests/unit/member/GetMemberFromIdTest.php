<?php

namespace App\Tests\Unit\Member;

use PHPUnit\Framework\TestCase;
use DG\BypassFinals;
use App\Repository\MemberRepository;
use App\Service\CustomSerializer;
use App\Domain\Validations\MemberChecker;
use App\Application\UseCases\Member\GetMemberFromId;
use App\Domain\Model\Member;

class GetMemberFromIdTest extends TestCase
{
    protected function setUp(): void
    {
        BypassFinals::enable();
    }

    public function testGetMemberFromId(): void
    {
        $memberRepository = $this->createMock(MemberRepository::class);
        $customSerializer = $this->createMock(CustomSerializer::class);
        $memberChecker = $this->createMock(MemberChecker::class);
        $getMemberFromId = new GetMemberFromId($memberRepository, $memberChecker, $customSerializer);
        $member = new Member();
        $serializedMember = '{"name": "test"}';

        $memberChecker->expects($this->any())
            ->method('checkId')
            ->willReturn(['status' => true, 'message' => 'ok'])
        ;

        $memberRepository->expects($this->any())
            ->method('findOneById')
            ->willReturn($member)
        ;

        $customSerializer->expects($this->any())
            ->method('serialize')
            ->willReturn($serializedMember)
        ;

        $this->assertSame(['status' => true, 'data' => json_decode($serializedMember, true)], $getMemberFromId->execute(1));
    }
}
