<?php

namespace App\Domain\Model;

final class Member
{
    private $id;
    private $name;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return array
     */
    public function returnArrayMember(Member $member): array
    {
        $data[] = [
            'id' => $member->getId(),
            'name' => $member->getName()
        ];

        return $data;
    }

    /**
     * @return Member
     */
    public static function returnObjMember(Member $member, array $memberArr): Member
    {
        $member->setName($memberArr['name']);

        return $member;
    }
}
