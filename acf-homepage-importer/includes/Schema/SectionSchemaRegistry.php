<?php

if (!defined('ABSPATH')) {
    exit;
}

class ACF_Homepage_Importer_Schema_SectionSchemaRegistry {
    public static function get_field_group_key(): string {
        return 'group_acf_homepage_importer_homepage_sections';
    }

    public static function get_flexible_field_key(): string {
        return 'field_acf_homepage_importer_homepage_sections';
    }

    public static function get_layout_keys(): array {
        return array(
            'hero' => 'layout_acf_homepage_importer_hero',
            'services' => 'layout_acf_homepage_importer_services',
            'about' => 'layout_acf_homepage_importer_about',
            'cta' => 'layout_acf_homepage_importer_cta',
            'custom' => 'layout_acf_homepage_importer_custom',
        );
    }

    public static function get_layout_definitions(): array {
        $keys = self::get_layout_keys();

        return array(
            array(
                'key' => $keys['hero'],
                'name' => 'hero',
                'label' => 'Hero',
                'sub_fields' => array(
                    self::text_field('field_acf_homepage_importer_hero_heading', 'heading', 'Heading', true),
                    self::text_field('field_acf_homepage_importer_hero_subheading', 'subheading', 'Subheading'),
                    self::text_field('field_acf_homepage_importer_hero_button_text', 'button_text', 'Button Text'),
                    self::text_field('field_acf_homepage_importer_hero_button_link', 'button_link', 'Button Link'),
                    self::image_field('field_acf_homepage_importer_hero_background_image', 'background_image', 'Background Image'),
                ),
            ),
            array(
                'key' => $keys['services'],
                'name' => 'services',
                'label' => 'Services',
                'sub_fields' => array(
                    self::text_field('field_acf_homepage_importer_services_heading', 'heading', 'Heading', true),
                    self::repeater_field(),
                ),
            ),
            array(
                'key' => $keys['about'],
                'name' => 'about',
                'label' => 'About',
                'sub_fields' => array(
                    self::text_field('field_acf_homepage_importer_about_heading', 'heading', 'Heading', true),
                    self::textarea_field('field_acf_homepage_importer_about_description', 'description', 'Description'),
                    self::image_field('field_acf_homepage_importer_about_image', 'image', 'Image'),
                    self::text_field('field_acf_homepage_importer_about_button_text', 'button_text', 'Button Text'),
                    self::text_field('field_acf_homepage_importer_about_button_link', 'button_link', 'Button Link'),
                ),
            ),
            array(
                'key' => $keys['cta'],
                'name' => 'cta',
                'label' => 'Call to Action',
                'sub_fields' => array(
                    self::text_field('field_acf_homepage_importer_cta_heading', 'heading', 'Heading', true),
                    self::textarea_field('field_acf_homepage_importer_cta_description', 'description', 'Description'),
                    self::text_field('field_acf_homepage_importer_cta_button_text', 'button_text', 'Button Text'),
                    self::text_field('field_acf_homepage_importer_cta_button_link', 'button_link', 'Button Link'),
                    self::text_field('field_acf_homepage_importer_cta_phone', 'phone', 'Phone'),
                ),
            ),
            array(
                'key' => $keys['custom'],
                'name' => 'custom',
                'label' => 'Custom',
                'sub_fields' => array(
                    self::text_field('field_acf_homepage_importer_custom_heading', 'heading', 'Heading'),
                    self::textarea_field('field_acf_homepage_importer_custom_raw_html', 'raw_html', 'Raw HTML'),
                    self::textarea_field('field_acf_homepage_importer_custom_notes', 'notes', 'Notes'),
                ),
            ),
        );
    }

    protected static function text_field(string $key, string $name, string $label, bool $required = false): array {
        return array(
            'key' => $key,
            'label' => $label,
            'name' => $name,
            'type' => 'text',
            'required' => $required ? 1 : 0,
        );
    }

    protected static function textarea_field(string $key, string $name, string $label, bool $required = false): array {
        return array(
            'key' => $key,
            'label' => $label,
            'name' => $name,
            'type' => 'textarea',
            'rows' => 4,
            'required' => $required ? 1 : 0,
        );
    }

    protected static function image_field(string $key, string $name, string $label, bool $required = false): array {
        return array(
            'key' => $key,
            'label' => $label,
            'name' => $name,
            'type' => 'image',
            'return_format' => 'id',
            'preview_size' => 'medium',
            'library' => 'all',
            'required' => $required ? 1 : 0,
        );
    }

    protected static function repeater_field(): array {
        return array(
            'key' => 'field_acf_homepage_importer_services_items',
            'label' => 'Items',
            'name' => 'items',
            'type' => 'repeater',
            'layout' => 'row',
            'button_label' => 'Add Item',
            'sub_fields' => array(
                self::text_field('field_acf_homepage_importer_services_item_title', 'title', 'Title', true),
                self::textarea_field('field_acf_homepage_importer_services_item_description', 'description', 'Description'),
                self::image_field('field_acf_homepage_importer_services_item_image', 'image', 'Image'),
                self::text_field('field_acf_homepage_importer_services_item_link', 'link', 'Link'),
            ),
        );
    }
}
