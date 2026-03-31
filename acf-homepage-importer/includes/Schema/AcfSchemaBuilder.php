<?php

if (!defined('ABSPATH')) {
    exit;
}

class ACF_Homepage_Importer_Schema_AcfSchemaBuilder {
    public static function build(): array {
        return array(
            'key' => ACF_Homepage_Importer_Schema_SectionSchemaRegistry::get_field_group_key(),
            'title' => 'ACF Homepage Importer — Homepage Sections',
            'fields' => array(
                array(
                    'key' => ACF_Homepage_Importer_Schema_SectionSchemaRegistry::get_flexible_field_key(),
                    'label' => 'Homepage Sections',
                    'name' => 'homepage_sections',
                    'type' => 'flexible_content',
                    'layouts' => ACF_Homepage_Importer_Schema_SectionSchemaRegistry::get_layout_definitions(),
                    'button_label' => 'Add Section',
                    'min' => 0,
                    'max' => 0,
                ),
            ),
            'location' => array(
                array(
                    array(
                        'param' => 'post_type',
                        'operator' => '==',
                        'value' => 'page',
                    ),
                ),
            ),
            'position' => 'normal',
            'style' => 'default',
            'label_placement' => 'top',
            'instruction_placement' => 'label',
            'active' => true,
            'show_in_rest' => 0,
        );
    }
}
