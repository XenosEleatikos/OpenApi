<?php

declare(strict_types=1);

namespace Xenos\OpenApiTest\Model;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestWith;
use PHPUnit\Framework\TestCase;
use Xenos\OpenApi\Model\Info;
use Xenos\OpenApi\Model\OpenAPI;
use Xenos\OpenApi\Model\Operation;
use Xenos\OpenApi\Model\PathItem;
use Xenos\OpenApi\Model\Paths;
use Xenos\OpenApi\Model\Version;

use function array_values;
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

    #[DataProvider('provideDataForTestFindUsedTags')]
    public function testFindUsedTags(
        array $expectedResult,
        OpenAPI $openAPI
    ): void {
        self::assertEqualsCanonicalizing(
            expected: array_values($expectedResult),
            actual: array_values($openAPI->findUsedTags()),
            message: 'The array does not contain the expected tags.'
        );
        self::assertSame(
            expected: array_values($expectedResult),
            actual: array_values($openAPI->findUsedTags()),
            message: 'The array is not sorted correctly.'
        );
        self::assertSame(
            expected: $expectedResult,
            actual: $openAPI->findUsedTags(),
            message: 'The array does not have numeric contiguous keys starting from 0.'
        );
    }

    public static function provideDataForTestFindUsedTags(): array
    {
        return [
            'Three tags used' => [
                'expectedResult' => ['pet', 'store', 'user'],
                'openAPI' => new OpenAPI(
                    openapi: Version::make('3.1.0'),
                    info: new Info('Pet Shop API', '1.0.0'),
                    paths: new Paths(
                        [
                            '/pet' => new PathItem(get: new Operation(tags: ['pet'])),
                            '/store' => new PathItem(get: new Operation(tags: ['store'])),
                            '/user' => new PathItem(get: new Operation(tags: ['user'])),
                        ]
                    ),
                ),
            ]
        ];
    }
}
