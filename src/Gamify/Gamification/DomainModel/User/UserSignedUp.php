<?php

namespace Gamify\Gamification\DomainModel\User;

use DateTime;
use Ddd\Domain\DomainEvent;

class UserSignedUp implements DomainEvent
{
    /**
     * @var UserId
     */
    private $userId;

    /**
     * @var DateTimeImmutable
     */
    private $occurredOn;

    public function __construct(UserId $userId)
    {
        $this->userId = $userId;
        $this->occurredOn = new DateTime();
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