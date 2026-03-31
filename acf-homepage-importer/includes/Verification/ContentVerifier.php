<?php

if (!defined('ABSPATH')) {
    exit;
}

class ACF_Homepage_Importer_Verification_ContentVerifier {
    protected ACF_Homepage_Importer_Verification_DiffBuilder $diffBuilder;

    public function __construct(?ACF_Homepage_Importer_Verification_DiffBuilder $diffBuilder = null) {
        $this->diffBuilder = $diffBuilder ?: new ACF_Homepage_Importer_Verification_DiffBuilder();
    }

    public function verify(array $sections, array $payload, array $seedResult): array {
        $diff = $this->diffBuilder->build($sections, $payload);
        $warnings = array();

        if (empty($seedResult['success'])) {
            $warnings[] = 'Seed step failed or returned an incomplete result.';
        }

        if (empty($diff['count_match'])) {
            $warnings[] = 'Section count mismatch between parsed and saved data.';
        }

        if (empty($diff['type_match'])) {
            $warnings[] = 'Section type mismatch between parsed and saved data.';
        }

        $status = empty($warnings) ? 'pass' : (empty($seedResult['success']) ? 'fail' : 'warn');

        return array(
            'status' => $status,
            'warnings' => $warnings,
            'diff' => $diff,
            'seed_success' => !empty($seedResult['success']),
        );
    }
}
