<?php

namespace App\Service;

use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Serializer;
use App\Domain\Service\SerializerInterface;
use App\Domain\Model\Classroom;
use App\Domain\Model\Booking;
use App\Domain\Model\Member;
use DateTime;

final class CustomSerializer implements SerializerInterface
{
    private $serializer;

    public function __construct()
    {
        $encoders = [new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];

        $this->serializer = new Serializer($normalizers, $encoders);
    }

    public function serialize(object $object): string
    {
        return $this->serializer->serialize($object, 'json');
    }

    public function deserialize(array $arrClass, string $class): object
    {
        switch ($class) {
            case "classroom":
                $classroom = $this->serializer->deserialize(
                    json_encode($arrClass), Classroom::class, 'json', [AbstractNormalizer::IGNORED_ATTRIBUTES => ['start_date', 'end_date']]);
                $classroom->setStartDate(DateTime::createFromFormat('d-m-Y', $arrClass['start_date']));
                $classroom->setEndDate(DateTime::createFromFormat('d-m-Y', $arrClass['end_date']));

                return $classroom;
                break;
            case "booking":
                $booking = $this->serializer->deserialize(
                    json_encode($arrClass), Booking::class, 'json', [AbstractNormalizer::IGNORED_ATTRIBUTES => ['date']]);
                $booking->setDate(DateTime::createFromFormat('d-m-Y', $arrClass['date']));
                
                return $booking;
                break;
            case "member":
                return $this->serializer->deserialize(json_encode($arrClass), Member::class, 'json');
                break;
        }
    }
}
