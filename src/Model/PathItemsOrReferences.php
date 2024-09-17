<?php

declare(strict_types=1);

namespace Xenos\OpenApi\Model;

use ArrayObject;
use JsonSerializable;
use stdClass;
use Xenos\OpenApi\Model\Reference;

use function array_map;

/** @extends ArrayObject<string, PathItem|Reference> */
class PathItemsOrReferences extends ArrayObject implements JsonSerializable
{
    public static function make(stdClass $schemas): self
    {
        $instance = new self();

        foreach ((array)$schemas as $name => $pathItem) {
            $instance[$name] = PathItem::makePathItemOrReference($pathItem);
        }

        return $instance;
    }

    /** @return array<string, object> */
    public function jsonSerialize(): array
    {
        return array_map(
            fn (PathItem|Reference $pathItem) => $pathItem->jsonSerialize(),
            $this->getArrayCopy()
        );
    }
}
