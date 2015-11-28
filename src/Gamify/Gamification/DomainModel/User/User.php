<?php

namespace Gamify\Gamification\DomainModel\User;

use Assert\Assertion;
use Gamify\Gamification\DomainModel\AggregateRoot;

class User extends AggregateRoot
{
    /**
     * @var UserId
     */
    private $id;

    /**
     * @var array
     */
    private $points;

    private function __construct(UserId $userId)
    {
        $this->id = $userId;
        $this->points = [];
    }

    public function id()
    {
        return $this->id;
    }

    public static function signup()
    {
        $user = new User(new UserId());

        $user->publishThat(new UserSignedUp(new UserId()));

        return $user;
    }

    public function earnPoints($numberOfPoints)
    {
        Assertion::integer($numberOfPoints, 'The number of earned points should be a valid integer!');

        $this->publishThat(new UserEarnedPoints($this->id(), $numberOfPoints));
    }

    protected function applyThatUserEarnedPoints(UserEarnedPoints $event)
    {
        foreach (array_fill(0, $event->earnedPoints(), new Point()) as $point) {
            $this->points[] = $point;
        }
    }
}
