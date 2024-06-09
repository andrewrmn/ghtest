<?php
namespace BBModules\MediaText;

use BBModules\GHBBModule;

class MediaText extends GHBBModule
{
    function getName (): string
    {
        return "Media and Text";
    }

    static function getSettings (): array
    {
        return [
            'content' => [
                'title' => 'Content',
                'sections' => [
                    'content' => [
                        'title' => 'Content',
                        'fields' => [
                            'title' => ['type' => 'text', 'label' => 'Title'],
                            'subtitle' => ['type' => 'text', 'label' => 'Subtitle'],
                            'text' => ['type' => 'editor', 'label' => 'Text'],
                            'cta_url' => ['type' => 'link', 'label' => 'CTA Link', 'show_target' => true],
                            'cta_text' => ['type' => 'text', 'label' => 'CTA text']
                        ]
                    ],
                    'image' => [
                        'title' => 'Image',
                        'fields' => [
                            'image' => ['type' => 'photo', 'label' => 'Image'],
                            'image_position' => [
                                'type' => 'select',
                                'label' => 'Image position',
                                'default' => 'left',
                                'options' => [
                                    'left' => 'Left',
                                    'right' => 'Right'
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ];
    }
}
