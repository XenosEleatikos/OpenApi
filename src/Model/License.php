<?php

declare(strict_types=1);

namespace Xenos\OpenApi\Model;

use JsonSerializable;
use stdClass;

class License implements JsonSerializable
{
    public function __construct(
        public string  $name,
        public ?string $identifier = null,
        public ?string $url = null,
    ) {
    }

    public static function make(stdClass $license): self
    {
        return new self(...(array)$license);
    }

    public function jsonSerialize(): stdClass
    {
        return (object)array_filter((array)$this);
    }
}
