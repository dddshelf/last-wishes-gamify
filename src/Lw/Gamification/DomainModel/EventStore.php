<?php

namespace Lw\Gamification\DomainModel;

interface EventStore
{
    public function append(EventStream $events);
    public function getEventsFor($id);
}