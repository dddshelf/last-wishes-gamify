<?php

namespace AppBundle\Infrastructure\Projection;

class Projector
{
    /**
     * @var array
     */
    private $projections = [];

    public function register(array $projections)
    {
        foreach ($projections as $projection) {
            $this->projections[$projection->eventType()] = $projection;
        }
    }

    public function project(array $events)
    {
        foreach ($events as $event) {
            if (isset($this->projections[get_class($event)])) {
                $this->projections[get_class($event)]->project($event);
            }
        }
    }
}