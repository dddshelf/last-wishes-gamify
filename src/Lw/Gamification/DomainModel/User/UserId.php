<?php

namespace Lw\Gamification\DomainModel\User;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Serializable;

final class UserId
{
    /**
     * @var UuidInterface
     */
    private $id;

    public function __construct()
    {
        $this->id = Uuid::uuid4();
    }

    /**
     * @return UuidInterface
     */
    public function id()
    {
        return $this->id;
    }

    public function __toString()
    {
        return $this->id->toString();
    }

    public static function fromString($id)
    {
        $userId = new UserId();
        $userId->id = Uuid::fromString($id);

        return $userId;
    }
}