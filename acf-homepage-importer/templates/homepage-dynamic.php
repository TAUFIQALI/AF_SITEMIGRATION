<?php
/**
 * Dynamic homepage template for ACF Homepage Importer.
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();

$page_id = (int) get_option('page_on_front');

if (have_rows('homepage_sections', $page_id)) :
    while (have_rows('homepage_sections', $page_id)) : the_row();
        $layout = get_row_layout();
        $partial = ACF_HOMEPAGE_IMPORTER_PATH . 'templates/sections/' . $layout . '.php';

        if (file_exists($partial)) {
            include $partial;
        } else {
            include ACF_HOMEPAGE_IMPORTER_PATH . 'templates/sections/custom.php';
        }
    endwhile;
else :
    while (have_posts()) : the_post();
        the_content();
    endwhile;
endif;

get_footer();
