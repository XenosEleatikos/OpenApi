<?php

declare(strict_types=1);

namespace Xenos\OpenApi\Model\SecurityScheme\OAuth2;

use JsonSerializable;
use stdClass;

use function array_filter;

class Implicit implements JsonSerializable
{
    public function __construct(
        public string $authorizationUrl,
        /** @var array<string, string> $scopes */
        public array $scopes,
        public ?string $refreshUrl = null,
    ) {
    }

    public static function make(stdClass $implicit): self
    {
        return new self(...array_filter([ // @phpstan-ignore-line
            'authorizationUrl' => $implicit->authorizationUrl,
            'scopes' => isset($implicit->scopes) ? (array)$implicit->scopes : null,
            'refreshUrl' => $implicit->refreshUrl ?? null,
        ]));
    }

    public function jsonSerialize(): stdClass
    {
        return (object)array_filter((array)$this);
    }
}
