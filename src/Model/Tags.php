<?php

declare(strict_types=1);

namespace Xenos\OpenApi\Model;

use ArrayObject;
use JsonSerializable;
use stdClass;

use function array_map;

/** @extends ArrayObject<int, Tag> */
class Tags extends ArrayObject implements JsonSerializable
{
    /** @param stdClass[] $tags */
    public static function make(array $tags): self
    {
        return new self(array_map(
            fn (stdClass $tag): Tag => Tag::make($tag),
            $tags
        ));
    }

    /** @return array<int, stdClass> */
    public function jsonSerialize(): array
    {
        return array_map(
            fn (Tag $tag) => $tag->jsonSerialize(),
            $this->getArrayCopy()
        );
    }
}
