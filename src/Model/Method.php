<?php

declare(strict_types=1);

namespace Xenos\OpenApi\Model;

use function strtolower;

enum Method: string
{
    case GET = 'GET';
    case PUT = 'PUT';
    case POST = 'POST';
    case DELETE = 'DELETE';
    case OPTIONS = 'OPTIONS';
    case HEAD = 'HEAD';
    case PATCH = 'PATCH';
    case TRACE = 'TRACE';

    public function lowerCase(): string
    {
        return strtolower($this->value);
    }
}
