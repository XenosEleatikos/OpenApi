<?php

declare(strict_types=1);

namespace Xenos\OpenApi\Model;

use JsonSerializable;
use stdClass;

use function array_filter;
use function array_map;

/**
 * @extends AbstractComponentsSubList<Parameter>
 */
class Parameters extends AbstractComponentsSubList implements JsonSerializable
{
    public static function make(stdClass $parameters): self
    {
        $instance = new self();

        foreach ((array)$parameters as $name => $parameter) {
            $instance[$name] = Parameter::make($parameter);
        }

        return $instance;
    }

    public function jsonSerialize(): stdClass
    {
        return (object)array_filter(
            array_map(
                fn (Parameter $parameter) => $parameter->jsonSerialize(),
                $this->getArrayCopy()
            )
        );
    }
}
