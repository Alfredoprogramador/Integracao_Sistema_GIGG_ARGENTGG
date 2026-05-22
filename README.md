# Integração GIGG ⇄ ARGENTGG

Gateway de integração em PHP 8.2+ para sincronizar dados entre GIGG e ARGENTGG, com webhooks, fila assíncrona, validações, logs estruturados e automações Botmaker.

## Estrutura

- `src/Http/ArgentggApiClient.php`: cliente Guzzle com timeout, retry/backoff e rate limit.
- `src/Integration`: importação/exportação, webhook, validações e resolução de conflitos.
- `src/Botmaker`: eventos e notificações para WhatsApp/SMS/E-mail.
- `database/migrations`: tabelas de suporte (`argentgg_sync_log`, `argentgg_webhook_queue`).
- `docs/openapi.yaml`: documentação dos endpoints REST.
- `docs/postman_collection.json`: collection para testes de API.
- `logs/examples/argentgg_request.log`: exemplo de log estruturado.

## Instalação

```bash
composer install
```

## Testes

```bash
composer test
```
