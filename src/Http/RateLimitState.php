<?php

declare(strict_types=1);

namespace GiggArgentgg\Http;

final class RateLimitState
{
    private int $waitUntilEpoch = 0;

    public function setWaitUntil(int $waitUntilEpoch): void
    {
        $this->waitUntilEpoch = max($this->waitUntilEpoch, $waitUntilEpoch);
    }

    public function waitIfNeeded(?callable $sleeper = null): void
    {
        $now = time();
        if ($this->waitUntilEpoch <= $now) {
            return;
        }

        $delay = $this->waitUntilEpoch - $now;
        ($sleeper ?? static fn (int $seconds): int => sleep($seconds))($delay);
    }
}
