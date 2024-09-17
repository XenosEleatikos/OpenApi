<?php

declare(strict_types=1);

namespace Xenos\OpenApi\Model;

use ArrayObject;
use JsonSerializable;
use stdClass;
use Xenos\OpenApi\Model\Reference;

use function array_filter;
use function array_map;

/** @extends ArrayObject<string, Link|Reference> */
class LinksOrReferences extends ArrayObject implements JsonSerializable
{
    public static function make(stdClass $links): self
    {
        $instance = new self();

        foreach ((array)$links as $name => $linkOrReference) {
            $instance[$name] = Link::makeLinkOrReference($linkOrReference);
        }

        return $instance;
    }

    public function jsonSerialize(): stdClass
    {
        return (object)array_filter(
            array_map(
                fn (Link|\Xenos\OpenApi\Model\Reference $link) => $link->jsonSerialize(),
                $this->getArrayCopy()
            )
        );
    }
}
