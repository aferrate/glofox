<?php

namespace App\Controller;

use App\Application\UseCases\Classroom\AddClassroom;
use App\Application\UseCases\Classroom\DeleteClassroom;
use App\Application\UseCases\Classroom\GetAllClassrooms;
use App\Application\UseCases\Classroom\GetClassroomFromId;
use App\Application\UseCases\Classroom\UpdateClassroom;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ClassroomController.
 *
 * @Route(path="/api/v1/")
 */
class ClassroomController
{
    /**
     * @Route("classroom/id/{id}", name="get_classroom_id", methods={"GET"})
     */
    public function getFromId(int $id, GetClassroomFromId $getClassroomFromId): JsonResponse
    {
        $result = $getClassroomFromId->execute($id);
        $status = ($result['status']) ? Response::HTTP_OK : Response::HTTP_BAD_REQUEST;

        return new JsonResponse($result['data'], $status);
    }

    /**
     * @Route("classrooms", name="get_all_classrooms", methods={"GET"})
     */
    public function getAll(GetAllClassrooms $getAllClassrooms): JsonResponse
    {
        $result = $getAllClassrooms->execute();
        $status = ($result['status']) ? Response::HTTP_OK : Response::HTTP_BAD_REQUEST;

        return new JsonResponse($result['data'], $status);
    }

    /**
     * @Route("classroom/create", name="add_classroom", methods={"POST"})
     */
    public function add(Request $request, AddClassroom $addClassroom): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $result = $addClassroom->execute($data);
        $status = ($result['status']) ? Response::HTTP_CREATED : Response::HTTP_BAD_REQUEST;

        return new JsonResponse($result['data'], $status);
    }

    /**
     * @Route("classroom/update/{id}", name="update_classroom", methods={"PUT"})
     */
    public function update(int $id, Request $request, UpdateClassroom $updateClassroom): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $result = $updateClassroom->execute($id, $data);
        $status = ($result['status']) ? Response::HTTP_OK : Response::HTTP_BAD_REQUEST;

        return new JsonResponse($result['data'], $status);
    }

    /**
     * @Route("classroom/delete/{id}", name="delete_classroom", methods={"DELETE"})
     */
    public function delete(int $id, DeleteClassroom $deleteClassroom): JsonResponse
    {
        $result = $deleteClassroom->execute($id);
        $status = ($result['status']) ? Response::HTTP_OK : Response::HTTP_BAD_REQUEST;

        return new JsonResponse($result['data'], $status);
    }
}
