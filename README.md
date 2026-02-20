# 🐺 Garm Monitor PHP SDK

![PHP Version](https://img.shields.io/badge/php-%5E8.0-777BB4.svg?style=flat-square&logo=php&logoColor=white)
![License](https://img.shields.io/badge/license-MIT-blue.svg?style=flat-square)

O **Garm Monitor** é uma plataforma de observabilidade focada em simplicidade e eficiência. Este SDK permite que você monitore aplicações PHP em tempo real, capturando desde erros fatais até métricas de negócio personalizadas.

---

## 🚀 Instalação

```bash
composer require garm-monitor/garm-php-sdk
🛠️ Configuração HíbridaO Garm oferece dois modos de operação que podem (e devem) trabalhar juntos:1. Modo Vigia (Monitoramento Automático)Ideal para sistemas legados ou para garantir que nada escape. Com apenas uma linha, o Garm captura todos os erros nativos do PHP, exceções não tratadas e até erros fatais de memória.PHPuse Garm\Sdk\GarmClient;

$garm = new GarmClient('SEU_TOKEN_AQUI');

// Ativa a captura global em todo o sistema
$garm->registerAsGlobalHandler();
2. Modo Investigador (Captura Manual com Contexto)Para funcionalidades críticas (como checkouts ou integrações de API), use o modo manual para enviar payloads personalizados e entender exatamente o que aconteceu.PHPtry {
    $checkout = $order->process();
} catch (\Exception $e) {
    // Flexibilidade total para enviar dados do seu negócio
    $garm->critical("Falha no Checkout", [
        'order_id' => 1025,
        'user_email' => 'cliente@email.com',
        'gateway_error' => $e->getMessage()
    ]);
}
📊 Por que usar o Garm?RecursoDescriçãoMonitoramento PassivoCaptura erros sem que você precise alterar códigos antigos.Payload RicoEnvie variáveis de contexto para debugar falhas de lógica.Metadados AutomáticosIP, URL, Versão do PHP e Método HTTP são colhidos em cada log.Alertas DiscordLogs de nível critical geram notificações instantâneas no seu canal.⚙️ Opções do ConstrutorPHP$options = [
    'base_url' => '[https://sua-api.com/api](https://sua-api.com/api)', // Opcional
    'timeout'  => 3,                          // Padrão: 2s
    'enabled'  => true                        // Útil para desativar em ambiente local
];

$garm = new GarmClient('TOKEN', $options);