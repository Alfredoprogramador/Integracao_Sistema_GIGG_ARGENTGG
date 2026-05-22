<?php

declare(strict_types=1);

namespace GiggArgentgg\Botmaker;

final class BotmakerNotifier
{
    /**
     * @param array<string, mixed> $context
     */
    public function forVendaConcluida(array $context): string
    {
        return sprintf(
            'Seu pedido #%s foi faturado! Código de rastreio: %s',
            $context['pedido'] ?? '',
            $context['rastreio'] ?? ''
        );
    }

    /**
     * @param array<string, mixed> $context
     */
    public function forEstoqueBaixo(array $context): string
    {
        return sprintf(
            'Alerta de estoque baixo: produto %s com saldo %s',
            $context['produto'] ?? '',
            $context['saldo'] ?? ''
        );
    }
}
