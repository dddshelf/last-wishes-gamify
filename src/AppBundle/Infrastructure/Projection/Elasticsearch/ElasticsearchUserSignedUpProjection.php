<?php

namespace AppBundle\Infrastructure\Projection\Elasticsearch;

use AppBundle\Infrastructure\Projection\Projection;
use Gamify\Gamification\DomainModel\User\UserSignedUp;

class ElasticsearchUserSignedUpProjection extends BaseProjection
{
    public function eventType()
    {
        return UserSignedUp::class;
    }

    public function project($event)
    {
        $this->index($event->userId(), ['points' => 0]);
    }
}