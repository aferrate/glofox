<?php

namespace App\Application\UseCases\Member;

use App\Domain\Repository\MemberRepositoryInterface;
use App\Domain\Service\SerializerInterface;
use App\Domain\Validations\MemberChecker;

class GetMemberFromId
{
    private $memberRepository;
    private $memberChecker;
    private $serializer;

    public function __construct(
        MemberRepositoryInterface $memberRepository,
        MemberChecker $memberChecker,
        SerializerInterface $serializer
    ) {
        $this->memberRepository = $memberRepository;
        $this->memberChecker = $memberChecker;
        $this->serializer = $serializer;
    }

    public function execute(int $id): array
    {
        try {
            $checkId = $this->memberChecker->checkId($id);

            if (false === $checkId['status']) {
                return ['status' => false, 'data' => ['message' => $checkId['message']]];
            }

            $memberObj = $this->memberRepository->findOneById($id);

            if (is_null($memberObj)) {
                return ['status' => false, 'data' => ['message' => 'no member found']];
            }

            $member = json_decode($this->serializer->serialize($memberObj), true);

            return ['status' => true, 'data' => $member];
        } catch (\Exception $e) {
            return ['status' => false, 'data' => ['message' => $e->getMessage()]];
        }
    }
}
