# 🐺 Garm Monitor PHP SDK

![PHP Version](https://img.shields.io/badge/php-%5E8.0-777BB4.svg?style=flat-square&logo=php&logoColor=white)
![License](https://img.shields.io/badge/license-MIT-blue.svg?style=flat-square)

O **Garm Monitor** é uma plataforma de observabilidade e segurança focada em simplicidade. Este SDK permite monitorar aplicações PHP de forma universal, capturando desde erros fatais até métricas de SOC e Segurança em tempo real.
---

## 🚀 Instalação

Instale a biblioteca via Composer:

```bash
composer require garm-monitor/garm-php-sdk
```

# 🛠️ Configuração Global
O Garm utiliza o padrão Singleton. Você configura uma única vez no início da sua aplicação (ex: index.php ou AppServiceProvider.php) e o monitoramento já começa a rodar sozinho.


```bash
require 'vendor/autoload.php';

use Garm\Sdk\GarmClient;

// Inicializa o SDK com seu Token do Painel Garm
GarmClient::init('SEU_TOKEN_AQUI', [
    'base_url' => 'https://api.garm-monitor.com.br', // Opcional
    'timeout'  => 2                                  // Opcional
]);
```

# 🐺 Uso Universal (Helper Global)
Após a inicialização, você não precisa mais instanciar classes. Use a função global garm() em qualquer lugar do seu código.

1. Envio Direto
```bash
garm('critical', 'Falha no Checkout', ['user_id' => 123]);
```

2. Envio Encadeado (Estilo Fluent)
```bash
garm()->info('Pagamento processado com sucesso');
garm()->warning('Tentativa de login inválida', ['ip' => '192.168.0.1']);
```

# 🛡️ Monitoramento Automático (Vigia)
Ao chamar o GarmClient::init(), o SDK ativa automaticamente o monitoramento de:

- Exceções não tratadas (try/catch esquecidos).

- Erros de Sintaxe e Avisos do PHP.

- Erros Fatais (Shutdown) como estouro de memória ou timeout do servidor.


# 📊 Inteligência de Dados

Cada log enviado é enriquecido automaticamente com metadados para facilitar o seu trabalho de SOC:

- Contexto de Rede: IP do Servidor e IP do Cliente.

- Contexto HTTP: URI da requisição e Método (GET, POST, etc).

- Ambiente: Versão do PHP e Timestamp preciso.

# ⚙️ Níveis de Log e Alertas

- info(): Eventos de rotina.

- warning(): Alertas que não param o sistema.

- error(): Falhas em funcionalidades específicas.

- critical(): Dispara alerta imediato no Discord (configurado no dashboard).

# 💡 Dica para Desenvolvedores
Para ambiente local, você pode desativar o envio de logs ou ignorar o SSL (já configurado por padrão no SDK para facilitar seu dev local).