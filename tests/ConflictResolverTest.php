<?php

declare(strict_types=1);

namespace GiggArgentgg\Tests;

use GiggArgentgg\Integration\ConflictResolver;
use PHPUnit\Framework\TestCase;

final class ConflictResolverTest extends TestCase
{
    public function testPrefersHigherVersion(): void
    {
        $resolver = new ConflictResolver();
        $result = $resolver->resolve(
            ['updated_at' => '2026-05-22 12:00:00', 'version' => 1, 'status' => 'pendente'],
            ['updated_at' => '2026-05-22 11:00:00', 'version' => 2, 'status' => 'aprovado']
        );

        self::assertSame('remote', $result['winner']);
        self::assertSame('aprovado', $result['status']);
        self::assertFalse($result['alert']);
    }

    public function testSameVersionUsesLatestTimestamp(): void
    {
        $resolver = new ConflictResolver();
        $result = $resolver->resolve(
            ['updated_at' => '2026-05-22 10:00:00', 'version' => 2, 'status' => 'pendente'],
            ['updated_at' => '2026-05-22 12:00:00', 'version' => 2, 'status' => 'rejeitado']
        );

        self::assertSame('remote', $result['winner']);
        self::assertSame('rejeitado', $result['status']);
        self::assertFalse($result['alert']);
    }
}
