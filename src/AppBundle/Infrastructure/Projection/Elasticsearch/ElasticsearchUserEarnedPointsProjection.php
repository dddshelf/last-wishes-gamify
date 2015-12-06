<?php

namespace AppBundle\Infrastructure\Projection\Elasticsearch;

use AppBundle\Infrastructure\Projection\Projection;
use Lw\Gamification\DomainModel\User\UserEarnedPoints;
use ONGR\ElasticsearchBundle\Service\Repository;

class ElasticsearchUserEarnedPointsProjection implements Projection
{
    /**
     * @var Repository
     */
    private $repository;

    public function __construct(Repository $repository)
    {
        $this->repository = $repository;
    }

    public function eventType()
    {
        return UserEarnedPoints::class;
    }

    public function project($event)
    {
        $user = $this->repository->find($event->userId());

        $this->repository->update($event->userId(), ['points' => $user->getPoints() + $event->earnedPoints()]);
    }
}