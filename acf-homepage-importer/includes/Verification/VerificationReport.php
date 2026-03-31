<?php

if (!defined('ABSPATH')) {
    exit;
}

class ACF_Homepage_Importer_Verification_VerificationReport {
    public function build(array $context): array {
        return array(
            'run_id' => $context['run_id'] ?? '',
            'source_url' => $context['source_url'] ?? '',
            'page_id' => $context['page_id'] ?? 0,
            'dry_run' => !empty($context['dry_run']),
            'status' => $context['status'] ?? 'warn',
            'warnings' => $context['warnings'] ?? array(),
            'diff' => $context['diff'] ?? array(),
            'created_at' => gmdate('c'),
        );
    }
}
