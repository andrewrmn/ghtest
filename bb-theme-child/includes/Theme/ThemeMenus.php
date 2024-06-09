<?php

namespace Theme;

class ThemeMenus
{
    const MAIN_MENU = 'gh-main-menu';
    const FOOTER_MENU = 'gh-footer-menu';

    public static function start() : void
    {
        register_nav_menus([
            self::MAIN_MENU => 'Gifted Healthcare: Main Menu',
            self::FOOTER_MENU => 'Gifted Healthcare: Footer Menu',
        ]);
    }

    public static function mainMenu( $menu_class = 'mainNav') : void
    {
        wp_nav_menu([
            'theme_location' => self::MAIN_MENU,
            'container' => false,
            'menu_class' => $menu_class
        ]);
    }

    public static function footerMenu() : void
    {
        wp_nav_menu([
            'theme_location' => self::FOOTER_MENU,
            'container' => false,
            'menu_class' => 'footerNav'
        ]);
    }

}
