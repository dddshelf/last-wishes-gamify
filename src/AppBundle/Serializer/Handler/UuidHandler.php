<?php

namespace AppBundle\Serializer\Handler;

use JMS\Serializer\Context;
use JMS\Serializer\GraphNavigator;
use JMS\Serializer\Handler\SubscribingHandlerInterface;
use JMS\Serializer\JsonDeserializationVisitor;
use JMS\Serializer\VisitorInterface;
use JMS\Serializer\XmlSerializationVisitor;
use Ramsey\Uuid\Uuid;

class UuidHandler implements SubscribingHandlerInterface
{
    /**
     * Return format:
     *
     *      array(
     *          array(
     *              'direction' => GraphNavigator::DIRECTION_SERIALIZATION,
     *              'format' => 'json',
     *              'type' => 'DateTime',
     *              'method' => 'serializeDateTimeToJson',
     *          ),
     *      )
     *
     * The direction and method keys can be omitted.
     *
     * @return array
     */
    public static function getSubscribingMethods()
    {
        return [
            [
                'direction' => GraphNavigator::DIRECTION_SERIALIZATION,
                'format' => 'xml',
                'type' => Uuid::class,
                'method' => 'serializeUuid'
            ],
            [
                'direction' => GraphNavigator::DIRECTION_SERIALIZATION,
                'format' => 'json',
                'type' => Uuid::class,
                'method' => 'serializeUuid'
            ],
            [
                'direction' => GraphNavigator::DIRECTION_DESERIALIZATION,
                'format' => 'xml',
                'type' => Uuid::class,
            ],
            [
                'direction' => GraphNavigator::DIRECTION_DESERIALIZATION,
                'format' => 'json',
                'type' => Uuid::class,
            ],
        ];
    }

    public function serializeUuid(VisitorInterface $visitor, Uuid $uuid, array $type, Context $context)
    {
        return $visitor->visitString($uuid->toString(), $type, $context);
    }

    public function deserializeUuidFromXml(XmlSerializationVisitor $visitor, $data, array $type)
    {
        $attributes = $data->attributes('xsi', true);

        if (isset($attributes['nil'][0]) && (string) $attributes['nil'][0] === 'true') {
            return null;
        }

        return $this->toUuid($data);
    }

    public function deserializeUuidFromJson(JsonDeserializationVisitor $visitor, $data, array $type)
    {
        if (null === $data) {
            return null;
        }

        return $this->toUuid($data);
    }

    private function toUuid($data)
    {
        return Uuid::fromString((string) $data);
    }
}