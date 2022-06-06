<?php

namespace App\Application\UseCases\Member;

use App\Domain\Repository\MemberRepositoryInterface;
use App\Domain\Model\Member;
use App\Domain\Validations\MemberChecker;

class UpdateMember
{
    private $memberRepository;
    private $memberChecker;

    public function __construct(MemberRepositoryInterface $memberRepository, MemberChecker $memberChecker)
    {
        $this->memberRepository = $memberRepository;
        $this->memberChecker = $memberChecker;
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

            $memberExist = $this->memberRepository->findOneByName($memberArr['name']);

            if(!is_null($memberExist)) {
                return ['status' => false, 'data' => ['message' => 'member already exists']];
            }
    
            $member = Member::returnObjMember($member, $memberArr);
    
            $this->memberRepository->save($member);
    
            return ['status' => true, 'data' => ['message' => 'member updated!']];
        } catch(\Exception $e){
            return ['status' => false, 'data' => ['message' => $e->getMessage()]];
        }
    }
}
