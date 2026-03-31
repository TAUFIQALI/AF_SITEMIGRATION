<?php

if (!defined('ABSPATH')) {
    exit;
}

class ACF_Homepage_Importer_Admin_ImportPage {
    public static function render(): void {
        if (!current_user_can('manage_options')) {
            wp_die('Insufficient permissions.');
        }

        $result = null;
        $source_url = '';
        $target_page_id = 0;
        $dry_run = true;

        if (
            isset($_POST['acf_homepage_importer_nonce']) &&
            wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['acf_homepage_importer_nonce'])), 'acf_homepage_importer_import')
        ) {
            $source_url = isset($_POST['source_url']) ? esc_url_raw(wp_unslash($_POST['source_url'])) : '';
            $target_page_id = isset($_POST['target_page_id']) ? absint($_POST['target_page_id']) : 0;
            $dry_run = !empty($_POST['dry_run']);

            if (isset($_POST['acf_homepage_importer_submit'])) {
                $workflow = new ACF_Homepage_Importer_Workflow_ImportWorkflow();
                $result = $workflow->run($source_url, $target_page_id, $dry_run);
            }
        }

        $pages = get_posts(array(
            'post_type' => 'page',
            'posts_per_page' => -1,
            'orderby' => 'title',
            'order' => 'ASC',
            'post_status' => array('publish', 'draft', 'private'),
        ));
        ?>
        <div class="wrap">
            <h1>ACF Homepage Importer</h1>
            <p>Run a crawl, parse, and dry-run import from the admin screen.</p>
            <form method="post">
                <?php wp_nonce_field('acf_homepage_importer_import', 'acf_homepage_importer_nonce'); ?>
                <table class="form-table">
                    <tr>
                        <th scope="row"><label for="source_url">Source homepage URL</label></th>
                        <td><input name="source_url" id="source_url" type="url" class="regular-text" value="<?php echo esc_attr($source_url); ?>" placeholder="https://example.com" /></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="target_page_id">Target page</label></th>
                        <td>
                            <select name="target_page_id" id="target_page_id">
                                <option value="0">Use front page if available</option>
                                <?php foreach ($pages as $page) : ?>
                                    <option value="<?php echo esc_attr($page->ID); ?>" <?php selected($target_page_id, (int) $page->ID); ?>><?php echo esc_html(get_the_title($page)); ?> (#<?php echo esc_html($page->ID); ?>)</option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">Dry run</th>
                        <td><label><input type="checkbox" name="dry_run" value="1" <?php checked($dry_run); ?> /> Preview only, do not save</label></td>
                    </tr>
                </table>
                <?php submit_button('Run Import Preview', 'primary', 'acf_homepage_importer_submit'); ?>
            </form>

            <?php if (is_array($result)) : ?>
                <hr />
                <h2>Run Summary</h2>
                <?php if (!empty($result['success'])) : ?>
                    <div class="notice notice-success inline"><p>Import completed successfully<?php echo !empty($result['dry_run']) ? ' in dry-run mode.' : '.'; ?></p></div>
                <?php else : ?>
                    <div class="notice notice-error inline"><p><?php echo esc_html($result['error'] ?? 'Import failed.'); ?></p></div>
                <?php endif; ?>

                <table class="widefat striped" style="margin-top:16px;">
                    <tbody>
                        <tr><th>Run ID</th><td><?php echo esc_html($result['run_id'] ?? '-'); ?></td></tr>
                        <tr><th>Source URL</th><td><?php echo esc_html($result['source_url'] ?? $source_url); ?></td></tr>
                        <tr><th>Target Page ID</th><td><?php echo esc_html((string) ($result['page_id'] ?? $target_page_id)); ?></td></tr>
                        <tr><th>Sections Found</th><td><?php echo esc_html((string) count($result['sections'] ?? array())); ?></td></tr>
                        <tr><th>Dry Run</th><td><?php echo !empty($result['dry_run']) ? 'Yes' : 'No'; ?></td></tr>
                    </tbody>
                </table>

                <h3>Artifact Files</h3>
                <pre><?php echo esc_html(print_r($result['artifacts'] ?? array(), true)); ?></pre>

                <h3>Preview Payload</h3>
                <pre><?php echo esc_html(wp_json_encode($result['seed']['payload'] ?? array(), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)); ?></pre>
            <?php endif; ?>
        </div>
        <?php
    }
}
