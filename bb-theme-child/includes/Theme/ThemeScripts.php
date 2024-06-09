<?php
namespace Theme;
class ThemeScripts
{
    public static function start() : void
    {
        add_action('wp_enqueue_scripts', [self::class, 'enqueueScripts']);
    }

    public static function enqueueScripts() : void
    {
        $theme = wp_get_theme();
        $version = $theme->version;

        wp_enqueue_style('gifted-health-style', FL_CHILD_THEME_URL . '/assets/css/app.css', ['fl-child-theme'], $version );

        wp_enqueue_script('gifted-health-scripts', FL_CHILD_THEME_URL . '/assets/js/main.min.js', ['jquery'], $version );
    }
}