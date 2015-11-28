<?php

namespace Gamify\Gamification\DomainModel\User;

use DateTimeImmutable;
use Ddd\Domain\DomainEvent;

class UserSignedUp implements DomainEvent
{
    /**
     * @var UserId
     */
    private $userId;

    public function __construct(UserId $userId)
    {
        $this->userId = $userId;
        $this->occurredOn = new DateTimeImmutable();
    }

    public function userId()
    {
        return $this->userId;
    }

    public function occurredOn()
    {
        return $this->occurredOn;
    }
}