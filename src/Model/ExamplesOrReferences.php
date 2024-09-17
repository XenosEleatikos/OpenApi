<?php

declare(strict_types=1);

namespace Xenos\OpenApi\Model;

use ArrayObject;
use JsonSerializable;
use stdClass;

use function array_map;

/** @extends ArrayObject<string, Example|\Xenos\OpenApi\Model\Reference> */
class ExamplesOrReferences extends ArrayObject implements JsonSerializable
{
    public static function make(stdClass $examplesOrReferences): self
    {
        $instance = new self();

        foreach ((array)$examplesOrReferences as $example) {
            $instance[] = Example::makeExampleOrReference($example);
        }

        return $instance;
    }

    public function jsonSerialize(): mixed
    {
        return array_map(
            fn (Example|\Xenos\OpenApi\Model\Reference $schema) => $schema->jsonSerialize(),
            $this->getArrayCopy()
        );
    }
}
