<?php

namespace App\Controller;

use App\Application\UseCases\Member\GetMemberFromId;
use App\Application\UseCases\Member\GetAllMembers;
use App\Application\UseCases\Member\AddMember;
use App\Application\UseCases\Member\UpdateMember;
use App\Application\UseCases\Member\DeleteMember;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class MemberController
 * @package App\Controller
 *
 * @Route(path="/api/v1/")
 */
class MemberController
{
    /**
     * @Route("member/id/{id}", name="get_member_id", methods={"GET"})
     */
    public function getFromId(int $id, GetMemberFromId $getMemberFromId): JsonResponse
    {
        $result = $getMemberFromId->execute($id);
        $status = ($result['status']) ? Response::HTTP_OK : Response::HTTP_BAD_REQUEST;

        return new JsonResponse($result['data'], $status);
    }

    /**
     * @Route("members", name="get_all_members", methods={"GET"})
     */
    public function getAll(GetAllMembers $getAllMembers): JsonResponse
    {
        $result = $getAllMembers->execute();
        $status = ($result['status']) ? Response::HTTP_OK : Response::HTTP_BAD_REQUEST;

        return new JsonResponse($result['data'], $status);
    }

    /**
     * @Route("member/create", name="add_member", methods={"POST"})
     */
    public function add(Request $request, AddMember $addMember): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $result = $addMember->execute($data);
        $status = ($result['status']) ? Response::HTTP_CREATED : Response::HTTP_BAD_REQUEST;

        return new JsonResponse($result['data'], $status);
    }

    /**
     * @Route("member/update/{id}", name="update_member", methods={"PUT"})
     */
    public function update(int $id, Request $request, UpdateMember $updateMember): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $result = $updateMember->execute($id, $data);
        $status = ($result['status']) ? Response::HTTP_OK : Response::HTTP_BAD_REQUEST;

		return new JsonResponse($result['data'], $status);
    }

    /**
     * @Route("member/delete/{id}", name="delete_member", methods={"DELETE"})
     */
    public function delete(int $id, DeleteMember $deleteMember): JsonResponse
    {
        $result = $deleteMember->execute($id);
        $status = ($result['status']) ? Response::HTTP_OK : Response::HTTP_BAD_REQUEST;

        return new JsonResponse($result['data'], $status);
    }
}
