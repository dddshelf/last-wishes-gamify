<?php

namespace Gamify\Gamification\DomainModel\User;

use Assert\Assertion;
use Gamify\Gamification\DomainModel\AggregateRoot;
use Gamify\Gamification\DomainModel\EventSourcedAggregateRoot;
use Gamify\Gamification\DomainModel\EventStream;

class User extends AggregateRoot implements EventSourcedAggregateRoot
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

    public static function signup(UserId $userId)
    {
        $user = new User($userId);

        $user->publishThat(new UserSignedUp($userId));

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

    public static function reconstitute(EventStream $events)
    {
        $user = new static($events->aggregateId());

        foreach ($events as $event) {
            $user->apply($event);
        }

        return $user;
    }
}
