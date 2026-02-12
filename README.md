Claro! Aqui está o arquivo **`README.md`** completinho e profissional.

É só clicar em **"Copiar"** no canto do código abaixo, colar no seu arquivo e salvar.

```markdown
# 🐺 Garm Monitor PHP SDK

![PHP Version](https://img.shields.io/badge/php-%5E8.0-777BB4.svg?style=flat-square&logo=php&logoColor=white)
![License](https://img.shields.io/badge/license-MIT-blue.svg?style=flat-square)
![Garm Monitor](https://img.shields.io/badge/Garm-Official%20SDK-0D2538?style=flat-square)

O **SDK Oficial** para integração de aplicações PHP com o **Garm Monitor**.
Monitore erros, logs e exceções em tempo real com facilidade, segurança e zero impacto na performance.

---

## 📋 Requisitos

- **PHP 8.0** ou superior
- Extensão `ext-curl` habilitada
- Extensão `ext-json` habilitada

## 📦 Instalação

Instale a biblioteca via Composer no diretório do seu projeto:

```bash
composer require garm-monitor/garm-php-sdk

```

## 🚀 Como Usar

### 1. Configuração Inicial

Inicialize o cliente no ponto de entrada da sua aplicação (ex: `index.php`, `bootstrap.php` ou `AppServiceProvider` no Laravel).

```php
use Garm\Client;

// Inicialize com seu Token de Projeto (Disponível no Dashboard do Garm)
$garm = new Client('SEU_TOKEN_AQUI', [
    'timeout' => 2, // (Opcional) Tempo limite em segundos para não travar sua aplicação
    'base_url' => '[https://api.garm-monitor.com.br](https://api.garm-monitor.com.br)' // (Opcional) URL da API
]);

```

### 2. Monitorando Erros (Try/Catch)

Esta é a forma recomendada para capturar falhas críticas. O SDK envia o erro completo, incluindo arquivo e linha.

```php
try {
    // Seu código crítico aqui...
    // ex: $db->connect();
} catch (\Exception $e) {
    // O SDK captura o erro e envia para o painel
    $garm->critical('Falha crítica no processamento', [
        'erro'    => $e->getMessage(),
        'arquivo' => $e->getFile(),
        'linha'   => $e->getLine(),
        'user_id' => 123 // Você pode enviar dados personalizados do seu negócio
    ]);
}

```

### 3. Logs Simples

Você também pode usar o Garm para monitorar eventos de rotina:

```php
$garm->info('Novo usuário registrado', ['email' => 'cliente@email.com']);
$garm->warning('Tentativa de login falhou', ['ip' => $_SERVER['REMOTE_ADDR']]);

```

## 🛠️ Funcionalidades Automáticas

O SDK enriquece seus logs automaticamente com metadados para facilitar o debug:

* ✅ Versão do PHP
* ✅ IP do Servidor
* ✅ URL/URI da requisição
* ✅ Método HTTP (GET, POST, etc.)

## 🎚️ Níveis de Log Disponíveis

| Método | Descrição |
| --- | --- |
| `$garm->info()` | Informações gerais e eventos de sucesso. |
| `$garm->warning()` | Alertas que não param o sistema, mas exigem atenção. |
| `$garm->error()` | Erros padrão que afetam uma funcionalidade. |
| `$garm->critical()` | Erros graves que exigem atenção imediata (ex: Banco caiu). |

## 📄 Licença

Este projeto está licenciado sob a licença **MIT** - veja o arquivo [LICENSE](https://www.google.com/search?q=LICENSE) para mais detalhes.

```

```