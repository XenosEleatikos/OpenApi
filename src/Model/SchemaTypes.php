<?php

declare(strict_types=1);

namespace Xenos\OpenApi\Model;

use ArrayObject;
use JsonSerializable;

use function array_map;
use function is_string;

/** @extends ArrayObject<int, SchemaType> */
class SchemaTypes extends ArrayObject implements JsonSerializable
{
    /** @param string|string[] $schemaTypes */
    public static function make(string|array $schemaTypes): self
    {
        if (is_string($schemaTypes)) {
            $schemaTypes = [$schemaTypes];
        }

        return new self(
            array_map(
                fn (string $schemaType): SchemaType => SchemaType::from($schemaType),
                $schemaTypes
            )
        );
    }

    public function contains(SchemaType $schemaType): bool
    {
        foreach ($this as $existingSchemaType) {
            if ($existingSchemaType->equals($schemaType)) {
                return true;
            }
        }

        return false;
    }

    /** @return string|string[] */
    public function jsonSerialize(): string|array
    {
        return $this->count() === 1
            ? $this[0]->value // @phpstan-ignore-line
            : array_map(
                fn (SchemaType $schemaType): string => $schemaType->value,
                $this->getArrayCopy()
            );
    }
}
