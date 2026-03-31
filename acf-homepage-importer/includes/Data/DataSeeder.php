<?php

if (!defined('ABSPATH')) {
    exit;
}

class ACF_Homepage_Importer_Data_DataSeeder {
    protected ACF_Homepage_Importer_Parser_SectionItemMapper $mapper;
    protected ACF_Homepage_Importer_Data_PageRepository $pageRepository;

    public function __construct(
        ?ACF_Homepage_Importer_Parser_SectionItemMapper $mapper = null,
        ?ACF_Homepage_Importer_Data_PageRepository $pageRepository = null
    ) {
        $this->mapper = $mapper ?: new ACF_Homepage_Importer_Parser_SectionItemMapper();
        $this->pageRepository = $pageRepository ?: new ACF_Homepage_Importer_Data_PageRepository();
    }

    public function buildPayload(array $sections): array {
        return $this->mapper->map($sections);
    }

    public function seed(array $sections, int $page_id = 0, bool $dry_run = false): array {
        $target_page_id = $this->pageRepository->findTargetPageId($page_id);
        $payload = $this->buildPayload($sections);

        if ($dry_run) {
            return array(
                'success' => true,
                'dry_run' => true,
                'page_id' => $target_page_id,
                'payload' => $payload,
            );
        }

        if ($target_page_id <= 0) {
            return array(
                'success' => false,
                'dry_run' => false,
                'error' => 'No target page found.',
                'payload' => $payload,
            );
        }

        if (!function_exists('update_field')) {
            return array(
                'success' => false,
                'dry_run' => false,
                'error' => 'ACF update_field() is unavailable.',
                'payload' => $payload,
            );
        }

        $saved = update_field('homepage_sections', $payload['homepage_sections'] ?? array(), $target_page_id);

        return array(
            'success' => (bool) $saved,
            'dry_run' => false,
            'page_id' => $target_page_id,
            'payload' => $payload,
        );
    }
}
