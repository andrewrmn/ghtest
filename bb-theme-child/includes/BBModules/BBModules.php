<?php

namespace BBModules;

use BBModules\Blurbs\Blurbs;
use BBModules\HomeHero\HomeHero;
use BBModules\HomeJobs\HomeJobs;
use BBModules\JobListingHeader\JobListingHeader;
use BBModules\MediaText\MediaText;
use BBModules\Testimonials\Testimonials;

class BBModules
{
    public static function start() : void
    {
        add_action('init', [self::class, '_startModules']);
    }

    public static function _startModules() : void
    {
        if( !class_exists('FLBuilder') ) {
            return;
        }

        HomeHero::register();
        JobListingHeader::register();
        MediaText::register();
        Blurbs::register();
        HomeJobs::register();
        Testimonials::register();

    }
}