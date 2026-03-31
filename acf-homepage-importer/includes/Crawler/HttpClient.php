<?php

if (!defined('ABSPATH')) {
    exit;
}

class ACF_Homepage_Importer_Crawler_HttpClient {
    public function fetch(string $url): array {
        $response = wp_remote_get($url, array(
            'timeout' => 20,
            'redirection' => 5,
            'user-agent' => 'ACF Homepage Importer/0.1.0',
        ));

        if (is_wp_error($response)) {
            return array('success' => false, 'error' => $response->get_error_message());
        }

        return array(
            'success' => true,
            'status' => wp_remote_retrieve_response_code($response),
            'body' => wp_remote_retrieve_body($response),
            'headers' => wp_remote_retrieve_headers($response),
        );
    }
}
