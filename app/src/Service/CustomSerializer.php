<?php

namespace App\Service;

use App\Domain\Model\Booking;
use App\Domain\Model\Classroom;
use App\Domain\Model\Member;
use App\Domain\Service\SerializerInterface;
use DateTime;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

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
        $objectDeserialized = null;

        switch ($class) {
            case 'classroom':
                $objectDeserialized = $this->serializer->deserialize(
                    json_encode($arrClass), Classroom::class, 'json', [AbstractNormalizer::IGNORED_ATTRIBUTES => ['start_date', 'end_date']]);
                $objectDeserialized->setStartDate(DateTime::createFromFormat('d-m-Y', $arrClass['start_date']));
                $objectDeserialized->setEndDate(DateTime::createFromFormat('d-m-Y', $arrClass['end_date']));
                break;
            case 'booking':
                $objectDeserialized = $this->serializer->deserialize(
                    json_encode($arrClass), Booking::class, 'json', [AbstractNormalizer::IGNORED_ATTRIBUTES => ['date']]);
                $objectDeserialized->setDate(DateTime::createFromFormat('d-m-Y', $arrClass['date']));
                break;
            case 'member':
                $objectDeserialized = $this->serializer->deserialize(json_encode($arrClass), Member::class, 'json');
                break;
        }

        return $objectDeserialized;
    }
}
