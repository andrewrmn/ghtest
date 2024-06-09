<?php
use Theme\ThemeMenus;

$subscribe_form_id = get_field('footer_form_id', 'options');

$socials = [
    'facebook-fill'  => 'facebook_url',
    'linkedin-fill'  => 'linkedin_url',
    'instagram-line' => 'instagram_url',
    'youtube-line'   => 'youtube_url',
];

?>
<div class="siteFooter">
    <div class="gh-container">

        <div class="siteFooter__layout">

            <div class="siteFooter__info">

                <div class="siteFooter__logo">
                    <a href="/" rel="home">
                        <?php get_template_part('parts/logo'); ?>
                    </a>
                </div>

                <?php if( $subscribe_form_id && function_exists('gravity_form') ): ?>
                    <div class="siteFooter__form">
                        <h4 class="siteFooter__title">Sign Up for Updates</h4>
                        <?php gravity_form( $subscribe_form_id, false, false, false, null, true ); ?>
                    </div>
                <?php endif; ?>

                <div class="siteFooter__socials">
                    <?php foreach( $socials as $icon => $field ):
                        $value = get_field( $field, 'options');
                        if( !$value ){ continue; }
                        ?>
                        <a href="<?php echo $value; ?>" target="_blank">
                            <i class="ri-<?php echo $icon; ?>"></i>
                        </a>
                    <?php endforeach; ?>
                </div>

                <div class="siteFooter__badge">
                    <img src="<?php echo FL_CHILD_THEME_URL; ?>/assets/images/JCNQA-logo.png" alt="The Joint Commission National Quality Approval" />
                </div>


            </div>

            <div class="siteFooter__nav">

                <?php ThemeMenus::footerMenu(); ?>

            </div>

        </div>

    </div>
</div>

<div class="bottomline">

    <div class="gh-container">
        <div class="bottomline__top">
            <div class="bottomline__copyright">
                &copy;<?php echo date('Y'); ?> Gifted Healthcare. All Rights Reserved
            </div>
            <span class="sep desktop"></span>
            <a href="/privacy-policy">Privacy Policy</a>
            <span class="sep"></span>
            <a href="/terms-conditions/">Terms &amp; Conditions</a>
        </div>

        <div class="bottomline__bottom">
            <?php the_field('footer_bottom_text', 'options'); ?>
        </div>

    </div>


</div>