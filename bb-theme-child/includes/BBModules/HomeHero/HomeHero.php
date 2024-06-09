<?php
namespace BBModules\HomeHero;

use BBModules\GHBBModule;

class HomeHero extends GHBBModule
{

    function getName (): string
    {
        return 'Home Hero';
    }

    static function getSettings (): array
    {
        return [
            'general' => [
                'title' => 'General',
                'sections' => [
                    'general' => [
                        'title' => 'General',
                        'fields' => [
                            'title' => [
                                'type' => 'text',
                                'label' => 'Title'
                            ],
                            'text' => [
                                'type' => 'textarea',
                                'label' => 'Description'
                            ],
                        ]
                    ]
                ]
            ]
        ];

    }
}
