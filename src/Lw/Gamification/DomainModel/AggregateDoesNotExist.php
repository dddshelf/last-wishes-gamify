<?php

namespace Lw\Gamification\DomainModel;

use RuntimeException;

class AggregateDoesNotExist extends RuntimeException
{
    public function __construct($aggregateId)
    {
        parent::__construct(sprintf('Aggregate with ID of "%s" does not exist!', $aggregateId));
    }
}
