<?php

if (!defined('ABSPATH')) {
    exit;
}

class ACF_Homepage_Importer_Template_HomepageTemplateRenderer {
    public function hooks(): void {
        add_filter('template_include', array($this, 'maybeRenderHomepageTemplate'));
    }

    public function maybeRenderHomepageTemplate(string $template): string {
        if (!is_front_page()) {
            return $template;
        }

        $page_id = (int) get_option('page_on_front');
        if ($page_id <= 0 || !function_exists('have_rows') || !have_rows('homepage_sections', $page_id)) {
            return $template;
        }

        $pluginTemplate = ACF_HOMEPAGE_IMPORTER_PATH . 'templates/homepage-dynamic.php';
        return file_exists($pluginTemplate) ? $pluginTemplate : $template;
    }
}
