<?php

namespace AppBundle\Controller;

use AppBundle\PagerFanta\Adapter\DeserializationCallable;
use FOS\RestBundle\Controller\FOSRestController;
use Hateoas\Configuration\Route;
use Hateoas\Representation\Factory\PagerfantaFactory;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Component\HttpFoundation\Request;
use function Functional\select;
use function Functional\map;

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
     *      {"name" = "limit", "dataType" = "integer"},
     *  },
     *  output = "Hateoas\Representation\CollectionRepresentation",
     *  statusCodes = {
     *      200 = "Returned along with the list of published events"
     *  }
     * )
     */
    public function getEventsAction(Request $request)
    {
        $page  = $request->query->get('page', 1);
        $limit = $request->query->get('limit', (int) $this->getParameter('events_pagination_limit'));

        $pager = $this->get('published_events_pager');
        $pager->setCurrentPage($page);
        $pager->setMaxPerPage($limit);

        if ($request->query->has('since')) {
            $since = $request->query->get('since');

            $events = select(
                map(
                    $this->get('snc_redis.default')->lrange($this->getParameter('published_events_key'), 0, -1),
                    new DeserializationCallable($this->get('jms_serializer'))
                ),
                function($event) use ($since) {
                    return (int) $event['created_on'] >= (int) $since;
                }
            );

            $pager = (new Pagerfanta(new ArrayAdapter($events)))->setCurrentPage($page)->setMaxPerPage($limit);
        }

        return (new PagerfantaFactory())->createRepresentation($pager, new Route('get_events', [], true));
    }
}