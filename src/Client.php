<?php

namespace Garm;

use Exception;

class Client
{
    private string $token;
    private string $baseUrl;
    private int $timeout;
    private bool $enabled;

    /**
     * @param string $token O Token de API do Sistema (X-Garm-Token)
     * @param array $options Opções extras [base_url, timeout, enabled]
     */
    public function __construct(string $token, array $options = [])
    {
        $this->token = $token;
        // Permite mudar a URL (útil para dev/homolog)
        $this->baseUrl = rtrim($options['base_url'] ?? 'https://garm-monitor.com.br/api', '/');
        $this->timeout = $options['timeout'] ?? 2; // 2 segundos máx para não travar o cliente
        $this->enabled = $options['enabled'] ?? true;
    }

    /**
     * Captura um evento e envia para o Garm
     */
    public function capture(string $level, string $message, array $context = []): bool
    {
        if (!$this->enabled) {
            return false;
        }

        // 1. Injeta Metadados Automáticos
        $payload = array_merge($this->getSystemContext(), $context);

        $data = json_encode([
            'level' => $level,
            'message' => $message,
            'payload' => $payload,
        ]);

        return $this->sendRequest($data);
    }

    /**
     * Envia a requisição via cURL
     */
    private function sendRequest(string $jsonData): bool
    {
        $ch = curl_init($this->baseUrl . '/logs');
        
        curl_setopt_array($ch, [
            CURLOPT_POST => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Accept: application/json',
                'X-Garm-Token: ' . $this->token,
                'User-Agent: Garm-PHP-SDK/1.0'
            ],
            CURLOPT_POSTFIELDS => $jsonData,
            CURLOPT_TIMEOUT => $this->timeout,
            CURLOPT_CONNECTTIMEOUT => 1,
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        // Se quiser, você pode logar erros do próprio SDK em um arquivo local
        // mas nunca deve jogar uma Exception que pare o site do cliente.
        if ($error || $httpCode >= 400) {
            return false;
        }

        return true;
    }

    /**
     * Coleta dados do ambiente automaticamente
     */
    private function getSystemContext(): array
    {
        return [
            '_meta' => [
                'php_version' => PHP_VERSION,
                'server_ip' => $_SERVER['SERVER_ADDR'] ?? null,
                'client_ip' => $_SERVER['REMOTE_ADDR'] ?? null,
                'uri' => $_SERVER['REQUEST_URI'] ?? 'cli',
                'method' => $_SERVER['REQUEST_METHOD'] ?? 'cli',
                'timestamp' => date('c')
            ]
        ];
    }

    // --- Helpers ---

    public function info(string $message, array $context = []): bool
    {
        return $this->capture('info', $message, $context);
    }

    public function warning(string $message, array $context = []): bool
    {
        return $this->capture('warning', $message, $context);
    }

    public function error(string $message, array $context = []): bool
    {
        return $this->capture('error', $message, $context);
    }

    public function critical(string $message, array $context = []): bool
    {
        return $this->capture('critical', $message, $context);
    }
}