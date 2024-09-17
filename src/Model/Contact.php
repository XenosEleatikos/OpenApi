<?php

declare(strict_types=1);

namespace Xenos\OpenApi\Model;

use JsonSerializable;
use stdClass;

use function array_filter;

class Contact implements JsonSerializable
{
    public function __construct(
        public ?string $name,
        public ?string $url,
        public ?string $email = null,
    ) {
    }

    public static function make(stdClass $contact): self
    {
        return new self(
            name: $contact->name ?? null,
            url: $contact->url ?? null,
            email: $contact->email ?? null,
        );
    }

    public function jsonSerialize(): stdClass
    {
        return (object)array_filter((array)$this);
    }
}
