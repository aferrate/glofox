<?php

namespace App\Application\UseCases\Member;

use App\Domain\Repository\MemberRepositoryInterface;
use App\Domain\Model\Member;
use App\Domain\Validations\MemberChecker;

class AddMember
{
    private $memberRepository;
    private $memberChecker;

    public function __construct(MemberRepositoryInterface $memberRepository, MemberChecker $memberChecker)
    {
        $this->memberRepository = $memberRepository;
        $this->memberChecker = $memberChecker;
    }

    public function execute(array $memberArr): array
    {
        try{
            $checkParams = $this->memberChecker->checkName($memberArr);

            if($checkParams['status'] === false) {
                return ['status' => false, 'data' => ['message' => $checkParams['message']]];
            }

            $memberExist = $this->memberRepository->findOneByName($memberArr['name']);

            if(!is_null($memberExist)) {
                return ['status' => false, 'data' => ['message' => 'member already exists']];
            }

            $member = new Member();
            $member = Member::returnObjMember($member, $memberArr);
    
            $id = $this->memberRepository->save($member);
    
            return [
                'status' => true,
                'data' => [
                    'message' => 'member created!',
                    'id' => $id
                ]
            ];
        } catch(\Exception $e){
            return ['status' => false, 'data' => ['message' => $e->getMessage()]];
        }
    }
}
