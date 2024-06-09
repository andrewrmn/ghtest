<?php

namespace BBModules\Testimonials;

use BBModules\GHBBModule;

class Testimonials extends GHBBModule
{
    public function getName (): string
    {
        return "Testimonials";
    }
    public static function getSettings (): array
    {

        \FLBuilder::register_settings_form('gh_testimonials_form', [
            'title' => 'Testimonial',
            'tabs' => [
                'general' => [
                    'title' => 'General',
                    'sections' => [
                        'general' => [
                            'title' => '',
                            'fields' => [
                                'image' => ['type' => 'photo', 'label' => 'Icon'],
                                'text' => ['type' => 'editor', 'label' => 'Text'],
                                'author_pic' => ['type' => 'photo', 'label' => 'Author picture'],
                                'author_name' => ['type' => 'text', 'label' => 'Author name']
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
                            'testimonials' => [
                                'type' => 'form',
                                'label' => 'Testimonial',
                                'form' => 'gh_testimonials_form',
                                'preview_text' => 'author_name',
                                'multiple' => true,
                                'limit' => 10
                            ]
                        ]
                    ]
                ]
            ]
        ];
    }

}