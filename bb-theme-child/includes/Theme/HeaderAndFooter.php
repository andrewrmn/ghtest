<?php
namespace Theme;

class HeaderAndFooter{
    public static function start() {
        if( function_exists('get_field')) {

            // If new header & footer are enabled via "Site Options" options page
            if( get_field('new_header_and_footer', 'options')){

                add_action('wp', [self::class, 'removeBBHeadersAndFooters'], 11);
                add_action('fl_before_header', [self::class, 'printHeader']);
                add_action('fl_after_content', [self::class, 'printFooter']);
            }

        }
    }

    public static function removeBBHeadersAndFooters() : void
    {

        add_filter( 'fl_topbar_enabled', '__return_false' );
        add_filter( 'fl_fixed_header_enabled', '__return_false' );
        add_filter( 'fl_header_enabled', '__return_false' );

        remove_action( 'fl_before_header', 'FLThemeBuilderLayoutRenderer::render_header', 999 );

        add_filter( 'fl_footer_enabled', '__return_false' );
        remove_action( 'fl_after_content', 'FLThemeBuilderSupportBBTheme::render_footer', 11 );
    }

    public static function printHeader() : void
    {
        get_template_part('parts/siteHeader');
    }

    public static function printFooter() : void
    {
        get_template_part('parts/siteFooter');
    }

    public static function getCtaLink() : mixed {

        $default_cta_link = get_field('header_cta','options');

        if( !is_page() ) {
            return $default_cta_link;
        }

        $current_page_id = get_the_ID();
        $conditional_ctas = get_field('conditional_cta', 'options');
        if( empty($conditional_ctas) ) {
            return $default_cta_link;
        }

        foreach ($conditional_ctas as $one_cta_condition) {
            $condition_page_ids = $one_cta_condition['pages'];
            $condition_link = $one_cta_condition['cta_link'];

            if( in_array( $current_page_id, $condition_page_ids )) {
                return $condition_link;
            }
        }

        return $default_cta_link;

    }

    public static function getTopLinks() : mixed
    {
        return get_field('top_bar_links', 'options');
    }

}
