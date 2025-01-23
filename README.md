# README-pt-BR.md

# Advanced Logger para Laravel

[Read in English](README.md)

Sistema avançado de logs para aplicações Laravel com suporte integrado para Slack, Sentry e DataDog.

[![Latest Version on Packagist](https://img.shields.io/packagist/v/tfo/advanced-log.svg?style=flat-square)](https://packagist.org/packages/tfo/advanced-log)
[![Total Downloads](https://img.shields.io/packagist/dt/tfo/advanced-log.svg?style=flat-square)](https://packagist.org/packages/tfo/advanced-log)
[![License](https://img.shields.io/packagist/l/tfo/advanced-log.svg?style=flat-square)](LICENSE.md)

## Funcionalidades

- 🚀 API simples e intuitiva
- 📱 Notificações em tempo real no Slack
- 🔍 Integração com rastreamento de erros do Sentry
- 📊 Suporte a métricas do DataDog
- 🎨 Formatação personalizável de mensagens
- ⚡ Múltiplos canais de notificação
- 🔒 Tratamento seguro de credenciais
- 🛠 Configuração simplificada

## Requisitos

- PHP ^8.1
- Laravel ^10.0

## Instalação

Você pode instalar o pacote via composer:

```bash
composer require tfo/advanced-log
```

## Configuração

1. Publique o arquivo de configuração:

```bash
php artisan vendor:publish --tag=advanced-logger-config
```

2. Adicione estas variáveis ao seu arquivo `.env`:

```env
# Configuração do Slack
LOGGER_SLACK_WEBHOOK_URL=sua-webhook-url
LOGGER_SLACK_CHANNEL=#seu-canal
LOGGER_SLACK_USERNAME=Logger Bot

# Configuração do Sentry
LOGGER_SENTRY_DSN=seu-sentry-dsn

# Configuração do DataDog
LOGGER_DATADOG_API_KEY=sua-api-key
LOGGER_DATADOG_APP_KEY=sua-app-key
LOGGER_DATADOG_SERVICE=nome-do-seu-servico

# Habilitar/Desabilitar Serviços
LOGGER_ENABLE_SLACK=true
LOGGER_ENABLE_SENTRY=true
LOGGER_ENABLE_DATADOG=true
```

## Uso

### Logs Básicos

```php
use Tfo\AdvancedLog\Facades\Log;

// Mensagem simples
Log::log('info', 'Usuário logado com sucesso');

// Com contexto
Log::log('info', 'Novo pedido criado', [
    'pedido_id' => $pedido->id,
    'valor' => $pedido->valor,
    'cliente' => $pedido->cliente->email
]);
```

### Logs de Erro

```php
try {
    // Seu código
} catch (\Exception $e) {
    Log::log('error', 'Falha no processamento do pagamento', [
        'exception' => $e,
        'pedido_id' => $pedido->id
    ]);
}
```

### Formatação Personalizada

```php
Log::log('warning', 'Alta carga no servidor detectada', [
    'uso_cpu' => '90%',
    'uso_memoria' => '85%',
    'timestamp' => now()
]);
```

## Canais

### Slack

Mensagens são enviadas para o Slack com:

- Emojis indicando o nível do log
- Mensagens com código de cores
- Campos estruturados para contexto
- Suporte a canais personalizados

### Sentry

Erros são rastreados no Sentry com:

- Stack traces completos
- Informações do ambiente
- Contexto do usuário
- Tags e breadcrumbs personalizados

### DataDog

Métricas são enviadas para o DataDog com:

- Métricas personalizadas
- Tags
- Agregação de eventos
- Rastreamento de performance

## Testes

```bash
composer test
```

## Changelog

Veja [CHANGELOG.md](CHANGELOG.md) para mais informações sobre mudanças recentes.

## Contribuindo

Veja [CONTRIBUTING.md](CONTRIBUTING.md) para detalhes.

## Segurança

Se você descobrir algum problema relacionado à segurança, por favor envie um e-mail para your@email.com em vez de usar o issue tracker.

## Créditos

- [Thalles Freitas](https://github.com/thallesfreitas)
- [Todos os Contribuidores](../../contributors)

## Licença

The MIT License (MIT). Veja [License File](LICENSE.md) para mais informações.
