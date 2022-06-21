<?php

namespace App\Application\UseCases\Member;

use App\Domain\Repository\MemberRepositoryInterface;
use App\Domain\Service\SerializerInterface;
use App\Domain\Validations\MemberChecker;

class AddMember
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

    public function execute(array $memberArr): array
    {
        try {
            $checkParams = $this->memberChecker->checkName($memberArr);

            if (false === $checkParams['status']) {
                return ['status' => false, 'data' => ['message' => $checkParams['message']]];
            }

            $memberExist = $this->memberRepository->findOneByName($memberArr['name']);

            if (!is_null($memberExist)) {
                return ['status' => false, 'data' => ['message' => 'member already exists']];
            }

            $member = $this->serializer->deserialize($memberArr, 'member');

            $id = $this->memberRepository->save($member);

            return [
                'status' => true,
                'data' => [
                    'message' => 'member created!',
                    'id' => $id,
                ],
            ];
        } catch (\Exception $e) {
            return ['status' => false, 'data' => ['message' => $e->getMessage()]];
        }
    }
}
