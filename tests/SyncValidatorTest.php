<?php

declare(strict_types=1);

namespace GiggArgentgg\Tests;

use GiggArgentgg\Integration\SyncValidator;
use PHPUnit\Framework\TestCase;

final class SyncValidatorTest extends TestCase
{
    public function testValidations(): void
    {
        $validator = new SyncValidator();

        self::assertTrue($validator->isCpfCnpjValid('123.456.789-01'));
        self::assertTrue($validator->isCpfCnpjValid('12.345.678/0001-99'));
        self::assertFalse($validator->isCpfCnpjValid('123'));
        self::assertTrue($validator->isEmailUnique('a@b.com', static fn (): bool => false));
        self::assertFalse($validator->isEmailUnique('a@b.com', static fn (): bool => true));
        self::assertTrue($validator->hasValidPrice(10.5));
        self::assertFalse($validator->hasValidPrice(0.0));
        self::assertTrue($validator->hasValidStock(0));
        self::assertFalse($validator->hasValidStock(-1));
    }
}
