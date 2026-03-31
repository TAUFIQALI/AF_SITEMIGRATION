<?php

if (!defined('ABSPATH')) {
    exit;
}

class ACF_Homepage_Importer_Data_PageRepository {
    public function findTargetPageId(int $page_id = 0): int {
        if ($page_id > 0) {
            return $page_id;
        }

        $front_page_id = (int) get_option('page_on_front');

        return $front_page_id > 0 ? $front_page_id : 0;
    }
}
