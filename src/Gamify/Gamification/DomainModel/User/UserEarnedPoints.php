<?php

namespace Gamify\Gamification\DomainModel\User;

use DateTime;
use Ddd\Domain\DomainEvent;

class UserEarnedPoints implements DomainEvent
{
    /**
     * @var UserId
     */
    private $userId;

    /**
     * @var int
     */
    private $earnedPoints;

    /**
     * @var DateTime
     */
    private $occurredOn;

    public function __construct(UserId $id, $earnedPoints)
    {
        $this->userId = $id;
        $this->earnedPoints = $earnedPoints;
        $this->occurredOn = new DateTime();
    }

    public function occurredOn()
    {
        return $this->occurredOn;
    }

    public function userId()
    {
        return $this->userId;
    }

    public function earnedPoints()
    {
        return $this->earnedPoints;
    }
}