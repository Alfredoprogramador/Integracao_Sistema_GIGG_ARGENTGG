<?php

declare(strict_types=1);

namespace GiggArgentgg\Integration;

final class SyncValidator
{
    public function isCpfCnpjValid(string $document): bool
    {
        $digits = preg_replace('/\D+/', '', $document) ?? '';

        return \strlen($digits) === 11 || \strlen($digits) === 14;
    }

    public function isEmailUnique(string $email, callable $exists): bool
    {
        return !$exists(mb_strtolower(trim($email)));
    }

    public function hasValidPrice(float $price): bool
    {
        return $price > 0;
    }

    public function hasValidStock(int $stock): bool
    {
        return $stock >= 0;
    }
}
