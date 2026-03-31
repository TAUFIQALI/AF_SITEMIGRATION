<?php

if (!defined('ABSPATH')) {
    exit;
}

class ACF_Homepage_Importer_Parser_SectionItemMapper {
    protected ACF_Homepage_Importer_Parser_ContentNormalizer $normalizer;

    public function __construct(?ACF_Homepage_Importer_Parser_ContentNormalizer $normalizer = null) {
        $this->normalizer = $normalizer ?: new ACF_Homepage_Importer_Parser_ContentNormalizer();
    }

    public function map(array $sections): array {
        $payload = array();

        foreach ($sections as $section) {
            if (!is_array($section)) {
                continue;
            }

            $normalized = $this->normalizer->normalize($section);
            $payload[] = $this->mapSection($normalized);
        }

        return array('homepage_sections' => $payload);
    }

    protected function mapSection(array $section): array {
        $type = $section['type'] ?? 'custom';
        $content = $section['content'] ?? array();

        $mapped = array('acf_fc_layout' => $type);

        switch ($type) {
            case 'hero':
                $mapped['heading'] = $content['heading'] ?? '';
                $mapped['subheading'] = $content['subheading'] ?? '';
                $mapped['button_text'] = $content['button_text'] ?? '';
                $mapped['button_link'] = $content['button_link'] ?? '';
                $mapped['background_image'] = $this->firstImageId($section);
                break;

            case 'services':
                $mapped['heading'] = $content['heading'] ?? '';
                $mapped['items'] = array();
                break;

            case 'about':
                $mapped['heading'] = $content['heading'] ?? '';
                $mapped['description'] = $content['description'] ?? '';
                $mapped['image'] = $this->firstImageId($section);
                $mapped['button_text'] = $content['button_text'] ?? '';
                $mapped['button_link'] = $content['button_link'] ?? '';
                break;

            case 'cta':
                $mapped['heading'] = $content['heading'] ?? '';
                $mapped['description'] = $content['description'] ?? '';
                $mapped['button_text'] = $content['button_text'] ?? '';
                $mapped['button_link'] = $content['button_link'] ?? '';
                $mapped['phone'] = $content['phone'] ?? '';
                break;

            default:
                $mapped['heading'] = $content['heading'] ?? '';
                $mapped['raw_html'] = $content['raw_html'] ?? '';
                $mapped['notes'] = $content['notes'] ?? array();
                break;
        }

        return $mapped;
    }

    protected function firstImageId(array $section): int {
        $images = $section['assets']['images'] ?? array();

        if (!is_array($images) || empty($images)) {
            return 0;
        }

        $first = reset($images);

        return is_array($first) && isset($first['id']) ? (int) $first['id'] : 0;
    }
}
