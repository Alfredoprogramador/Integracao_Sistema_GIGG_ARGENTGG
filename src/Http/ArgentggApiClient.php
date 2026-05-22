<?php

declare(strict_types=1);

namespace GiggArgentgg\Http;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use Monolog\Logger;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Throwable;

final class ArgentggApiClient
{
    private const MAX_RETRIES = 3;
    private const TIMEOUT_SECONDS = 30.0;

    private readonly Client $client;

    public function __construct(
        string $baseUri,
        private readonly Logger $logger,
        private readonly RateLimitState $rateLimitState = new RateLimitState()
    ) {
        $stack = HandlerStack::create();
        $stack->push(Middleware::retry(
            function (
                int $retries,
                RequestInterface $request,
                ?ResponseInterface $response = null,
                ?GuzzleException $exception = null
            ): bool {
                if ($retries >= self::MAX_RETRIES) {
                    return false;
                }

                return $exception !== null || ($response !== null && $response->getStatusCode() >= 500);
            },
            static fn (int $retries): int => (int) (1000 * (2 ** $retries))
        ));

        $this->client = new Client([
            'base_uri' => $baseUri,
            'handler' => $stack,
            'timeout' => self::TIMEOUT_SECONDS,
        ]);
    }

    /**
     * @param array<string, mixed> $payload
     *
     * @return array<string, mixed>
     */
    public function request(string $method, string $endpoint, array $payload = []): array
    {
        $requestId = bin2hex(random_bytes(8));
        $this->rateLimitState->waitIfNeeded();

        try {
            $response = $this->client->request($method, $endpoint, ['json' => $payload]);
            $this->updateRateLimitState($response);

            $decoded = (array) json_decode((string) $response->getBody(), true, 512, JSON_THROW_ON_ERROR);
            $this->logger->info('argentgg_request', [
                'request_id' => $requestId,
                'endpoint' => $endpoint,
                'payload' => $payload,
                'status' => $response->getStatusCode(),
            ]);

            return $decoded;
        } catch (Throwable $throwable) {
            $this->logger->error('argentgg_request_error', [
                'request_id' => $requestId,
                'endpoint' => $endpoint,
                'payload' => $payload,
                'status' => 0,
                'erro' => $throwable->getMessage(),
            ]);

            throw $throwable;
        }
    }

    private function updateRateLimitState(ResponseInterface $response): void
    {
        $remaining = (int) $response->getHeaderLine('X-RateLimit-Remaining');
        $resetAt = (int) $response->getHeaderLine('X-RateLimit-Reset');

        if ($remaining === 0 && $resetAt > 0) {
            $this->rateLimitState->setWaitUntil($resetAt);
        }
    }
}
