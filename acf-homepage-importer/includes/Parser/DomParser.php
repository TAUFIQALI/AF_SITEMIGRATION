<?php

if (!defined('ABSPATH')) {
    exit;
}

class ACF_Homepage_Importer_Parser_DomParser {
    public function load(string $html): ?DOMDocument {
        if (trim($html) === '') {
            return null;
        }

        libxml_use_internal_errors(true);
        $dom = new DOMDocument();
        $dom->loadHTML('<?xml encoding="utf-8" ?>' . $html);
        libxml_clear_errors();

        return $dom;
    }
}
