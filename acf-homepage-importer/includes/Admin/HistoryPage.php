<?php

if (!defined('ABSPATH')) {
    exit;
}

class ACF_Homepage_Importer_Admin_HistoryPage {
    public static function render(): void {
        if (!current_user_can('manage_options')) {
            wp_die('Insufficient permissions.');
        }

        $jobs = get_option('acf_homepage_importer_jobs', array());
        if (!is_array($jobs)) {
            $jobs = array();
        }
        $jobs = array_reverse($jobs);
        ?>
        <div class="wrap">
            <h1>Import History</h1>
            <p>Recent import runs stored in WordPress options.</p>
            <table class="widefat striped">
                <thead>
                    <tr>
                        <th>Run ID</th>
                        <th>Source URL</th>
                        <th>Page ID</th>
                        <th>Status</th>
                        <th>Dry Run</th>
                        <th>Created</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($jobs)) : ?>
                        <tr><td colspan="6">No import runs yet.</td></tr>
                    <?php else : ?>
                        <?php foreach ($jobs as $job) : ?>
                            <tr>
                                <td><?php echo esc_html($job['run_id'] ?? '-'); ?></td>
                                <td><?php echo esc_html($job['source_url'] ?? '-'); ?></td>
                                <td><?php echo esc_html((string) ($job['page_id'] ?? '-')); ?></td>
                                <td><?php echo esc_html($job['status'] ?? '-'); ?></td>
                                <td><?php echo !empty($job['dry_run']) ? 'Yes' : 'No'; ?></td>
                                <td><?php echo esc_html($job['created_at'] ?? $job['updated_at'] ?? '-'); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php
    }
}
