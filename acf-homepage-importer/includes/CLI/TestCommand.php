<?php

if (!defined('ABSPATH')) {
    exit;
}

class ACF_Homepage_Importer_CLI_TestCommand {
    public function __invoke($args, $assoc_args): void {
        WP_CLI::success('ACF Homepage Importer is loaded and ready.');
    }
}
