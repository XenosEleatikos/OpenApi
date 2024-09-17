<?php

declare(strict_types=1);

namespace Xenos\OpenApiTest\Model;

use PHPUnit\Framework\Attributes\TestWith;
use PHPUnit\Framework\TestCase;
use Xenos\OpenApi\Model\OpenAPI;

use function file_get_contents;
use function json_decode;
use function json_encode;

class OpenAPITest extends TestCase
{
    #[TestWith([__DIR__ . '/fixtures/non-oauth-scopes.json'])]
    #[TestWith([__DIR__ . '/fixtures/webhook-example.json'])]
    #[TestWith([__DIR__ . '/fixtures/openapi3_1.json'])]
    public function testSerialize(string $specificationJson): void
    {
        $specification = json_decode(file_get_contents($specificationJson));
        $specification = OpenAPI::make($specification);

        self::assertJsonStringEqualsJsonFile(
            $specificationJson,
            json_encode($specification)
        );
    }
}
