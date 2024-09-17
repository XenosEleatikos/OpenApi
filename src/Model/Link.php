<?php

declare(strict_types=1);

namespace Xenos\OpenApi\Model;

use JsonSerializable;
use stdClass;
use Xenos\OpenApi\Model\Reference;

class Link implements JsonSerializable
{
    // @todo Optimize with phpstan
    public function __construct(// @phpstan-ignore-line
        public string  $operationRef,
        public ?string $operationId = null,
        public array $parameters = [],
        public mixed $requestBody = null,
        public ?string $description = null,
        public ?Server $body = null,
    ) {
    }

    public static function make(stdClass $license): self
    {
        return new self(...(array)$license);
    }

    public static function makeLinkOrReference(stdClass $linkOrReference): self|Reference
    {
        if (isset($linkOrReference->{'$ref'})) {
            return \Xenos\OpenApi\Model\Reference::make($linkOrReference);
        } else {
            return self::make($linkOrReference);
        }
    }

    public function jsonSerialize(): stdClass
    {
        return (object)array_filter((array)$this);
    }
}
