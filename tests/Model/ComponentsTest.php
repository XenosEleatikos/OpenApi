<?php

declare(strict_types=1);

namespace Xenos\OpenApiTest\Model;

use InvalidArgumentException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Xenos\OpenApi\Model\AbstractComponentsSubList;
use Xenos\OpenApi\Model\Example;
use Xenos\OpenApi\Model\Examples;
use Xenos\OpenApi\Model\Header;
use Xenos\OpenApi\Model\Headers;
use Xenos\OpenApi\Model\Parameter;
use Xenos\OpenApi\Model\ParameterLocation;
use Xenos\OpenApi\Model\Parameters;
use Xenos\OpenApi\Model\RequestBodies;
use Xenos\OpenApi\Model\RequestBody;
use Xenos\OpenApi\Model\Response;
use Xenos\OpenApi\Model\Responses;
use Xenos\OpenApi\Model\Schema;
use Xenos\OpenApi\Model\Schemas;
use Xenos\OpenApi\Model\SecurityScheme;
use Xenos\OpenApi\Model\SecurityScheme\SecuritySchemeType;
use Xenos\OpenApi\Model\SecuritySchemes;

use function var_export;

class ComponentsTest extends TestCase
{
    #[DataProvider('provideDataForTestOffsetSetWithValidKeys')]
    public function testOffsetSetWithValidKeys(
        string $class,
        ?string $key,
        object $element
    ): void {
        $responses = new $class();
        $responses[$key] = $element;

        self::assertSame($element, $responses[$key]);
    }

    /** @param class-string<AbstractComponentsSubList> $class */
    #[DataProvider('provideDataForTestOffsetSetThrowsInvalidArgumentExceptionForInvalidKey')]
    public function testOffsetSetThrowsInvalidArgumentExceptionForInvalidKey(
        string $class,
        ?string $key,
        object $element
    ): void {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Array key must be a string matching the regular expression "/^[a-zA-Z0-9._-]+$/", ' . var_export($key, true) . ' given');

        $responses = new $class();
        $responses->offsetSet($key, $element);
    }

    #[DataProvider('provideDataForTestOffsetSetThrowsInvalidArgumentExceptionForInvalidKey')]
    public function testOffsetSetWithBracketSyntaxThrowsInvalidArgumentExceptionForInvalidKey(
        string $class,
        ?string $key,
        object $element
    ): void {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Array key must be a string matching the regular expression "/^[a-zA-Z0-9._-]+$/", ' . var_export($key, true) . ' given');

        $responses = new $class();
        $responses[$key] = $element;
    }

    public static function provideDataForTestOffsetSetThrowsInvalidArgumentExceptionForInvalidKey(): array
    {
        foreach (self::getSubLists() as $subList => $element) {
            foreach (self::getInvalidKeys() as $key) {
                $testCases[$subList . ' with key ' . $key] = [
                    'class' => $subList,
                    'key' => $key,
                    'element' => $element,
                ];
            }
        }

        return $testCases;
    }

    public static function provideDataForTestOffsetSetWithValidKeys(): array
    {
        foreach (self::getSubLists() as $subList => $element) {
            foreach (self::getValidKeys() as $key) {
                $testCases[$subList . ' with key ' . $key] = [
                    'class' => $subList,
                    'key' => $key,
                    'element' => $element,
                ];
            }
        }

        return $testCases;
    }

    private static function getValidKeys(): array
    {
        return [
            'pet',
            'Pet',
            'PET',
            'pet123',
            '123pet',
            'pet-store',
            'pet.store',
            'pet_store',
        ];
    }

    private static function getInvalidKeys(): array
    {
        return [
            'two words',
            '@',
            '#',
            '%',
            PHP_EOL,
            'ä',
            'ö',
            'ü',
            'ß',
            null,
        ];
    }

    private static function getSubLists(): array
    {
        return [
            Schemas::class => new Schema(),
            Responses::class => new Response(description: 'Some response'),
            Parameters::class => new Parameter(name: 'param1', in: ParameterLocation::PATH),
            Examples::class => new Example(),
            RequestBodies::class => new RequestBody(),
            Headers::class => new Header(),
            SecuritySchemes::class => new SecurityScheme(type: SecuritySchemeType::HTTP),
        ];
    }
}
