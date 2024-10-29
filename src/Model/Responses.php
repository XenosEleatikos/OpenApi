<?php

declare(strict_types=1);

namespace Xenos\OpenApi\Model;

use JsonSerializable;
use stdClass;

use function array_filter;
use function array_map;

/**
 * @extends AbstractComponentsSubList<Response>
 */
class Responses extends AbstractComponentsSubList implements JsonSerializable
{
    public static function make(stdClass $responses): self
    {
        $instance = new self();

        foreach ((array)$responses as $name => $response) {
            $instance[$name] = Response::make($response);
        }

        return $instance;
    }

    public function jsonSerialize(): stdClass
    {
        return (object)array_filter(
            array_map(
                fn (Response $response) => $response->jsonSerialize(),
                $this->getArrayCopy()
            )
        );
    }
}
