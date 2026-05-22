<?php

declare(strict_types=1);

namespace GiggArgentgg\Tests;

use GiggArgentgg\Http\RateLimitState;
use PHPUnit\Framework\TestCase;

final class RateLimitStateTest extends TestCase
{
    public function testSleepsOnlyWhenNeeded(): void
    {
        $state = new RateLimitState();
        $state->setWaitUntil(time() + 2);

        $delay = 0;
        $state->waitIfNeeded(static function (int $seconds) use (&$delay): void {
            $delay = $seconds;
        });

        self::assertGreaterThanOrEqual(1, $delay);
    }
}
