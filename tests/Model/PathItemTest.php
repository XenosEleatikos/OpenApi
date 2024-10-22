<?php

declare(strict_types=1);

namespace Xenos\OpenApiTest\Model;

use PHPUnit\Framework\Attributes\Depends;
use PHPUnit\Framework\Attributes\TestWith;
use PHPUnit\Framework\TestCase;
use Xenos\OpenApi\Model\Method;
use Xenos\OpenApi\Model\Operation;
use Xenos\OpenApi\Model\PathItem;

use function array_keys;
use function array_map;
use function array_values;
use function count;
use function strtolower;

class PathItemTest extends TestCase
{
    private const array METHODS = ['GET', 'PUT', 'POST', 'DELETE', 'OPTIONS', 'HEAD', 'PATCH', 'TRACE'];

    /** @return array<string, Operation> */
    public function testGetAllOperationsReturnsAllOperations(): array
    {
        $pathItem = new PathItem(
            get: new Operation(tags: ['GET']),
            put: new Operation(tags: ['PUT']),
            post: new Operation(tags: ['POST']),
            delete: new Operation(tags: ['DELETE']),
            options: new Operation(tags: ['OPTIONS']),
            head: new Operation(tags: ['HEAD']),
            patch: new Operation(tags: ['PATCH']),
            trace: new Operation(tags: ['TRACE']),
        );

        $allOperations = $pathItem->getAllOperations();

        self::assertCount(8, $allOperations);
        self::assertEqualsCanonicalizing(
            self::METHODS,
            array_values($this->getFirstTag($allOperations))
        );

        return $allOperations;
    }

    /**
     * @param array<string, Operation> $allOperations
     * @return array<string, Operation>
     */
    #[Depends('testGetAllOperationsReturnsAllOperations')]
    public function testGetAllOperationsReturnValuesHasArrayKeys(array $allOperations): array
    {
        self::assertSame(
            array_keys($allOperations),
            $this->getFirstTag($allOperations),
            'There is a mismatch between operations and keys'
        );

        return $allOperations;
    }

    /** @param array<string, Operation> $allOperations */
    public function tetGetAllOperationsReturnValueIsOrderedCorrectly(array $allOperations): void
    {
        self::assertSame(
            self::METHODS,
            array_values($this->getFirstTag($allOperations))
        );
    }

