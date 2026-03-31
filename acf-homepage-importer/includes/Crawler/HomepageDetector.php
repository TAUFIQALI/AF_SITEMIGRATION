<?php

if (!defined('ABSPATH')) {
    exit;
}

class ACF_Homepage_Importer_Crawler_HomepageDetector {
    public function normalize(string $url): string {
        $url = trim($url);
        if ($url === '') {
            return '';
        }

        if (!preg_match('#^https?://#i', $url)) {
            $url = 'https://' . ltrim($url, '/');
        }

        return esc_url_raw($url);
    }
}
