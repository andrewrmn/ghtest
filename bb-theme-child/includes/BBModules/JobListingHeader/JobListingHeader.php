<?php
namespace BBModules\JobListingHeader;
use BBModules\GHBBModule;

class JobListingHeader extends GHBBModule {

    function getName (): string
    {
        return 'Job Listing Module';
    }

    public static function getSettings (): array
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
                            ]
                        ]
                    ]
                ]
            ]
        ];
    }

}
