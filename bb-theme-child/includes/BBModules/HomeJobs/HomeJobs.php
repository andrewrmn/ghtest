<?php

namespace BBModules\HomeJobs;

use BBModules\GHBBModule;

class HomeJobs extends GHBBModule
{
    public function getName (): string
    {
        return 'Featured Jobs';
    }

    public static function getSettings (): array
    {
        $fields = [
            'title' => ['type' => 'text', 'label' => 'Title'],
            'text' => ['type' => 'editor', 'label' => 'Text'],
            'cta_link' => ['type' => 'link', 'label' => 'CTA link', 'show_target' => true],
            'cta_text' => ['type' => 'text', 'label' => 'CTA text'],
        ];

        for ( $i = 0; $i < 3; $i ++ ) {
            $fields['image_'.$i] = ['type' => 'photo', 'label' => 'Picture ' . ($i + 1)];
            $fields['text_'.$i] = ['type' => 'text', 'label' => 'Text ' . ($i + 1)];
            $fields['link_'.$i] = ['type' => 'link', 'label' => 'Link ' . ($i + 1)];
        }

        return [
            'general' => [
                'title' => 'General',
                'sections' => [
                    'general' => [
                        'title' => '',
                        'fields' => $fields
                    ]
                ]
            ]
        ];
    }
}
