<?php

namespace AppBundle\PagerFanta\Adapter;

use InvalidArgumentException;
use Pagerfanta\Adapter\AdapterInterface;
use function Functional\map;

class RedisListAdapter implements AdapterInterface
{
    private $redis;
    private $key;
    private $deserializationCallback;

    public function __construct($key, $redis, callable $deserializationCallback)
    {
        $this->deserializationCallback = $deserializationCallback;
        $this->redis = $redis;

        $this->assertKeyExists($key);
        $this->key = $key;
    }

    /**
     * Returns the number of results.
     *
     * @return integer The number of results.
     */
    public function getNbResults()
    {
        $this->redis->llen($this->key);
    }

    /**
     * Returns an slice of the results.
     *
     * @param integer $offset The offset.
     * @param integer $length The length.
     *
     * @return array|\Traversable The slice.
     */
    public function getSlice($offset, $length)
    {
        return map($this->redis->lrange($this->key, $offset, ($offset + $length)), $this->deserializationCallback);
    }

    private function assertKeyExists($key)
    {
        if (!$this->redis->exists($key)) {
            throw new InvalidArgumentException(sprintf('The "%s" key does not exist!'));
        }
    }
}