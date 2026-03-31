<?php

if (!defined('ABSPATH')) {
    exit;
}

class ACF_Homepage_Importer_Verification_DiffBuilder {
    public function build(array $sections, array $payload): array {
        $parsedCount = count($sections);
        $savedCount = count($payload['homepage_sections'] ?? array());
        $parsedTypes = $this->countTypes($sections);
        $savedTypes = $this->countSavedTypes($payload['homepage_sections'] ?? array());

        return array(
            'parsed_count' => $parsedCount,
            'saved_count' => $savedCount,
            'count_match' => $parsedCount === $savedCount,
            'parsed_types' => $parsedTypes,
            'saved_types' => $savedTypes,
            'type_match' => $parsedTypes === $savedTypes,
        );
    }

    protected function countTypes(array $sections): array {
        $counts = array();
        foreach ($sections as $section) {
            $type = sanitize_key((string) ($section['type'] ?? 'custom'));
            $counts[$type] = ($counts[$type] ?? 0) + 1;
        }
        ksort($counts);
        return $counts;
    }

    protected function countSavedTypes(array $sections): array {
        $counts = array();
        foreach ($sections as $section) {
            $type = sanitize_key((string) ($section['acf_fc_layout'] ?? 'custom'));
            $counts[$type] = ($counts[$type] ?? 0) + 1;
        }
        ksort($counts);
        return $counts;
    }
}
