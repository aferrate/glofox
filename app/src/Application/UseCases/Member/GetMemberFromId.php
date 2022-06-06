<?php

namespace App\Application\UseCases\Member;

use App\Domain\Repository\MemberRepositoryInterface;
use App\Domain\Validations\MemberChecker;

class GetMemberFromId
{
    private $memberRepository;
    private $memberChecker;

    public function __construct(MemberRepositoryInterface $memberRepository, MemberChecker $memberChecker)
    {
        $this->memberRepository = $memberRepository;
        $this->memberChecker = $memberChecker;
    }

    public function execute(int $id): array
    {
        try{
            $checkId = $this->memberChecker->checkId($id);

            if($checkId['status'] === false) {
                return ['status' => false, 'data' => ['message' => $checkId['message']]];
            }

            $memberObj = $this->memberRepository->findOneById($id);

            if(is_null($memberObj)) {
                return ['status' => false, 'data' => ['message' => 'no member found']];
            }
    
            $member = $memberObj->returnArrayMember($memberObj);
    
            return ['status' => true, 'data' => $member];
        } catch(\Exception $e){
            return ['status' => false, 'data' => ['message' => $e->getMessage()]];
        }
    }
}
