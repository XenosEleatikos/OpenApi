<?php

declare(strict_types=1);

namespace Xenos\OpenApi\Model;

use ArrayObject;
use JsonSerializable;
use stdClass;
use Xenos\OpenApi\Model\Reference;

use function array_map;

/** @extends ArrayObject<string, RequestBody|\Xenos\OpenApi\Model\Reference> */
class RequestBodiesOrReferences extends ArrayObject implements JsonSerializable
{
    public static function make(stdClass $schemas): self
    {
        $instance = new self();

        foreach ((array)$schemas as $name => $requestBody) {
            $instance[$name] = RequestBody::makeRequestBodyOrReference($requestBody);
        }

        return $instance;
    }

    public static function makeRequestBodyOrReference(stdClass $requestBodyOrReference): self|Reference
    {
        if (isset($requestBodyOrReference->{'$ref'})) {
            return Reference::make($requestBodyOrReference);
        } else {
            return self::make($requestBodyOrReference);
        }
    }

    /** @return array<string, stdClass> */
    public function jsonSerialize(): array
    {
        return array_map(
            fn (RequestBody|Reference $requestBody) => $requestBody->jsonSerialize(),
            $this->getArrayCopy()
        );
    }
}
