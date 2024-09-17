<?php

declare(strict_types=1);

namespace Xenos\OpenApi\Model\SecurityScheme;

use JsonSerializable;

enum SecuritySchemeType: string implements JsonSerializable
{
    case API_KEY = 'apiKey';
    case HTTP = 'http';
    case MUTUAL_TLS = 'mutualTLS';
    case OAUTH2 = 'oauth2';
    case OPEN_ID_CONNECT = 'openIdConnect';

    public function jsonSerialize(): string
    {
        return $this->value;
    }
}
