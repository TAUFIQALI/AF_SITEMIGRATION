<?php

if (!defined('ABSPATH')) {
    exit;
}

class ACF_Homepage_Importer_CLI_ParseCommand {
    public function __invoke($args, $assoc_args): void {
        $url = isset($assoc_args['url']) ? (string) $assoc_args['url'] : '';
        $workflow = new ACF_Homepage_Importer_Workflow_ImportWorkflow();
        $result = $workflow->parse($url);

        if (empty($result['success'])) {
            WP_CLI::error($result['error'] ?? 'Parse failed.');
        }

        WP_CLI::line(wp_json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    }
}
