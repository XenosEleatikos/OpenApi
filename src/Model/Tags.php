<?php

declare(strict_types=1);

namespace Xenos\OpenApi\Model;

use ArrayObject;
use JsonSerializable;
use stdClass;

use function array_flip;
use function array_key_exists;
use function array_map;
use function usort;

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

    /** @return string[] */
    public function getTagNames(): array
    {
        return array_map(
            fn (Tag $tag) => $tag->name,
            $this->getArrayCopy()
        );
    }

    public function sortTags(array $tagNames): array
    {
        $order = array_flip($this->getTagNames());

        usort(
            $tagNames,
            function (string $a, string $b) use ($order): int {
                $aInOrder = array_key_exists($a, $order);
                $bInOrder = array_key_exists($b, $order);

                if ($aInOrder && $bInOrder) {
                    return $order[$a] <=> $order[$b];
                }

                if ($aInOrder) {
                    return -1;
                }

                if ($bInOrder) {
                    return 1;
                }

                return 0;
            }
        );

        return $tagNames;
    }
}
