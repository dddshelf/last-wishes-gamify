<?php

namespace AppBundle\Document;

use Hateoas\Configuration\Annotation as Hateoas;
use ONGR\ElasticsearchBundle\Annotation as ES;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ES\Document(type="user")
 * @Serializer\XmlRoot("user")
 * @Serializer\ExclusionPolicy("all")
 * @Serializer\AccessType("public_method")
 * @Hateoas\Relation("self", href = @Hateoas\Route("get_user", parameters={"id" = "expr(object.getId())"}, absolute = true))
 */
class User
{
    /**
     * @var integer
     *
     * @ES\Property(type="integer", name="points")
     * @Serializer\Expose()
     * @Serializer\Type("integer")
     */
    private $points;

    public function getPoints()
    {
        return $this->points;
    }

    public function setPoints($points)
    {
        $this->points = $points;
    }
}