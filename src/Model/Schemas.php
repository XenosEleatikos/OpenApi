<?php

declare(strict_types=1);

namespace Xenos\OpenApi\Model;

use JsonSerializable;
use stdClass;

use function array_filter;
use function array_map;

/**
 * @extends AbstractComponentsSubList<Schema>
 */
class Schemas extends AbstractComponentsSubList implements JsonSerializable
{
    public static function make(stdClass $schemas): self
    {
        $instance = new self();

        foreach ((array)$schemas as $name => $schema) {
            $instance[$name] = Schema::make($schema);
        }

        return $instance;
    }

    public function jsonSerialize(): stdClass
    {
        return (object)array_filter(
            array_map(
                fn (Schema $schema) => $schema->jsonSerialize(),
                $this->getArrayCopy()
            )
        );
    }
}
