<?php

declare(strict_types=1);

namespace Xenos\OpenApi\Model;

use JsonSerializable;
use stdClass;

use function array_filter;
use function array_map;

/**
 * @extends AbstractComponentsSubList<Header>
 */
class Headers extends AbstractComponentsSubList implements JsonSerializable
{
    public static function make(stdClass $headers): self
    {
        $instance = new self();

        foreach ((array)$headers as $name => $header) {
            $instance[$name] = Header::make($header);
        }

        return $instance;
    }

    public function jsonSerialize(): stdClass
    {
        return (object)array_filter(
            array_map(
                fn (Header $header) => $header->jsonSerialize(),
                $this->getArrayCopy()
            )
        );
    }
}
