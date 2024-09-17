<?php

declare(strict_types=1);

namespace Xenos\OpenApi\Model;

use ArrayObject;
use JsonSerializable;
use stdClass;

use function array_map;

/** @extends ArrayObject<string, PathItem|\Xenos\OpenApi\Model\Reference> */
class Webhooks extends ArrayObject implements JsonSerializable
{
    public static function make(stdClass $webhooks): self
    {
        $instance = new self();

        foreach ((array)$webhooks as $name => $pathItemOrReference) {
            if (isset($pathItemOrReference->{'$ref'})) {
                $instance[$name] = \Xenos\OpenApi\Model\Reference::make($pathItemOrReference);
            } else {
                $instance[$name] = PathItem::make($pathItemOrReference);
            }
        }

        return $instance;
    }

    /** @return array<string, stdClass> */
    public function jsonSerialize(): array
    {
        return array_map(
            fn (PathItem|\Xenos\OpenApi\Model\Reference $pathItemOrReference) => $pathItemOrReference->jsonSerialize(),
            $this->getArrayCopy()
        );
    }
}
