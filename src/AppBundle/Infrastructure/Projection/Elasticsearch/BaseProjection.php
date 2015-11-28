<?php

namespace AppBundle\Infrastructure\Projection\Elasticsearch;

use AppBundle\Infrastructure\Projection\Projection;
use Elasticsearch\Client;

abstract class BaseProjection implements Projection
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @var string
     */
    private $index;

    /**
     * @var string
     */
    private $type;

    public function __construct($index, $type, Client $client)
    {
        $this->client = $client;
        $this->index = $index;
        $this->type = $type;
    }

    protected function index($id, array $document)
    {
        $this->client->index([
            'index' => $this->index,
            'type'  => $this->type,
            'id'    => $id,
            'body'  => $document
        ]);
    }

    protected function update($id, array $document)
    {
        $this->client->update([
            'index' => $this->index,
            'type'  => $this->type,
            'id'    => $id,
            'body'  => $document
        ]);
    }
}
