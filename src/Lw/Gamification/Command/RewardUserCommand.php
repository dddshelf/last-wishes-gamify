<?php

namespace Lw\Gamification\Command;

class RewardUserCommand
{
    /**
     * @var string
     */
    private $userId;

    /**
     * @var int
     */
    private $points;

    public function __construct($userId, $points)
    {
        $this->userId = $userId;
        $this->points = (int) $points;
    }

    public function userId()
    {
        return $this->userId;
    }

    public function points()
    {
        return $this->points;
    }
}