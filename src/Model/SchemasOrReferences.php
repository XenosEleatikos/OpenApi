<?php

declare(strict_types=1);

namespace Xenos\OpenApi\Model;

use ArrayObject;
use JsonSerializable;
use stdClass;

use function array_filter;
use function array_map;

/** @extends ArrayObject<string, Schema|Reference> */
class SchemasOrReferences extends ArrayObject implements JsonSerializable
{
    public static function make(stdClass $schemas): self
    {
        $instance = new self();

        foreach ((array)$schemas as $name => $schemaOrReference) {
            $instance[$name] = Schema::makeSchemaOrReference($schemaOrReference);
        }

        return $instance;
    }

    public function jsonSerialize(): stdClass
    {
        return (object)array_filter(
            array_map(
                fn (Schema|Reference $schema) => $schema->jsonSerialize(),
                $this->getArrayCopy()
            )
        );
    }
}
