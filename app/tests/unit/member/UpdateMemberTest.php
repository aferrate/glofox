<?php

namespace App\Tests\Unit\Member;

use PHPUnit\Framework\TestCase;
use DG\BypassFinals;
use App\Domain\Model\Member;
use App\Repository\MemberRepository;
use App\Service\CustomSerializer;
use App\Domain\Validations\MemberChecker;
use App\Application\UseCases\Member\UpdateMember;

class UpdateMemberTest extends TestCase
{
    protected function setUp(): void
    {
        BypassFinals::enable();
    }

    public function testUpdateClassroom(): void
    {
        $params = ["name" => "test"];
        $memberRepository = $this->createMock(MemberRepository::class);
        $customSerializer = $this->createMock(CustomSerializer::class);
        $memberChecker = $this->createMock(MemberChecker::class);
        $updateMember = new UpdateMember($memberRepository, $memberChecker, $customSerializer);
        $member = new Member();
        $member->setName('test');

        $memberChecker->expects($this->any())
            ->method('checkId')
            ->willReturn(['status' => true, 'message' => 'ok'])
        ;
        $memberChecker->expects($this->any())
            ->method('checkName')
            ->willReturn(['status' => true, 'message' => 'ok'])
        ;

        $memberRepository->expects($this->any())
            ->method('findOneById')
            ->willReturn($member)
        ;
        $memberRepository->expects($this->any())
            ->method('findOneByName')
            ->willReturn(null)
        ;
        $memberRepository->expects($this->any())
            ->method('save')
            ->willReturn(1)
        ;

        $customSerializer->expects($this->any())
            ->method('deserialize')
            ->willReturn($member)
        ;

        $this->assertSame(['status' => true, 'data' => ['message' => 'member updated!']], $updateMember->execute(1, $params));
    }
}
