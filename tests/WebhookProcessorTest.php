<?php

declare(strict_types=1);

namespace GiggArgentgg\Tests;

use GiggArgentgg\Integration\WebhookProcessor;
use PHPUnit\Framework\TestCase;

final class WebhookProcessorTest extends TestCase
{
    public function testSignatureValidationAndQueueEntry(): void
    {
        $payload = '{"event_type":"presupuesto.updated"}';
        $secret = 'my-secret';
        $signature = hash_hmac('sha256', $payload, $secret);

        $processor = new WebhookProcessor();
        self::assertTrue($processor->validateSignature($payload, $signature, $secret));
        self::assertFalse($processor->validateSignature($payload, 'invalid', $secret));

        $entry = $processor->toQueueEntry(['event_type' => 'presupuesto.updated', 'id' => 'P1']);
        self::assertSame('presupuesto.updated', $entry['event_type']);
        self::assertSame('pending', $entry['status']);
        self::assertSame(0, $entry['attempts']);
    }
}
