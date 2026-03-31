<?php

if (!defined('ABSPATH')) {
    exit;
}

class ACF_Homepage_Importer_Crawler_HtmlCrawler {
    protected ACF_Homepage_Importer_Crawler_HttpClient $client;

    public function __construct(?ACF_Homepage_Importer_Crawler_HttpClient $client = null) {
        $this->client = $client ?: new ACF_Homepage_Importer_Crawler_HttpClient();
    }

    public function crawl(string $url): array {
        $result = $this->client->fetch($url);
        if (empty($result['success'])) {
            return $result;
        }

        return array(
            'success' => true,
            'url' => $url,
            'html' => $result['body'] ?? '',
            'status' => $result['status'] ?? 0,
        );
    }
}
