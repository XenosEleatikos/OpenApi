<?php

declare(strict_types=1);

namespace Xenos\OpenApi\Model;

use ArrayObject;
use JsonSerializable;
use stdClass;

use function array_flip;
use function array_key_exists;
use function array_keys;
use function array_map;
use function array_values;
use function usort;

/** @extends ArrayObject<string, Tag> */
class Tags extends ArrayObject implements JsonSerializable
{
    /** @param Tag[] $tags */
    public function __construct(array $tags = [])
    {
        foreach ($tags as $tag) {
            $array[$tag->name] = $tag;
        }

        parent::__construct($array ?? []);
    }

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
            array_values($this->getArrayCopy())
        );
    }

    /** @return string[] */
    public function getTagNames(): array
    {
        return array_keys($this->getArrayCopy());
    }

    /**
     * @param string[] $tagNames
     * @return string[]
     */
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
