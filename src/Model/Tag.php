<?php

declare(strict_types=1);

namespace Xenos\OpenApi\Model;

use JsonSerializable;
use stdClass;

class Tag implements JsonSerializable
{
    public function __construct(
        public string                 $name,
        public ?string                $description = null,
        public ?ExternalDocumentation $externalDocs = null,
    ) {
    }

    public static function make(stdClass $tag): self
    {
        return new self(
            name: $tag->name,
            description: $tag->description ?? null,
            externalDocs: isset($tag->externalDocs) ? ExternalDocumentation::make($tag->externalDocs) : null,
        );
    }

    public function jsonSerialize(): stdClass
    {
        return (object)array_filter((array)$this);
    }
}