    #[TestWith([['GET']])]
    #[TestWith([['PUT']])]
    #[TestWith([['POST']])]
    #[TestWith([['DELETE']])]
    #[TestWith([['OPTIONS']])]
    #[TestWith([['HEAD']])]
    #[TestWith([['PATCH']])]
    #[TestWith([['TRACE']])]
    #[TestWith([['GET', 'PUT']])]
    #[TestWith([['GET', 'POST']])]
    #[TestWith([['GET', 'DELETE']])]
    #[TestWith([['GET', 'OPTIONS']])]
    #[TestWith([['GET', 'HEAD']])]
    #[TestWith([['GET', 'PATCH']])]
    #[TestWith([['GET', 'TRACE']])]
    #[TestWith([['PUT', 'POST']])]
    #[TestWith([['PUT', 'DELETE']])]
    #[TestWith([['PUT', 'OPTIONS']])]
    #[TestWith([['PUT', 'HEAD']])]
    #[TestWith([['PUT', 'PATCH']])]
    #[TestWith([['PUT', 'TRACE']])]
    #[TestWith([['POST', 'DELETE']])]
    #[TestWith([['POST', 'OPTIONS']])]
    #[TestWith([['POST', 'HEAD']])]
    #[TestWith([['POST', 'PATCH']])]
    #[TestWith([['POST', 'TRACE']])]
    #[TestWith([['DELETE', 'OPTIONS']])]
    #[TestWith([['DELETE', 'HEAD']])]
    #[TestWith([['DELETE', 'PATCH']])]
    #[TestWith([['DELETE', 'TRACE']])]
    #[TestWith([['OPTIONS', 'HEAD']])]
    #[TestWith([['OPTIONS', 'PATCH']])]
    #[TestWith([['OPTIONS', 'TRACE']])]
    #[TestWith([['HEAD', 'PATCH']])]
    #[TestWith([['HEAD', 'TRACE']])]
    #[TestWith([['PATCH', 'TRACE']])]
    #[TestWith([['GET', 'PUT', 'POST']])]
    #[TestWith([['GET', 'PUT', 'DELETE']])]
    #[TestWith([['GET', 'PUT', 'OPTIONS']])]
    #[TestWith([['GET', 'PUT', 'HEAD']])]
    #[TestWith([['GET', 'PUT', 'PATCH']])]
    #[TestWith([['GET', 'PUT', 'TRACE']])]
    #[TestWith([['GET', 'POST', 'DELETE']])]
    #[TestWith([['GET', 'POST', 'OPTIONS']])]
    #[TestWith([['GET', 'POST', 'HEAD']])]
    #[TestWith([['GET', 'POST', 'PATCH']])]
    #[TestWith([['GET', 'POST', 'TRACE']])]
    #[TestWith([['GET', 'DELETE', 'OPTIONS']])]
    #[TestWith([['GET', 'DELETE', 'HEAD']])]
    #[TestWith([['GET', 'DELETE', 'PATCH']])]
    #[TestWith([['GET', 'DELETE', 'TRACE']])]
    #[TestWith([['GET', 'OPTIONS', 'HEAD']])]
    #[TestWith([['GET', 'OPTIONS', 'PATCH']])]
    #[TestWith([['GET', 'OPTIONS', 'TRACE']])]
    #[TestWith([['GET', 'HEAD', 'PATCH']])]
    #[TestWith([['GET', 'HEAD', 'TRACE']])]
    #[TestWith([['GET', 'PATCH', 'TRACE']])]
    #[TestWith([['PUT', 'POST', 'DELETE']])]
    #[TestWith([['PUT', 'POST', 'OPTIONS']])]
    #[TestWith([['PUT', 'POST', 'HEAD']])]
    #[TestWith([['PUT', 'POST', 'PATCH']])]
    #[TestWith([['PUT', 'POST', 'TRACE']])]
    #[TestWith([['PUT', 'DELETE', 'OPTIONS']])]
    #[TestWith([['PUT', 'DELETE', 'HEAD']])]
    #[TestWith([['PUT', 'DELETE', 'PATCH']])]
    #[TestWith([['PUT', 'DELETE', 'TRACE']])]
    #[TestWith([['PUT', 'OPTIONS', 'HEAD']])]
    #[TestWith([['PUT', 'OPTIONS', 'PATCH']])]
    #[TestWith([['PUT', 'OPTIONS', 'TRACE']])]
    #[TestWith([['PUT', 'HEAD', 'PATCH']])]
    #[TestWith([['PUT', 'HEAD', 'TRACE']])]
    #[TestWith([['PUT', 'PATCH', 'TRACE']])]
    #[TestWith([['POST', 'DELETE', 'OPTIONS']])]
    #[TestWith([['POST', 'DELETE', 'HEAD']])]
    #[TestWith([['POST', 'DELETE', 'PATCH']])]
    #[TestWith([['POST', 'DELETE', 'TRACE']])]
    #[TestWith([['POST', 'OPTIONS', 'HEAD']])]
    #[TestWith([['POST', 'OPTIONS', 'PATCH']])]
    #[TestWith([['POST', 'OPTIONS', 'TRACE']])]
    #[TestWith([['POST', 'HEAD', 'PATCH']])]
    #[TestWith([['POST', 'HEAD', 'TRACE']])]
    #[TestWith([['POST', 'PATCH', 'TRACE']])]
    #[TestWith([['DELETE', 'OPTIONS', 'HEAD']])]
    #[TestWith([['DELETE', 'OPTIONS', 'PATCH']])]
    #[TestWith([['DELETE', 'OPTIONS', 'TRACE']])]
    #[TestWith([['DELETE', 'HEAD', 'PATCH']])]
    #[TestWith([['DELETE', 'HEAD', 'TRACE']])]
    #[TestWith([['DELETE', 'PATCH', 'TRACE']])]
    #[TestWith([['OPTIONS', 'HEAD', 'PATCH']])]
    #[TestWith([['OPTIONS', 'HEAD', 'TRACE']])]
    #[TestWith([['OPTIONS', 'PATCH', 'TRACE']])]
    #[TestWith([['HEAD', 'PATCH', 'TRACE']])]
    public function testGetAllOperationsFiltersOptions(array $methods): void
    {
        $pathItem = new PathItem();
        foreach ($methods as $method) {
            $pathItem->{strtolower($method)} = new Operation(tags: [$method]);
        }

        $allOperations = $pathItem->getAllOperations();

        self::assertCount(
            expectedCount: count($methods),
            haystack: $allOperations
        );
        self::assertEqualsCanonicalizing(
            expected: $methods,
            actual: $this->getFirstTag($allOperations)
        );
        self::assertSame(
            expected: $methods,
            actual: $this->getFirstTag($allOperations),
            message: 'Filtered options are not sorted correctly.'
        );
        self::assertSame(
            expected: array_keys($allOperations),
            actual: $this->getFirstTag($allOperations),
            message: 'There is a mismatch between filtered operations and keys.'
        );
    }

    public function testGetOperationReturnsOperation(): void
    {
        foreach (Method::cases() as $method) {
            $operation = new Operation();

            $pathItem = new PathItem();
            $pathItem->{$method->lowerCase()} = $operation;

            self::assertSame(
                expected: $operation,
                actual: $pathItem->getOperation($method)
            );
        }
    }

    /**
     * @param array<string, Operation> $operations
     * @return string[]
    */
    private function getFirstTag(array $operations): array
    {
        return array_values(array_map(
            fn (Operation $operation): string => $operation->tags[0],
            $operations
        ));
    }
}
