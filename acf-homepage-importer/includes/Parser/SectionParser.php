<?php

if (!defined('ABSPATH')) {
    exit;
}

class ACF_Homepage_Importer_Parser_SectionParser {
    protected ACF_Homepage_Importer_Parser_DomParser $domParser;

    public function __construct(?ACF_Homepage_Importer_Parser_DomParser $domParser = null) {
        $this->domParser = $domParser ?: new ACF_Homepage_Importer_Parser_DomParser();
    }

    public function parse(string $html): array {
        $dom = $this->domParser->load($html);
        if (!$dom) {
            return array();
        }

        $sections = array();
        $nodes = $dom->getElementsByTagName('section');

        foreach ($nodes as $node) {
            $text = trim($node->textContent);
            $sections[] = array(
                'type' => $this->detectType($node, $text),
                'confidence' => 0.6,
                'html' => $dom->saveHTML($node),
                'items' => array(
                    'heading' => $this->extractHeading($node),
                    'text' => $text,
                ),
            );
        }

        return $sections;
    }

    protected function detectType(DOMElement $node, string $text): string {
        $class = strtolower($node->getAttribute('class'));
        $text = strtolower($text);

        if (strpos($class, 'hero') !== false || strpos($text, 'hero') !== false) {
            return 'hero';
        }

        if (strpos($class, 'service') !== false || strpos($text, 'service') !== false) {
            return 'services';
        }

        if (strpos($class, 'about') !== false || strpos($text, 'about') !== false) {
            return 'about';
        }

        if (strpos($class, 'cta') !== false || strpos($text, 'contact') !== false) {
            return 'cta';
        }

        return 'custom';
    }

    protected function extractHeading(DOMElement $node): string {
        foreach (array('h1', 'h2', 'h3') as $tag) {
            $list = $node->getElementsByTagName($tag);
            if ($list->length > 0) {
                return trim($list->item(0)->textContent);
            }
        }

        return '';
    }
}
