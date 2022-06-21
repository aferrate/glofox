<?php

namespace App\Application\UseCases\Member;

use App\Domain\Repository\BookingRepositoryInterface;
use App\Domain\Repository\MemberRepositoryInterface;
use App\Domain\Validations\MemberChecker;

class DeleteMember
{
    private $memberRepository;
    private $bookingRepository;
    private $memberChecker;

    public function __construct(
        MemberRepositoryInterface $memberRepository,
        BookingRepositoryInterface $bookingRepository,
        MemberChecker $memberChecker
    ) {
        $this->memberRepository = $memberRepository;
        $this->memberChecker = $memberChecker;
        $this->bookingRepository = $bookingRepository;
    }

    public function execute(int $id): array
    {
        try {
            $checkId = $this->memberChecker->checkId($id);

            if (false === $checkId['status']) {
                return ['status' => false, 'data' => ['message' => $checkParams['message']]];
            }

            $member = $this->memberRepository->findOneById($id);

            if (is_null($member)) {
                return ['status' => false, 'data' => ['message' => 'no member found']];
            }

            $this->memberRepository->delete($member);
            $this->deleteBookingsByMemberId($id);

            return ['status' => true, 'data' => ['message' => 'member deleted']];
        } catch (\Exception $e) {
            return ['status' => false, 'data' => ['message' => $e->getMessage()]];
        }
    }

    private function deleteBookingsByMemberId(int $id): void
    {
        $bookingIds = $this->bookingRepository->findByMemberId($id);
        $arrIds = [];

        foreach ($bookingIds as $id) {
            $arrIds[] = intval($id['id']);
        }

        if (!empty($bookingIds)) {
            $this->bookingRepository->deleteFromArrayOfIds($arrIds);
        }
    }
}
