<?php

namespace App\Tests\Unit\Member;

use App\Application\UseCases\Member\GetAllMembers;
use App\Domain\Model\Member;
use App\Repository\MemberRepository;
use App\Service\CustomSerializer;
use DG\BypassFinals;
use PHPUnit\Framework\TestCase;

class GetAllMembersTest extends TestCase
{
    protected function setUp(): void
    {
        BypassFinals::enable();
    }

    public function testGetAllMembers(): void
    {
        $memberRepository = $this->createMock(MemberRepository::class);
        $customSerializer = $this->createMock(CustomSerializer::class);
        $getAllMembers = new GetAllMembers($memberRepository, $customSerializer);
        $serializedMember = '{"name": "test"}';
        $member = new Member();

        $memberRepository->expects($this->any())
            ->method('findAll')
            ->willReturn([$member])
        ;

        $customSerializer->expects($this->any())
            ->method('serialize')
            ->willReturn($serializedMember)
        ;

        $this->assertSame(['status' => true, 'data' => [json_decode($serializedMember, true)]], $getAllMembers->execute());
    }
}
