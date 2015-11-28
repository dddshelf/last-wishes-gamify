<?php

namespace Gamify\Gamification\DomainModel;

use Ddd\Domain\DomainEvent;
use Ddd\Domain\DomainEventPublisher;

abstract class AggregateRoot
{
    /**
     * @var array
     */
    private $uncommitedEvents = [];

    private function notify(DomainEvent $event)
    {
        DomainEventPublisher::instance()->publish($event);
    }

    private function record(DomainEvent $event)
    {
        $this->uncommitedEvents[] = $event;
    }

    private function apply(DomainEvent $event)
    {
        $modifier = 'applyThat' . array_reverse(explode('\\', get_class($event)))[0];

        if (!method_exists($this, $modifier)) {
            return;
        }

        $this->$modifier($event);
    }

    protected function publishThat(DomainEvent $event)
    {
        $this->apply($event);
        $this->record($event);
        $this->notify($event);
    }

    public function uncommitedEvents()
    {
        return $this->uncommitedEvents;
    }

    public function clearEvents()
    {
        $this->uncommitedEvents = [];
    }
}
