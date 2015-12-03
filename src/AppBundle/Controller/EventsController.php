<?php

namespace AppBundle\Controller;

use DateTime;
use FOS\RestBundle\Controller\FOSRestController;
use Hateoas\Representation\CollectionRepresentation;
use Hateoas\Representation\PaginatedRepresentation;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;
use function Functional\select;

class EventsController extends FOSRestController
{
    /**
     * Gets a list of all the published events
     *
     * @ApiDoc(
     *  resource = true,
     *  description = "Gets a list of all the published events, optionally filtered by a given date",
     *  filters = {
     *      {"name" = "since", "dataType" = "integer"},
     *      {"name" = "page", "dataType" = "integer"},
     *      {"name" = "all", "dataType" = "boolean"},
     *  },
     *  output = "Hateoas\Representation\CollectionRepresentation",
     *  statusCodes = {
     *      200 = "Returned along with the list of published events"
     *  }
     * )
     */
    public function getEventsAction(Request $request)
    {
        $page = $request->query->get('page', 1);
        $limit = $request->query->get('limit', (int) $this->getParameter('events_pagination_limit'));

        $start = ($page - 1) * $limit;
        $stop  = $request->query->has('all') ? -1 : $start + ($limit - 1);

        $rawPublishedEvents = $this->get('snc_redis.default')->lrange('published_events', $start, $stop);

        $events = [];

        foreach ($rawPublishedEvents as $rawPublishedEvent) {
            $events[] = $this->get('serializer')->deserialize($rawPublishedEvent, 'array', 'json');
        }

        if ($request->query->has('since')) {
            $since = $request->query->get('since');
            $events = select($events, function($event) use ($since) {
                return (int) $event['created_on'] >= (int) $since;
            });
        }

        return new PaginatedRepresentation(
            new CollectionRepresentation($events, 'events', 'events'),
            'get_events',
            [],
            $page,
            $limit,
            null,
            null,
            null,
            true
        );
    }
}