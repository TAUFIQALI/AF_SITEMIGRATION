<?php

if (!defined('ABSPATH')) {
    exit;
}

class ACF_Homepage_Importer_Parser_ContentNormalizer {
    public function normalize(array $section): array {
        $type = isset($section['type']) ? sanitize_key((string) $section['type']) : 'custom';
        $confidence = isset($section['confidence']) ? (float) $section['confidence'] : 0.0;
        $items = isset($section['items']) && is_array($section['items']) ? $section['items'] : array();

        return array(
            'type' => $type ?: 'custom',
            'confidence' => $confidence,
            'content' => array(
                'heading' => $this->cleanText($items['heading'] ?? ''),
                'subheading' => $this->cleanText($items['subheading'] ?? ($items['text'] ?? '')),
                'description' => $this->cleanText($items['description'] ?? ($items['text'] ?? '')),
                'button_text' => $this->cleanText($items['button_text'] ?? ''),
                'button_link' => $this->cleanText($items['button_link'] ?? ''),
                'phone' => $this->cleanText($items['phone'] ?? ''),
                'raw_html' => $section['html'] ?? '',
                'notes' => array(),
            ),
            'assets' => array(
                'images' => isset($section['images']) && is_array($section['images']) ? $section['images'] : array(),
                'links' => isset($section['links']) && is_array($section['links']) ? $section['links'] : array(),
            ),
            'notes' => array(),
        );
    }

    protected function cleanText($value): string {
        if (!is_string($value)) {
            return '';
        }

        $value = wp_strip_all_tags($value);
        $value = preg_replace('/\s+/', ' ', $value);

        return trim((string) $value);
    }
}
