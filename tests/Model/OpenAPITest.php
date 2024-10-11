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
use Xenos\OpenApi\Model\Tag;
use Xenos\OpenApi\Model\Tags;
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
            // no tag declared, 0-3 undeclared tags used
            'No tag used, no tag declared' => [
                'expectedResult' => [],
                'openAPI' => new OpenAPI(
                    openapi: Version::make('3.1.0'),
                    info: new Info('Pet Shop API', '1.0.0'),
                ),
            ],
            'One undeclared tag used, no tag declared' => [
                'expectedResult' => ['pet'],
                'openAPI' => new OpenAPI(
                    openapi: Version::make('3.1.0'),
                    info: new Info('Pet Shop API', '1.0.0'),
                    paths: new Paths(
                        [
                            '/pet' => new PathItem(get: new Operation(tags: ['pet'])),
                        ]
                    ),
                ),
            ],
            'Three undeclared tags used, no tag declared' => [
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
            ],

            // three tags declared, 0-3 undeclared tags used
            'No tag used, three tags declared' => [
                'expectedResult' => [],
                'openAPI' => new OpenAPI(
                    openapi: Version::make('3.1.0'),
                    info: new Info('Pet Shop API', '1.0.0'),
                    tags: new Tags(
                        [
                            new Tag(name: 'pet'),
                            new Tag(name: 'store'),
                            new Tag(name: 'user'),
                        ]
                    ),
                ),
            ],
            'One undeclared tag used, three tags declared' => [
                'expectedResult' => ['food'],
                'openAPI' => new OpenAPI(
                    openapi: Version::make('3.1.0'),
                    info: new Info('Pet Shop API', '1.0.0'),
                    paths: new Paths(
                        [
                            '/food' => new PathItem(get: new Operation(tags: ['food'])),
                        ]
                    ),
                    tags: new Tags(
                        [
                            new Tag(name: 'pet'),
                            new Tag(name: 'store'),
                            new Tag(name: 'user'),
                        ]
                    ),
                ),
            ],
            'Three undeclared tags used, three tags declared' => [
                'expectedResult' => ['food', 'doctor', 'health'],
                'openAPI' => new OpenAPI(
                    openapi: Version::make('3.1.0'),
                    info: new Info('Pet Shop API', '1.0.0'),
                    paths: new Paths(
                        [
                            '/food' => new PathItem(get: new Operation(tags: ['food'])),
                            '/doctor' => new PathItem(get: new Operation(tags: ['doctor'])),
                            '/health' => new PathItem(get: new Operation(tags: ['health'])),
                        ]
                    ),
                    tags: new Tags(
                        [
                            new Tag(name: 'pet'),
                            new Tag(name: 'store'),
                            new Tag(name: 'user'),
                        ]
                    ),
                ),
            ],

            // three tags declared, 0-3 declared tags used
            'One declared tag used, three tags declared' => [
                'expectedResult' => ['pet'],
                'openAPI' => new OpenAPI(
                    openapi: Version::make('3.1.0'),
                    info: new Info('Pet Shop API', '1.0.0'),
                    paths: new Paths(
                        [
                            '/pet' => new PathItem(get: new Operation(tags: ['pet'])),
                        ]
                    ),
                    tags: new Tags(
                        [
                            new Tag(name: 'pet'),
                            new Tag(name: 'store'),
                            new Tag(name: 'user'),
                        ]
                    ),
                ),
            ],
            'Three declared tags used, three tags declared' => [
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
                    tags: new Tags(
                        [
                            new Tag(name: 'pet'),
                            new Tag(name: 'store'),
                            new Tag(name: 'user'),
                        ]
                    ),
                ),
            ],

            // sort order
            'Three declared tags used, three tags declared in different order' => [
                'expectedResult' => ['pet', 'store', 'user'],
                'openAPI' => new OpenAPI(
                    openapi: Version::make('3.1.0'),
                    info: new Info('Pet Shop API', '1.0.0'),
                    paths: new Paths(
                        [
                            '/user' => new PathItem(get: new Operation(tags: ['user'])),
                            '/store' => new PathItem(get: new Operation(tags: ['store'])),
                            '/pet' => new PathItem(get: new Operation(tags: ['pet'])),
                        ]
                    ),
                    tags: new Tags(
                        [
                            new Tag(name: 'pet'),
                            new Tag(name: 'store'),
                            new Tag(name: 'user'),
                        ]
                    ),
                ),
            ],
            'One declared tag used, two undeclared tags used, three tags declared' => [
                'expectedResult' => ['pet', 'food', 'health'],
                'openAPI' => new OpenAPI(
                    openapi: Version::make('3.1.0'),
                    info: new Info('Pet Shop API', '1.0.0'),
                    paths: new Paths(
                        [
                            '/pet' => new PathItem(get: new Operation(tags: ['pet'])),
                            '/food' => new PathItem(get: new Operation(tags: ['food'])),
                            '/health' => new PathItem(get: new Operation(tags: ['health'])),
                        ]
                    ),
                    tags: new Tags(
                        [
                            new Tag(name: 'pet'),
                            new Tag(name: 'store'),
                            new Tag(name: 'user'),
                        ]
                    ),
                ),
            ],
            'One declared tag used (in the end), two undeclared tags used, three tags declared' => [
                'expectedResult' => ['pet', 'food', 'health'],
                'openAPI' => new OpenAPI(
                    openapi: Version::make('3.1.0'),
                    info: new Info('Pet Shop API', '1.0.0'),
                    paths: new Paths(
                        [
                            '/food' => new PathItem(get: new Operation(tags: ['food'])),
                            '/health' => new PathItem(get: new Operation(tags: ['health'])),
                            '/pet' => new PathItem(get: new Operation(tags: ['pet'])),
                        ]
                    ),
                    tags: new Tags(
                        [
                            new Tag(name: 'pet'),
                            new Tag(name: 'store'),
                            new Tag(name: 'user'),
                        ]
                    ),
                ),
            ],
            'Two declared tag used, one undeclared tag used, three tags declared' => [
                'expectedResult' => ['pet', 'store', 'health'],
                'openAPI' => new OpenAPI(
                    openapi: Version::make('3.1.0'),
                    info: new Info('Pet Shop API', '1.0.0'),
                    paths: new Paths(
                        [
                            '/pet' => new PathItem(get: new Operation(tags: ['pet'])),
                            '/store' => new PathItem(get: new Operation(tags: ['store'])),
                            '/health' => new PathItem(get: new Operation(tags: ['health'])),
                        ]
                    ),
                    tags: new Tags(
                        [
                            new Tag(name: 'pet'),
                            new Tag(name: 'store'),
                            new Tag(name: 'user'),
                        ]
                    ),
                ),
            ],
            'Two declared tag used (in different order), one undeclared tag used, three tags declared' => [
                'expectedResult' => ['pet', 'store', 'health'],
                'openAPI' => new OpenAPI(
                    openapi: Version::make('3.1.0'),
                    info: new Info('Pet Shop API', '1.0.0'),
                    paths: new Paths(
                        [
                            '/store' => new PathItem(get: new Operation(tags: ['store'])),
                            '/pet' => new PathItem(get: new Operation(tags: ['pet'])),
                            '/health' => new PathItem(get: new Operation(tags: ['health'])),
                        ]
                    ),
                    tags: new Tags(
                        [
                            new Tag(name: 'pet'),
                            new Tag(name: 'store'),
                            new Tag(name: 'user'),
                        ]
                    ),
                ),
            ],
            'Two declared tag used (in the end, in different order), one undeclared tag used, three tags declared' => [
                'expectedResult' => ['pet', 'store', 'health'],
                'openAPI' => new OpenAPI(
                    openapi: Version::make('3.1.0'),
                    info: new Info('Pet Shop API', '1.0.0'),
                    paths: new Paths(
                        [
                            '/health' => new PathItem(get: new Operation(tags: ['health'])),
                            '/store' => new PathItem(get: new Operation(tags: ['store'])),
                            '/pet' => new PathItem(get: new Operation(tags: ['pet'])),
                        ]
                    ),
                    tags: new Tags(
                        [
                            new Tag(name: 'pet'),
                            new Tag(name: 'store'),
                            new Tag(name: 'user'),
                        ]
                    ),
                ),
            ],
        ];
    }
}
