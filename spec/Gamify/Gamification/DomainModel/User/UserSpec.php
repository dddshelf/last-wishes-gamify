<?php

namespace spec\Gamify\Gamification\DomainModel\User;

use Ddd\Domain\DomainEventPublisher;
use Ddd\Domain\DomainEventSubscriber;
use Gamify\Gamification\DomainModel\User\UserEarnedPoints;
use Gamify\Gamification\DomainModel\User\UserSignedUp;
use Gamify\Gamification\DomainModel\User\UserId;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Ramsey\Uuid\Uuid;

class UserSpec extends ObjectBehavior
{
    public function let()
    {
        DomainEventPublisher::instance()->subscribe(new AllEventsSubscriber());

        $this->beConstructedThrough('signup', [new UserId()]);
    }

    public function letGo()
    {
        static $id = 0;

        AllEventsSubscriber::reset();

        DomainEventPublisher::instance()->unsubscribe($id++);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Gamify\Gamification\DomainModel\User\User');
    }

    public function it_should_be_constructed_with_a_user_id()
    {
        $this->shouldHaveAValidUserId();
    }

    public function it_should_record_that_a_new_user_has_signed_up()
    {
        $this->shouldHavePublished(UserSignedUp::class);
    }

    public function it_can_earn_points()
    {
        $this->earnPoints(3);
        $this->earnPoints(2);

        $this->shouldHavePoints(5);
    }

    public function getMatchers()
    {
        return [
            'havePublished' => function ($user, $eventClass) {
                $publishedEvents = count(
                    array_filter(
                        AllEventsSubscriber::publishedEvents(),
                        function ($event) use ($eventClass) {
                            return $eventClass === get_class($event);
                        }
                    )
                );

                $recordedEvents = count(
                    array_filter(
                        $user->uncommitedEvents(),
                        function ($event) use ($eventClass) {
                            return $eventClass === get_class($event);
                        }
                    )
                );

                return $publishedEvents > 0 && $recordedEvents > 0;
            },
            'haveAValidUserId' => function ($user) {
                return
                    $user->id() instanceof UserId
                    && Uuid::isValid($user->id())
                ;
            },
            'havePoints' => function ($user, $expectedNumberOfPoints) {
                return $expectedNumberOfPoints === array_reduce(
                    AllEventsSubscriber::publishedEvents(),
                    function($total, $event) {
                        if ($event instanceof UserEarnedPoints) {
                            $total += $event->earnedPoints();
                        }

                        return $total;
                    },
                    0
                );
            }
        ];
    }
}

class AllEventsSubscriber implements DomainEventSubscriber
{
    private static $publishedEvents = [];

    public function handle($aDomainEvent)
    {
        static::$publishedEvents[] = $aDomainEvent;
    }

    public function isSubscribedTo($aDomainEvent)
    {
        return true;
    }

    public static function publishedEvents()
    {
        return self::$publishedEvents;
    }

    public static function reset()
    {
        static::$publishedEvents = [];
    }
}