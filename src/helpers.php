<?php

use Garm\Sdk\GarmClient;

if (!function_exists('garm')) {
    /**
     * Helper Universal do Garm Monitor
     */
    function garm(string $level = null, string $message = null, array $context = [])
    {
        $instance = GarmClient::getInstance();

        // Se passar argumentos, já executa o capture (ex: garm('info', 'msg'))
        if ($level !== null && $message !== null) {
            return $instance->capture($level, $message, $context);
        }

        // Se não passar nada, retorna o objeto para encadear (ex: garm()->critical('msg'))
        return $instance;
    }
}