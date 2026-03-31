<?php

if (!defined('ABSPATH')) {
    exit;
}

class ACF_Homepage_Importer_Admin_AdminMenu {
    public static function register(): void {
        add_menu_page(
            'ACF Homepage Importer',
            'ACF Homepage Importer',
            'manage_options',
            'acf-homepage-importer',
            array('ACF_Homepage_Importer_Admin_ImportPage', 'render'),
            'dashicons-download',
            58
        );

        add_submenu_page(
            'acf-homepage-importer',
            'Import History',
            'Import History',
            'manage_options',
            'acf-homepage-importer-history',
            array('ACF_Homepage_Importer_Admin_HistoryPage', 'render')
        );
    }
}
