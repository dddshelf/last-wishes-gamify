<?php

namespace AppBundle\Infrastructure\Projection;

interface Projection
{
    public function eventType();
    public function project($event);
}