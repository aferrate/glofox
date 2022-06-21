<?php

namespace App\Domain\Service;

interface SerializerInterface
{
    public function serialize(object $object): string;

    public function deserialize(array $arrClass, string $class): object;
}
