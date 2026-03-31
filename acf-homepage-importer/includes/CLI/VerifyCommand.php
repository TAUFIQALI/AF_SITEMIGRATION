<?php

if (!defined('ABSPATH')) {
    exit;
}

class ACF_Homepage_Importer_CLI_VerifyCommand {
    public function __invoke($args, $assoc_args): void {
        $url = isset($assoc_args['url']) ? (string) $assoc_args['url'] : '';
        $pageId = isset($assoc_args['page_id']) ? absint($assoc_args['page_id']) : 0;

        $workflow = new ACF_Homepage_Importer_Workflow_ImportWorkflow();
        $result = $workflow->verify($url, $pageId);

        if (empty($result['success']) && empty($result['report'])) {
            WP_CLI::error($result['error'] ?? 'Verification failed.');
        }

        WP_CLI::line(wp_json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    }
}
