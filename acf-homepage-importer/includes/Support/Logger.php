<?php

if (!defined('ABSPATH')) {
    exit;
}

class ACF_Homepage_Importer_Support_Logger {
    protected string $optionName = 'acf_homepage_importer_logs';

    public function log(string $level, string $message, array $context = array()): array {
        $entry = array(
            'time' => gmdate('c'),
            'level' => sanitize_key($level),
            'message' => $message,
            'context' => $context,
        );

        $logs = get_option($this->optionName, array());
        if (!is_array($logs)) {
            $logs = array();
        }

        $logs[] = $entry;
        update_option($this->optionName, $logs, false);

        return $entry;
    }

    public function info(string $message, array $context = array()): array {
        return $this->log('info', $message, $context);
    }

    public function warning(string $message, array $context = array()): array {
        return $this->log('warning', $message, $context);
    }

    public function error(string $message, array $context = array()): array {
        return $this->log('error', $message, $context);
    }
}
