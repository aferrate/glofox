<?php

namespace App\Controller;

use App\Application\UseCases\Booking\GetBookingFromId;
use App\Application\UseCases\Booking\GetAllBookings;
use App\Application\UseCases\Booking\AddBooking;
use App\Application\UseCases\Booking\UpdateBooking;
use App\Application\UseCases\Booking\DeleteBooking;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class BookingController
 * @package App\Controller
 *
 * @Route(path="/api/v1/")
 */
class BookingController
{
    /**
     * @Route("booking/id/{id}", name="get_booking_id", methods={"GET"})
     */
    public function getFromId(int $id, GetBookingFromId $getBookingFromId): JsonResponse
    {
        $result = $getBookingFromId->execute($id);
        $status = ($result['status']) ? Response::HTTP_OK : Response::HTTP_BAD_REQUEST;

        return new JsonResponse($result['data'], $status);
    }

    /**
     * @Route("bookings", name="get_all_bookings", methods={"GET"})
     */
    public function getAll(GetAllBookings $getAllBookings): JsonResponse
    {
        $result = $getAllBookings->execute();
        $status = ($result['status']) ? Response::HTTP_OK : Response::HTTP_BAD_REQUEST;

        return new JsonResponse($result['data'], $status);
    }

    /**
     * @Route("booking/create", name="add_booking", methods={"POST"})
     */
    public function add(Request $request, AddBooking $addBooking): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $result = $addBooking->execute($data);
        $status = ($result['status']) ? Response::HTTP_CREATED : Response::HTTP_BAD_REQUEST;

        return new JsonResponse($result['data'], $status);
    }

    /**
     * @Route("booking/update/{id}", name="update_booking", methods={"PUT"})
     */
    public function update(int $id, Request $request, UpdateBooking $updateBooking): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $result = $updateBooking->execute($id, $data);
        $status = ($result['status']) ? Response::HTTP_OK : Response::HTTP_BAD_REQUEST;

		return new JsonResponse($result['data'], $status);
    }

    /**
     * @Route("booking/delete/{id}", name="delete_booking", methods={"DELETE"})
     */
    public function delete(int $id, DeleteBooking $deleteBooking): JsonResponse
    {
        $result = $deleteBooking->execute($id);
        $status = ($result['status']) ? Response::HTTP_OK : Response::HTTP_BAD_REQUEST;

        return new JsonResponse($result['data'], $status);
    }
}
