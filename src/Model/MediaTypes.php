<?php

declare(strict_types=1);

namespace Xenos\OpenApi\Model;

use ArrayObject;
use JsonSerializable;
use stdClass;
use Xenos\OpenApi\Model\Reference;

use function array_map;

/** @extends ArrayObject<string, MediaType|Reference> */
class MediaTypes extends ArrayObject implements JsonSerializable
{
    public static function make(stdClass $schemas): self
    {
        $instance = new self();

        foreach ((array)$schemas as $name => $mediaType) {
            $instance[$name] = MediaType::make($mediaType);
        }

        return $instance;
    }

    /** @return array<string, stdClass> */
    public function jsonSerialize(): array
    {
        return array_map(
            fn (MediaType|Reference $mediaType) => $mediaType->jsonSerialize(),
            $this->getArrayCopy()
        );
    }
}
