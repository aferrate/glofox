<?php

namespace App\Application\UseCases\Member;

use App\Domain\Repository\MemberRepositoryInterface;
use App\Domain\Model\Member;

class GetAllMembers
{
    private $memberRepository;

    public function __construct(MemberRepositoryInterface $memberRepository)
    {
        $this->memberRepository = $memberRepository;
    }

    public function execute(): array
    {
        try{
            $membersObjs = $this->memberRepository->findAll();

            if(empty($membersObjs)) {
                return ['status' => true, 'data' => ['message' => 'no members found']];
            }

            $members = [];

            foreach ($membersObjs as $member) {
                $members[] = $member->returnArrayMember($member);
            }

            return ['status' => true, 'data' => $members];
        } catch(\Exception $e){
            return ['status' => false, 'data' => ['message' => $e->getMessage()]];
        }
    }
}
