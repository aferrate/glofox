<?php

namespace App\Application\UseCases\Member;

use App\Domain\Repository\MemberRepositoryInterface;
use App\Domain\Model\Member;
use App\Domain\Service\SerializerInterface;

class GetAllMembers
{
    private $memberRepository;

    public function __construct(MemberRepositoryInterface $memberRepository, SerializerInterface $serializer)
    {
        $this->memberRepository = $memberRepository;
        $this->serializer = $serializer;
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
                $members[] = json_decode($this->serializer->serialize($member), true);
            }

            return ['status' => true, 'data' => $members];
        } catch(\Exception $e){
            return ['status' => false, 'data' => ['message' => $e->getMessage()]];
        }
    }
}
