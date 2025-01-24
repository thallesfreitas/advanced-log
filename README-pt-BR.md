# README-pt-BR.md

# Advanced Logger para Laravel

[Read in English](README.md)

Sistema avançado de logs para aplicações Laravel com suporte integrado para Slack, Sentry e DataDog (EM BREVE).

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

## Destino dos Logs

Os logs são enviados para:

- Arquivo local (storage/logs/laravel.log)
- Slack (configurável por webhook)
- Sentry (se configurado)
- DataDog (se configurado) (EM BREVE)

## Requisitos

- PHP ^8.1
- Laravel ^10.0|^11.0

## Instalação

Você pode instalar o pacote via composer:

```bash
composer require tfo/advanced-log
```

## Configuração

1. Publique o arquivo de configuração:

```bash
php artisan advanced-logger:install
```

O comando advanced-logger:install irá:

- Publicar arquivos de configuração
- Copiar loggers para app/Loggers
- Instalar e registrar o ServiceProvider
- Adicionar variáveis ao .env
- Publicar rotas de teste

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

use Illuminate\Support\Facades\Log;

Log::emergency('Emergency log test', ['context' => 'test']);
Log::alert('Alert log test', ['context' => 'test']);
Log::critical('Critical log test', ['context' => 'test']);
Log::error('Error log test', ['context' => 'test']);
Log::warning('Warning log test', ['context' => 'test']);
Log::notice('Notice log test', ['context' => 'test']);
Log::info('Info log test', ['context' => 'test']);
Log::debug('Debug log test', ['context' => 'test']);

```

### Advanced Logging

```php
use Tfo\AdvancedLog\Support\ALog;
```

### Performance Logging

```php
$startTime = microtime(true);
// Your code here
$duration = (microtime(true) - $startTime) * 1000;
ALog::performance('Process Order', $duration, [
    'order_id' => 123
]);
```

### Audit Logging

```php
ALog::audit('update', 'User', 1, [
    'name' => ['old' => 'John', 'new' => 'Johnny'],
    'email' => ['old' => 'john@example.com', 'new' => 'johnny@example.com']
]);
```

### Security Logging

```php
ALog::security('Login Failed', [
    'email' => 'user@example.com',
    'attempts' => 3
]);
```

### API Logging

```php
$response = response()->json(['status' => 'success']);
ALog::api('/api/users', 'GET', $response, 150.5);
```

### Database Logging

```php
ALog::database('create', 'users', 1, [
    'data' => ['name' => 'John', 'email' => 'john@example.com']
]);
```

### Job Logging

```php
ALog::job('SendWelcomeEmail', 'completed', [
    'user_id' => 1,
    'duration' => 1500
]);
```

### Cache Logging

```php
ALog::cache('hit', 'user:123', [
    'ttl' => 3600
]);
```

### Request Logging

```php
ALog::request('API Request', [
    'endpoint' => '/api/users',
    'params' => ['page' => 1]
]);
```

### Payment Logging

```php
ALog::payment('success', 99.99, 'stripe', [
    'transaction_id' => 'tx_123'
]);
```

### Notification Logging

```php
ALog::notification('email', 'user@example.com', 'welcome', [
    'template' => 'welcome-email'
]);
```

### File Logging

```php
ALog::file('upload', 'images/profile.jpg', [
    'size' => '2.5MB',
    'type' => 'image/jpeg'
]);
```

### Auth Logging

```php
ALog::auth('login_success', [
    'remember' => true,
    'device' => 'iPhone 13'
]);
```

### Export Logging

```php
ALog::export('users', 1000, [
    'format' => 'csv',
    'filters' => ['status' => 'active']
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

Veja [CHANGELOG.md](CHANGELOG-pt-BR.md) para mais informações sobre mudanças recentes.

## Contribuindo

Veja [CONTRIBUTING.md](CONTRIBUTING-pt-BR.md) para detalhes.

## Segurança

Se você descobrir algum problema relacionado à segurança, por favor envie um e-mail para thallesfreitas@yahoo.com.br em vez de usar o issue tracker.

## Créditos

- [Thalles Freitas](https://github.com/thallesfreitas)
- [Todos os Contribuidores](../../contributors)

## Licença

The MIT License (MIT). Veja [License File](LICENSE-pt-BR.md) para mais informações.
