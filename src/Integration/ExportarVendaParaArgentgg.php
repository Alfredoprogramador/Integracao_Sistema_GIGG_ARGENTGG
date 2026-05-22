<?php

declare(strict_types=1);

namespace GiggArgentgg\Integration;

final class ExportarVendaParaArgentgg
{
    /**
     * @param array<string, mixed> $pedido
     *
     * @return array<string, mixed>
     */
    public function buildPayload(array $pedido): array
    {
        return [
            'external_id' => (string) $pedido['codigo'],
            'cliente' => [
                'id' => (int) $pedido['cliente_id'],
                'nome' => (string) $pedido['cliente_nome'],
            ],
            'itens' => array_map(static fn (array $item): array => [
                'produto_id' => (string) $item['produto_id'],
                'quantidade' => (int) $item['quantidade'],
                'preco' => (float) $item['preco'],
            ], $pedido['itens'] ?? []),
            'total' => (float) $pedido['total'],
        ];
    }
}
