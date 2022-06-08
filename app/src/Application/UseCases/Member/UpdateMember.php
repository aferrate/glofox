<?php

namespace App\Application\UseCases\Member;

use App\Domain\Repository\MemberRepositoryInterface;
use App\Domain\Model\Member;
use App\Domain\Validations\MemberChecker;
use App\Domain\Service\SerializerInterface;

class UpdateMember
{
    private $memberRepository;
    private $memberChecker;
    private $serializer;

    public function __construct(
        MemberRepositoryInterface $memberRepository,
        MemberChecker $memberChecker,
        SerializerInterface $serializer
    )
    {
        $this->memberRepository = $memberRepository;
        $this->memberChecker = $memberChecker;
        $this->serializer = $serializer;
    }

    public function execute(int $id, array $memberArr): array
    {
        try{
            $checkId = $this->memberChecker->checkId($id);

            if($checkId['status'] === false) {
                return ['status' => false, 'data' => ['message' => $checkId['message']]];
            }

            $checkParams = $this->memberChecker->checkName($memberArr);

            if($checkParams['status'] === false) {
                return ['status' => false, 'data' => ['message' => $checkParams['message']]];
            }

            $member = $this->memberRepository->findOneById($id);

            if(is_null($member)) {
                return ['status' => false, 'data' => ['message' => 'no member found']];
            }

            if(!is_null($this->memberRepository->findOneByName($memberArr['name']))) {
                return ['status' => false, 'data' => ['message' => 'member already exists']];
            }
    
            $this->memberRepository->save($this->updateMemberObject($member, $memberArr));
    
            return ['status' => true, 'data' => ['message' => 'member updated!']];
        } catch(\Exception $e){
            return ['status' => false, 'data' => ['message' => $e->getMessage()]];
        }
    }

    private function updateMemberObject(Member $member, array $memberArr): Member
    {
        $memberNew = $this->serializer->deserialize($memberArr, 'member');
        $member->setName($memberNew->getName());

        return $member;
    }
}
