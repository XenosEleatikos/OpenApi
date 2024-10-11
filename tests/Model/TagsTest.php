<?php

declare(strict_types=1);

namespace Xenos\OpenApiTest\Model;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Xenos\OpenApi\Model\Tag;
use Xenos\OpenApi\Model\Tags;

use function array_values;

class TagsTest extends TestCase
{
    #[DataProvider('provideDataForTestGetTagNames')]
    public function testGetTagNames(
        array $expectedTagNames,
        Tags $tags
    ): void {
        self::assertEqualsCanonicalizing(
            expected: array_values($expectedTagNames),
            actual: array_values($tags->getTagNames()),
            message: 'The array does not contain the expected tag names.'
        );
        self::assertSame(
            expected: array_values($expectedTagNames),
            actual: array_values($tags->getTagNames()),
            message: 'The array is not sorted correctly.'
        );
        self::assertSame(
            expected: $expectedTagNames,
            actual: array_values($tags->getTagNames()),
            message: 'The array does not have numeric contiguous keys starting from 0.'
        );
    }

    public static function provideDataForTestGetTagNames(): array
    {
        return [
            'no tags' => [
                'expectedTagNames' => [],
                'tags' => new Tags(),
            ],
            'one tag' => [
                'expectedTagNames' => ['pet'],
                'tags' => new Tags([
                    new Tag(name: 'pet')
                ]),
            ],
            'three tags' => [
                'expectedTagNames' => ['pet', 'store', 'user'],
                'tags' => new Tags([
                    new Tag(name: 'pet'),
                    new Tag(name: 'store'),
                    new Tag(name: 'user'),
                ]),
            ],
        ];
    }

    #[DataProvider('provideDataForTestSortTags')]
    public function testSortTags(
        array $expectedTagNames,
        Tags $tags,
        array $input
    ): void {
        self::assertEqualsCanonicalizing(
            expected: array_values($expectedTagNames),
            actual: array_values($tags->sortTags($input)),
            message: 'The array does not contain the expected tag names.'
        );
        self::assertSame(
            expected: array_values($expectedTagNames),
            actual: array_values($tags->sortTags($input)),
            message: 'The array is not sorted correctly.'
        );
        self::assertSame(
            expected: $expectedTagNames,
            actual: array_values($tags->sortTags($input)),
            message: 'The array does not have numeric contiguous keys starting from 0.'
        );
    }

    public static function provideDataForTestSortTags(): array
    {
        return [
            // no tags in list
            'no tags in list, no input' => [
                'expectedTagNames' => [],
                'tags' => new Tags(),
                'input' => [],
            ],
            'no tags in list, one input tag' => [
                'expectedTagNames' => ['pet'],
                'tags' => new Tags(),
                'input' => ['pet'],
            ],
            'no tags in list, two input tags' => [
                'expectedTagNames' => ['pet', 'store'],
                'tags' => new Tags(),
                'input' => ['pet', 'store'],
            ],
            'no tags in list, two input tags (preserve order)' => [
                'expectedTagNames' => ['pet', 'store'],
                'tags' => new Tags(),
                'input' => ['pet', 'store'],
            ],
            // one tag in list
            'one tag in list, no input' => [
                'expectedTagNames' => [],
                'tags' => new Tags([
                    new Tag(name: 'pet')
                ]),
                'input' => [],
            ],
            'one tag in list, one input tag from list' => [
                'expectedTagNames' => ['pet'],
                'tags' => new Tags([
                    new Tag(name: 'pet')
                ]),
                'input' => ['pet'],
            ],
            'one tag in list, one other input tag' => [
                'expectedTagNames' => ['store'],
                'tags' => new Tags([
                    new Tag(name: 'pet')
                ]),
                'input' => ['store'],
            ],
            'one tag in list, two other input tags' => [
                'expectedTagNames' => ['store', 'user'],
                'tags' => new Tags([
                    new Tag(name: 'pet')
                ]),
                'input' => ['store', 'user'],
            ],
            'one tag in list, two other input tags (preserve order)' => [
                'expectedTagNames' => ['user', 'store'],
                'tags' => new Tags([
                    new Tag(name: 'pet')
                ]),
                'input' => ['user', 'store'],
            ],
            'one tag in list, one input tag from list and one other tag' => [
                'expectedTagNames' => ['pet', 'store'],
                'tags' => new Tags([
                    new Tag(name: 'pet')
                ]),
                'input' => ['pet', 'store'],
            ],
            'one tag in list, one other input tag and one from list' => [
                'expectedTagNames' => ['pet', 'store'],
                'tags' => new Tags([
                    new Tag(name: 'pet')
                ]),
                'input' => ['store', 'pet'],
            ],
            // two tags in list
            'two tags in list, no input' => [
                'expectedTagNames' => [],
                'tags' => new Tags([
                    new Tag(name: 'pet'),
                    new Tag(name: 'store'),
                ]),
                'input' => [],
            ],
            'two tags in list, one input tag from list' => [
                'expectedTagNames' => ['pet'],
                'tags' => new Tags([
                    new Tag(name: 'pet'),
                    new Tag(name: 'store'),
                ]),
                'input' => ['pet'],
            ],
            'two tags in list, two input tag from list' => [
                'expectedTagNames' => ['pet', 'store'],
                'tags' => new Tags([
                    new Tag(name: 'pet'),
                    new Tag(name: 'store'),
                ]),
                'input' => ['pet', 'store'],
            ],
            'two tags in list, two input tag from list in other direction' => [
                'expectedTagNames' => ['pet', 'store'],
                'tags' => new Tags([
                    new Tag(name: 'pet'),
                    new Tag(name: 'store'),
                ]),
                'input' => ['store', 'pet'],
            ],
            'two tags in list, two input tag from list and one other' => [
                'expectedTagNames' => ['pet', 'store', 'user'],
                'tags' => new Tags([
                    new Tag(name: 'pet'),
                    new Tag(name: 'store'),
                ]),
                'input' => ['pet', 'store', 'user'],
            ],
            'two tags in list, two input tag from list in other direction and one other' => [
                'expectedTagNames' => ['pet', 'store', 'user'],
                'tags' => new Tags([
                    new Tag(name: 'pet'),
                    new Tag(name: 'store'),
                ]),
                'input' => ['store', 'pet', 'user'],
            ],
            'two tags in list, one other input tag and two from list' => [
                'expectedTagNames' => ['pet', 'store', 'user'],
                'tags' => new Tags([
                    new Tag(name: 'pet'),
                    new Tag(name: 'store'),
                ]),
                'input' => ['user', 'store', 'pet'],
            ],
            'two tags in list, one other input tag and two from list in other direction' => [
                'expectedTagNames' => ['pet', 'store', 'user'],
                'tags' => new Tags([
                    new Tag(name: 'pet'),
                    new Tag(name: 'store'),
                ]),
                'input' => ['user', 'pet', 'store'],
            ],
        ];
    }
}
