<?php

namespace App\Domain\Validations;

final class MemberChecker
{
    public function checkId(int $id): array
    {
        if (!is_int($id) || $id < 1) {
            return ['status' => false, 'message' => 'id must be integer and greater than 0'];
        }

        return ['status' => true, 'message' => 'ok'];
    }

    public function checkName(array $params): array
    {
        if (!isset($params['name']) || !ctype_alpha($params['name'])) {
            return ['status' => false, 'message' => 'name must be specified and be an alpha string'];
        }

        return ['status' => true, 'message' => 'ok'];
    }
}
