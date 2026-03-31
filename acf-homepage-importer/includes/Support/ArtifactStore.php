<?php

if (!defined('ABSPATH')) {
    exit;
}

class ACF_Homepage_Importer_Support_ArtifactStore {
    protected string $baseDir;

    public function __construct(?string $baseDir = null) {
        $this->baseDir = $baseDir ?: trailingslashit(ACF_HOMEPAGE_IMPORTER_PATH . 'storage');
    }

    public function storeRun(array $artifacts, string $runId): array {
        $runDir = trailingslashit($this->baseDir . 'imports/' . $runId);

        if (!function_exists('wp_mkdir_p')) {
            require_once ABSPATH . 'wp-admin/includes/file.php';
        }

        wp_mkdir_p($runDir);

        $paths = array();
        foreach ($artifacts as $name => $data) {
            $path = $runDir . sanitize_file_name($name) . '.json';
            file_put_contents($path, wp_json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
            $paths[$name] = $path;
        }

        return array(
            'run_id' => $runId,
            'directory' => $runDir,
            'paths' => $paths,
        );
    }
}
