<?php

namespace AppBundle\Infrastructure\Projection\Elasticsearch;

use AppBundle\Document\User;
use AppBundle\Infrastructure\Projection\Projection;
use Lw\Gamification\DomainModel\User\UserSignedUp;
use ONGR\ElasticsearchBundle\Service\Manager;

class ElasticsearchUserSignedUpProjection implements Projection
{
    /**
     * @var Manager
     */
    private $manager;

    public function __construct(Manager $manager)
    {
        $this->manager = $manager;
    }

    public function eventType()
    {
        return UserSignedUp::class;
    }

    public function project($event)
    {
        $userDocument = new User();
        $userDocument->setId("{$event->userId()}");
        $userDocument->setPoints(0);

        $this->manager->persist($userDocument);
        $this->manager->commit();
    }
}