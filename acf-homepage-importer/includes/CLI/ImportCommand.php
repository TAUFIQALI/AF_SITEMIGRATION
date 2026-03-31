<?php

if (!defined('ABSPATH')) {
    exit;
}

class ACF_Homepage_Importer_CLI_ImportCommand {
    public function __invoke($args, $assoc_args): void {
        $url = isset($assoc_args['url']) ? (string) $assoc_args['url'] : '';
        $pageId = isset($assoc_args['page_id']) ? absint($assoc_args['page_id']) : 0;
        $dryRun = !empty($assoc_args['dry-run']) || !empty($assoc_args['dry_run']);

        $workflow = new ACF_Homepage_Importer_Workflow_ImportWorkflow();
        $result = $workflow->import($url, $pageId, $dryRun);

        if (empty($result['success'])) {
            WP_CLI::error($result['error'] ?? 'Import failed.');
        }

        WP_CLI::line(wp_json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    }
}
