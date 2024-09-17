<?php

declare(strict_types=1);

namespace Xenos\OpenApi\Model;

use ArrayObject;
use JsonSerializable;
use stdClass;
use Xenos\OpenApi\Model\Reference;

use function array_filter;
use function array_map;

/** @extends ArrayObject<string, SecurityScheme|Reference> */
class SecuritySchemesOrReferences extends ArrayObject implements JsonSerializable
{
    public static function make(stdClass $securitySchemes): self
    {
        $instance = new self();

        foreach ((array)$securitySchemes as $name => $securitySchemeOrReference) {
            $instance[$name] = SecurityScheme::makeSecuritySchemeOrReference($securitySchemeOrReference);
        }

        return $instance;
    }

    public function jsonSerialize(): stdClass
    {
        return (object)array_filter(
            array_map(
                fn (SecurityScheme|Reference $securityScheme) => $securityScheme->jsonSerialize(),
                $this->getArrayCopy()
            )
        );
    }
}
