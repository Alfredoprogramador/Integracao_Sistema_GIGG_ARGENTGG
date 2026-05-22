<?php

declare(strict_types=1);

namespace GiggArgentgg\Integration;

final class ClientImporter
{
    public function __construct(private readonly SyncValidator $validator)
    {
    }

    /**
     * @param array<string, mixed> $argentggClient
     * @param callable(string):bool $emailExists
     *
     * @return array<string, mixed>
     */
    public function normalize(array $argentggClient, callable $emailExists): array
    {
        $normalized = [
            'external_id' => (string) ($argentggClient['id'] ?? ''),
            'nome' => trim((string) ($argentggClient['name'] ?? '')),
            'email' => mb_strtolower(trim((string) ($argentggClient['email'] ?? ''))),
            'cpf_cnpj' => (string) ($argentggClient['document'] ?? ''),
        ];

        $this->validate($normalized, $emailExists);

        return $normalized;
    }

    /**
     * @param array<string, mixed> $normalized
     * @param callable(string):bool $emailExists
     */
    private function validate(array $normalized, callable $emailExists): void
    {
        if (!$this->validator->isCpfCnpjValid((string) $normalized['cpf_cnpj'])) {
            throw new \InvalidArgumentException('CPF/CNPJ inválido');
        }

        if (!$this->validator->isEmailUnique((string) $normalized['email'], $emailExists)) {
            throw new \InvalidArgumentException('E-mail duplicado');
        }
    }
}
