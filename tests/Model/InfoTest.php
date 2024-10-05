<?php

declare(strict_types=1);

namespace Xenos\OpenApiTest\Model;

use PHPUnit\Framework\Attributes\TestWith;
use PHPUnit\Framework\TestCase;
use Xenos\OpenApi\Model\Contact;
use Xenos\OpenApi\Model\Info;

class InfoTest extends TestCase
{
    #[TestWith([true, 'Jon Doe', 'http://example.com', 'test@example.com'])]
    #[TestWith([true, 'Jon Doe', 'http://example.com', null])]
    #[TestWith([true, 'Jon Doe', null, 'test@example.com'])]
    #[TestWith([true, null, 'http://example.com', 'test@example.com'])]
    #[TestWith([true, 'Jon Doe', null, null])]
    #[TestWith([true, null, 'http://example.com', null])]
    #[TestWith([true, null, null, 'test@example.com'])]
    #[TestWith([false, null, null, null])]
    #[TestWith([true, 'Jon Doe', 'http://example.com', 'test@example.com'])]
    #[TestWith([true, 'Jon Doe', 'http://example.com', ''])]
    #[TestWith([true, 'Jon Doe', '', 'test@example.com'])]
    #[TestWith([true, '', 'http://example.com', 'test@example.com'])]
    #[TestWith([true, 'Jon Doe', '', ''])]
    #[TestWith([true, '', 'http://example.com', ''])]
    #[TestWith([true, '', '', 'test@example.com'])]
    #[TestWith([false, '', '', ''])]
    public function testHasContactInformationWithContactObject(
        bool $expected,
        ?string $name,
        ?string $url,
        ?string $email
    ): void {
        $info = new Info(
            title: 'Pet Shop API',
            version: '1.0.0',
            contact: new Contact(
                name: $name,
                url: $url,
                email: $email
            )
        );

        self::assertSame($expected, $info->hasContactInformation());
    }

    public function testHasContactInformationWithoutContactObject(): void
    {
        $info = new Info(
            title: 'Pet Shop API',
            version: '1.0.0',
        );

        self::assertFalse(
            $info->hasContactInformation()
        );
    }
}
