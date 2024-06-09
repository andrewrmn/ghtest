<?php

namespace Theme;

class ThemeSetup{
    public static function start() {

        ThemeScripts::start();
        HeaderAndFooter::start();
        ThemeMenus::start();

        add_action('get_header', [self::class,'removeBump']);
    }

    public static function removeBump() : void
    {
        remove_action('wp_head', '_admin_bar_bump_cb');
    }


}
