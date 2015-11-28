<?php

namespace Gamify\Gamification\DomainModel;

interface EventSourcedAggregateRoot
{
    public static function reconstitute(EventStream $events);
}