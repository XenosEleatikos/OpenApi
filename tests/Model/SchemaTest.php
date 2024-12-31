<?php

declare(strict_types=1);

namespace Model;

use PHPUnit\Framework\TestCase;
use Xenos\OpenApi\Model\Schema;
use Xenos\OpenApi\Model\SchemaType;
use Xenos\OpenApi\Model\SchemaTypes;

class SchemaTest extends TestCase
{
    public function testAdditionalPropertiesDefaultValueIsNotSerialized(): void
    {
        $schema = new Schema();
        $result = $schema->jsonSerialize();
        self::assertObjectNotHasProperty('additionalProperties', $result);
    }

    public function testAdditionalPropertiesFalseValueIsSerialized(): void
    {
        $schema = new Schema(additionalProperties: false);
        $result = $schema->jsonSerialize();
        self::assertObjectHasProperty(propertyName: 'additionalProperties', object: $result);
        self::assertFalse(condition:$result->additionalProperties);
    }

    public function testAdditionalPropertiesSchemaIsSerialized(): void
    {
        $schema = new Schema(additionalProperties: new Schema(type: new SchemaTypes([SchemaType::STRING])));
        $result = $schema->jsonSerialize();
        self::assertObjectHasProperty(propertyName: 'additionalProperties', object: $result);
        self::assertObjectHasProperty(propertyName: 'type', object: $result->additionalProperties);
        self::assertSame(expected: 'string', actual: $result->additionalProperties->type);
    }
}
