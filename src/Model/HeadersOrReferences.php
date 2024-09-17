<?php

declare(strict_types=1);

namespace Xenos\OpenApi\Model;

use ArrayObject;
use JsonSerializable;
use stdClass;

use function array_map;

/** @extends ArrayObject<string, Header|\Xenos\OpenApi\Model\Reference> */
class HeadersOrReferences extends ArrayObject implements JsonSerializable
{
    public static function make(stdClass $headersOrReferences): self
    {
        $instance = new self();

        foreach ((array)$headersOrReferences as $name => $headerOrReference) {
            $instance[$name] = Header::makeHeaderOrReference($headerOrReference);
        }

        return $instance;
    }

    /** @return array<string, stdClass> */
    public function jsonSerialize(): array
    {
        return array_map(
            fn (Header|\Xenos\OpenApi\Model\Reference $header) => $header->jsonSerialize(),
            $this->getArrayCopy()
        );
    }
}
