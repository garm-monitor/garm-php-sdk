<?php

namespace Garm\Sdk;

use Throwable;

class GarmClient
{
    private string $token;
    private string $baseUrl;
    private int $timeout;
    private bool $enabled;

    public function __construct(string $token, array $options = [])
    {
        $this->token = $token;
        // Se base_url não for enviada, assume o servidor local do Laravel
        $this->baseUrl = rtrim($options['base_url'] ?? 'http://127.0.0.1:8000/api', '/');
        $this->timeout = $options['timeout'] ?? 2;
        $this->enabled = $options['enabled'] ?? true;
    }

    /**
     * MODO GLOBAL
     * Registra os handlers para capturar erros que NÃO estão em try/catch.
     */
    public function registerAsGlobalHandler(): void
    {
        set_exception_handler([$this, 'handleException']);
        set_error_handler([$this, 'handleError']);
        register_shutdown_function([$this, 'handleShutdown']);
    }

    /**
     * Handler para Exceções Globais
     */
    public function handleException(Throwable $e): void
    {
        $this->critical("Exceção não tratada: " . $e->getMessage(), [
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => substr($e->getTraceAsString(), 0, 1000),
            'type' => get_class($e),
            'mode' => 'automatic'
        ]);
    }

    /**
     * Handler para Erros de Sintaxe/Aviso do PHP
     */
    public function handleError($level, $message, $file, $line): bool
    {
        $this->error("Erro PHP ($level): $message", [
            'file' => $file,
            'line' => $line,
            'mode' => 'automatic'
        ]);
        return false; 
    }

    /**
     * Handler para Erros Fatais (Shutdown)
     */
    public function handleShutdown(): void
    {
        $error = error_get_last();
        if ($error !== null && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
            $this->critical("Erro Fatal: " . $error['message'], [
                'file' => $error['file'],
                'line' => $error['line'],
                'mode' => 'shutdown'
            ]);
        }
    }

    /**
     * 
     * Captura um evento com payload personalizado.
     */
    public function capture(string $level, string $message, array $context = []): bool
    {
        if (!$this->enabled) return false;

        // Mescla metadados automáticos com o contexto personalizado do desenvolvedor
        $payload = array_merge($this->getSystemContext(), ['custom_data' => $context]);

        $data = json_encode([
            'level' => $level,
            'message' => $message,
            'payload' => $payload,
        ]);

        return $this->sendRequest($data);
    }

    private function sendRequest(string $jsonData): bool
    {
        $ch = curl_init($this->baseUrl . '/logs');
        
        curl_setopt_array($ch, [
            CURLOPT_POST => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'X-Garm-Token: ' . $this->token,
                'User-Agent: Garm-PHP-SDK/1.1'
            ],
            CURLOPT_POSTFIELDS => $jsonData,
            CURLOPT_TIMEOUT => $this->timeout,
            CURLOPT_CONNECTTIMEOUT => 1,
            CURLOPT_SSL_VERIFYPEER => false // Importante para dev local
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return ($httpCode >= 200 && $httpCode < 300);
    }

    private function getSystemContext(): array
    {
        return [
            '_meta' => [
                'php_version' => PHP_VERSION,
                'server_ip' => $_SERVER['SERVER_ADDR'] ?? '127.0.0.1',
                'uri' => $_SERVER['REQUEST_URI'] ?? 'cli',
                'method' => $_SERVER['REQUEST_METHOD'] ?? 'cli',
                'timestamp' => date('c')
            ]
        ];
    }

    // Métodos auxiliares para facilitar a vida do Dev
    public function info(string $m, array $c = []) { return $this->capture('info', $m, $c); }
    public function warning(string $m, array $c = []) { return $this->capture('warning', $m, $c); }
    public function error(string $m, array $c = []) { return $this->capture('error', $m, $c); }
    public function critical(string $m, array $c = []) { return $this->capture('critical', $m, $c); }
}