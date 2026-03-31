<?php

if (!defined('ABSPATH')) {
    exit;
}

class ACF_Homepage_Importer_Schema_AcfRegistrar {
    public static function register(): void {
        if (!function_exists('acf_add_local_field_group')) {
            return;
        }

        acf_add_local_field_group(ACF_Homepage_Importer_Schema_AcfSchemaBuilder::build());
    }
}
