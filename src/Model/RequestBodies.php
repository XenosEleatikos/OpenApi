<?php

declare(strict_types=1);

namespace Xenos\OpenApi\Model;

use JsonSerializable;
use stdClass;

use function array_filter;
use function array_map;

/**
 * @extends AbstractComponentsSubList<RequestBody>
 */
class RequestBodies extends AbstractComponentsSubList implements JsonSerializable
{
    public static function make(stdClass $requestBodies): self
    {
        $instance = new self();

        foreach ((array)$requestBodies as $name => $requestBody) {
            $instance[$name] = RequestBody::make($requestBody);
        }

        return $instance;
    }

    public function jsonSerialize(): stdClass
    {
        return (object)array_filter(
            array_map(
                fn (RequestBody $requestBody) => $requestBody->jsonSerialize(),
                $this->getArrayCopy()
            )
        );
    }
}
