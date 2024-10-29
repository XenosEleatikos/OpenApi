<?php

declare(strict_types=1);

namespace Xenos\OpenApi\Model;

use ArrayObject;
use InvalidArgumentException;

use function preg_match;
use function var_export;

/**
 * @template TValue of mixed
 * @extends ArrayObject<string, TValue>
 */
class AbstractComponentsSubList extends ArrayObject
{
    public const string KEY_PATTERN = '/^[a-zA-Z0-9._-]+$/';

    public function offsetSet($key, $value): void
    {
        if (empty($key) || preg_match(pattern: self::KEY_PATTERN, subject: $key) === 0) {
            throw new InvalidArgumentException('Array key must be a string matching the regular expression "' . self::KEY_PATTERN . '", ' . var_export($key, true) . ' given.');
        }

        parent::offsetSet($key, $value);
    }
}
