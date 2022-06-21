<?php

namespace App\Tests\Unit\Member;

use App\Application\UseCases\Member\AddMember;
use App\Domain\Model\Member;
use App\Domain\Validations\MemberChecker;
use App\Repository\MemberRepository;
use App\Service\CustomSerializer;
use DG\BypassFinals;
use PHPUnit\Framework\TestCase;

class AddMemberTest extends TestCase
{
    protected function setUp(): void
    {
        BypassFinals::enable();
    }

    public function testAddMember(): void
    {
        $params = ['name' => 'test'];
        $memberRepository = $this->createMock(MemberRepository::class);
        $customSerializer = $this->createMock(CustomSerializer::class);
        $memberChecker = $this->createMock(MemberChecker::class);
        $addMember = new AddMember($memberRepository, $memberChecker, $customSerializer);
        $member = new Member();

        $memberChecker->expects($this->any())
            ->method('checkName')
            ->willReturn(['status' => true, 'message' => 'ok'])
        ;

        $memberRepository->expects($this->any())
            ->method('findOneByName')
            ->willReturn(null)
        ;
        $memberRepository->expects($this->any())
            ->method('save')
            ->willReturn(60)
        ;

        $customSerializer->expects($this->any())
            ->method('deserialize')
            ->willReturn($member)
        ;

        $this->assertSame(['status' => true, 'data' => ['message' => 'member created!', 'id' => 60]], $addMember->execute($params));
    }
}
