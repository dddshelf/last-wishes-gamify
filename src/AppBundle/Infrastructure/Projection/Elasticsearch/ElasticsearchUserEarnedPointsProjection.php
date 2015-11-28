<?php

namespace AppBundle\Infrastructure\Projection\Elasticsearch;

use Gamify\Gamification\DomainModel\User\UserEarnedPoints;

class ElasticsearchUserEarnedPointsProjection extends BaseProjection
{
    public function eventType()
    {
        return UserEarnedPoints::class;
    }

    public function project($event)
    {
        $this->update(
            $event->userId(),
            [
                'script' => 'ctx._source.points += points',
                'params' => [
                    'points' => $event->earnedPoints()
                ]
            ]
        );
    }
}