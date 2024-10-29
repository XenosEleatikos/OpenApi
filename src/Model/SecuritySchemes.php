<?php

declare(strict_types=1);

namespace Xenos\OpenApi\Model;

use JsonSerializable;
use stdClass;

use function array_filter;
use function array_map;

/**
 * @extends AbstractComponentsSubList<SecurityScheme>
 */
class SecuritySchemes extends AbstractComponentsSubList implements JsonSerializable
{
    public static function make(stdClass $securitySchemes): self
    {
        $instance = new self();

        foreach ((array)$securitySchemes as $name => $securityScheme) {
            $instance[$name] = SecurityScheme::make($securityScheme);
        }

        return $instance;
    }

    public function jsonSerialize(): stdClass
    {
        return (object)array_filter(
            array_map(
                fn (SecurityScheme $securityScheme) => $securityScheme->jsonSerialize(),
                $this->getArrayCopy()
            )
        );
    }
}
