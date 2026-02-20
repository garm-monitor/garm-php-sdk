# 🐺 Garm Monitor PHP SDK

![PHP Version](https://img.shields.io/badge/php-%5E8.0-777BB4.svg?style=flat-square&logo=php&logoColor=white)
![License](https://img.shields.io/badge/license-MIT-blue.svg?style=flat-square)

O **Garm Monitor** é uma plataforma de observabilidade focada em simplicidade e eficiência. Este SDK permite monitorar aplicações PHP em tempo real, capturando desde erros fatais automáticos até métricas de negócio personalizadas.

---

## 🚀 Instalação

Instale a biblioteca via Composer:

```bash
composer require garm-monitor/garm-php-sdk
🛠️ Configuração HíbridaO Garm oferece dois modos de operação que trabalham em conjunto para garantir cobertura total da sua aplicação:1. Modo Vigia (Monitoramento Automático)Ideal para sistemas legados ou para garantir que nada escape. Com apenas uma linha, o Garm captura erros nativos do PHP, exceções não tratadas e até erros fatais de memória (Shutdown).PHPrequire 'vendor/autoload.php';

use Garm\Sdk\GarmClient;

$garm = new GarmClient('SEU_TOKEN_AQUI');

// Ativa a captura global em todo o sistema (Vigia)
$garm->registerAsGlobalHandler();

// A partir daqui, qualquer erro não tratado será enviado ao Garm!
2. Modo Investigador (Captura Manual)Para funcionalidades críticas (como checkouts ou integrações de API), use o modo manual para enviar payloads personalizados. Isso permite entender o contexto do erro (ex: qual usuário ou pedido falhou).PHPtry {
    $checkout = $order->process();
} catch (\Exception $e) {
    // Flexibilidade total para enviar dados do seu negócio
    $garm->critical("Falha no Checkout", [
        'order_id' => 1025,
        'user_email' => 'cliente@email.com',
        'gateway_error' => $e->getMessage()
    ]);
}
📊 Funcionalidades AutomáticasO SDK enriquece cada log automaticamente com metadados cruciais para o debug:✅ Versão do PHP e IP do Servidor.✅ Contexto HTTP: URL da requisição (URI), Método (GET/POST) e IP do cliente.✅ Stack Trace: Rastro completo do erro em capturas automáticas.✅ Zero Impacto: Timeout configurável para não travar a experiência do usuário.🎚️ Níveis de Log DisponíveisMétodoDescriçãoAlerta Discord$garm->info()Informações gerais e eventos de sucesso.⚪$garm->warning()Alertas que exigem atenção, mas não param o sistema.⚪$garm->error()Erros padrão que afetam uma funcionalidade.⚪$garm->critical()Falhas graves. Exige atenção imediata.🔴 Sim⚙️ Opções do ConstrutorVocê pode ajustar o comportamento do SDK no momento da inicialização:PHP$options = [
    'base_url' => '[https://api.garm-monitor.com.br](https://api.garm-monitor.com.br)', // URL da sua API
    'timeout'  => 2,                                // Tempo limite da requisição (segundos)
    'enabled'  => true                              // Útil para desativar em ambiente local
];

$garm = new GarmClient('SEU_TOKEN_AQUI', $options);