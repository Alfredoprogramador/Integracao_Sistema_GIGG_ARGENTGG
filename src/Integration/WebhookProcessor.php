<?php

declare(strict_types=1);

namespace GiggArgentgg\Integration;

final class WebhookProcessor
{
    public function validateSignature(string $payload, string $signature, string $secret): bool
    {
        $expected = hash_hmac('sha256', $payload, $secret);

        return hash_equals($expected, $signature);
    }

    /**
     * @param array<string, mixed> $decodedPayload
     *
     * @return array<string, mixed>
     */
    public function toQueueEntry(array $decodedPayload): array
    {
        return [
            'event_type' => (string) ($decodedPayload['event_type'] ?? 'unknown'),
            'payload' => json_encode($decodedPayload, JSON_THROW_ON_ERROR),
            'received_at' => gmdate('Y-m-d H:i:s'),
            'status' => 'pending',
            'attempts' => 0,
        ];
    }
}
