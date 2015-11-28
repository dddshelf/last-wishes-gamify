<?php

namespace Gamify\Gamification\DomainModel\User;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

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

    function __toString()
    {
        return $this->id->toString();
    }
}