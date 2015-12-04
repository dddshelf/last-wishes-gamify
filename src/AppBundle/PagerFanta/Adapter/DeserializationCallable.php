<?php

namespace AppBundle\PagerFanta\Adapter;

use JMS\Serializer\Serializer;

class DeserializationCallable
{
    /**
     * @var Serializer
     */
    private $serializer;

    public function __construct(Serializer $serializer)
    {
        $this->serializer = $serializer;
    }

    public function __invoke($rawListElement)
    {
        return $this->serializer->deserialize($rawListElement, 'array', 'json');
    }
}