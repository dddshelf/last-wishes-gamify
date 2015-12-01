<?php

namespace AppBundle\Infrastructure\Persistence\EventStore;

use AppBundle\Infrastructure\Projection\Projector;
use Gamify\Gamification\DomainModel\EventStore;
use Gamify\Gamification\DomainModel\EventStream;
use Gamify\Gamification\DomainModel\User\User;
use Gamify\Gamification\DomainModel\User\UserId;
use Gamify\Gamification\DomainModel\User\UserRepository;

class EventStoreUserRepository implements UserRepository
{
    private $eventstore;
    private $projector;

    public function __construct(EventStore $eventstore, Projector $projector)
    {
        $this->eventstore = $eventstore;
        $this->projector = $projector;
    }

    public function save(User $user)
    {
        $events = $user->uncommitedEvents();

        $this->eventstore->append(new EventStream($user->id(), $events));
        $user->clearEvents();

        $this->projector->project($events);
    }

    public function byId(UserId $id)
    {
        return User::reconstitute($this->eventstore->getEventsFor($id));
    }

    /**
     * Generates a new UserId
     *
     * @return UserId
     */
    public function nextIdentity()
    {
        return new UserId();
    }
}