<?php

namespace Lw\Gamification\DomainModel;

interface EventSourcedAggregateRoot
{
    public static function reconstitute(EventStream $events);
}