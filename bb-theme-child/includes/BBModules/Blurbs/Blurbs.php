<?php

namespace BBModules\Blurbs;

use BBModules\GHBBModule;

class Blurbs extends GHBBModule
{
    public function getName (): string
    {
        return "Blurbs";
    }
    public static function getSettings (): array
    {

        \FLBuilder::register_settings_form('blurbs_form', [
            'title' => 'Blurb',
            'tabs' => [
                'general' => [
                    'title' => 'General',
                    'sections' => [
                        'general' => [
                            'title' => '',
                            'fields' => [
                                'icon' => ['type' => 'photo', 'label' => 'Icon', 'description' => 'Leave empty to use numbers'],
                                'title' => ['type' => 'text', 'label' => 'Title'],
                                'text' => ['type' => 'textarea', 'label' => 'Text'],
                                'link' => ['type' => 'link', 'label' => 'Link URL', 'show_target' => true],
                                'link_text' => ['type' => 'text', 'label' => 'Link text']
                            ]
                        ]
                    ]
                ]
            ]
        ]);

        return [
            'general' => [
                'title' => 'General',
                'sections' => [
                    'general' => [
                        'title' => 'General',
                        'fields' => [
                            'blurbs' => [
                                'type' => 'form',
                                'label' => 'Blurb',
                                'form' => 'blurbs_form',
                                'preview_text' => 'title',
                                'multiple' => true,
                                'limit' => 4
                            ]
                        ]
                    ]
                ]
            ]
        ];
    }

}