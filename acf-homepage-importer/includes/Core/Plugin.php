<?php

if (!defined('ABSPATH')) {
    exit;
}

class ACF_Homepage_Importer_Core_Plugin {
    public static function activate(): void {
        update_option('acf_homepage_importer_version', ACF_HOMEPAGE_IMPORTER_VERSION);
    }

    public static function deactivate(): void {
        // Reserved for cleanup.
    }

    public function init(): void {
        add_action('admin_menu', array('ACF_Homepage_Importer_Admin_AdminMenu', 'register'));
        add_action('acf/init', array('ACF_Homepage_Importer_Schema_AcfRegistrar', 'register'));

        $renderer = new ACF_Homepage_Importer_Template_HomepageTemplateRenderer();
        $renderer->hooks();

        if (defined('WP_CLI') && WP_CLI) {
            WP_CLI::add_command('acf-homepage-importer test', 'ACF_Homepage_Importer_CLI_TestCommand');
            WP_CLI::add_command('acf-homepage-importer crawl', 'ACF_Homepage_Importer_CLI_CrawlCommand');
            WP_CLI::add_command('acf-homepage-importer parse', 'ACF_Homepage_Importer_CLI_ParseCommand');
            WP_CLI::add_command('acf-homepage-importer import', 'ACF_Homepage_Importer_CLI_ImportCommand');
            WP_CLI::add_command('acf-homepage-importer verify', 'ACF_Homepage_Importer_CLI_VerifyCommand');
        }
    }
}
