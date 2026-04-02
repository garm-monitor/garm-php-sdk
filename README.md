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

🛠️ Configuração Global
O Garm utiliza o padrão Singleton. Você configura uma única vez no início da sua aplicação (ex: index.php ou AppServiceProvider.php) e o monitoramento já começa a rodar sozinho.


```bash
require 'vendor/autoload.php';

use Garm\Sdk\GarmClient;

// Inicializa o SDK com seu Token do Painel Garm
GarmClient::init('SEU_TOKEN_AQUI', [
    'base_url' => '[https://monitor.seusistema.com.br/api](https://monitor.seusistema.com.br/api)', // URL do seu Garm Monitor
    'timeout'  => 2                                        // Opcional
]);

// Ativa o Vigia Automático (Opcional, mas recomendado)
GarmClient::getInstance()->registerAsGlobalHandler();
```

# 🐺 Uso Universal (Helper Global)
Após a inicialização, você pode acionar o Garm em qualquer lugar do seu código, seja pela instância da classe ou por um helper global (se configurado).

1. Envio de Logs Simples:
```bash
// Via Helper
garm()->info('Pagamento processado com sucesso');

// Via Instância
GarmClient::getInstance()->warning('Tentativa de login inválida', ['ip' => '192.168.0.1']);
```

2. Notificações Dinâmicas no Discord (Alerta em Tempo Real):
Você pode forçar qualquer log a disparar um alerta imediato no seu servidor do Discord passando true no último parâmetro da função!
```bash
// garm()->warning($mensagem, $contextoArray, $notificarDiscord);
garm()->warning('Alerta de Segurança: Múltiplas falhas de login!', ['user' => 'admin'], true);
```

# 🛡️ Monitoramento Automático (Vigia)
Ao chamar o registerAsGlobalHandler(), o SDK ativa automaticamente a captura de:

- Exceções não tratadas: Falhas não abraçadas por blocos try/catch.

- Erros de Sintaxe e Avisos do PHP.

- Erros Fatais (Shutdown): Como estouro de memória (Out of Memory) ou timeout do servidor.
Nota: Todos os erros globais e fatais disparam notificações automáticas no Discord (se o Webhook estiver configurado no painel).

# 📊 Inteligência de Dados


Cada pacote enviado ao Garm Monitor é enriquecido automaticamente com metadados cruciais para a análise do seu SOC (Security Operations Center):

- Contexto de Rede: IP do Servidor.

- Contexto HTTP: URI da requisição e Método (GET, POST, etc).

- Ambiente: Versão exata do PHP e Timestamp preciso (ISO 8601).

# ⚙️ Níveis de Log e Alertas

- info(): Eventos de rotina.

- warning(): Alertas que não param o sistema.

- error(): Falhas em funcionalidades específicas.

- critical(): Dispara alerta imediato no Discord (configurado no dashboard).