<?php
use \Theme\HeaderAndFooter;
use \Theme\ThemeMenus;
$cta_link = HeaderAndFooter::getCtaLink();
$top_links = HeaderAndFooter::getTopLinks();


?>

<header class="siteHeader">
    <div class="siteHeader__top">
        <?php if( !empty($top_links) ): ?>
            <ul class="siteHeader__topNav">
                <?php foreach( $top_links as $one_link ):
                    $link_arr = $one_link['link'];
                    if( !isset($link_arr['url'])){ continue; }
                    $target_attr = $link_arr['target']
                        ? 'target="'.$link_arr['target'].'"'
                        : '';
                    ?>
                    <li>
                        <a href="<?php echo $link_arr['url']; ?>" <?php echo $target_attr; ?>>
                            <?php echo $link_arr['title']; ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>
    <div class="siteHeader__bottom">
        <div class="siteHeader__left">
            <button class="siteHeader__hamburger">
                <span></span>
                <span></span>
                <span></span>
            </button>
            <a class="siteHeader__logo" href="/" rel="home">
                <?php get_template_part('parts/logo'); ?>
            </a>
        </div>

        <div class="siteHeader__middle">
            <?php ThemeMenus::mainMenu(); ?>
        </div>

        <?php if( $cta_link ):
            $target_attr = $cta_link['target']
                ? 'target="'.$cta_link['target'].'"'
                : '';
            ?>
        <div class="siteHeader__right">
            <a class="ghButton" href="<?php echo $cta_link['url']; ?>" <?php echo $target_attr; ?>>
                <?php echo $cta_link['title']; ?>
            </a>
        </div>
        <?php endif; ?>
    </div>
</header>
<!--<div class="siteHeader__height"></div>-->

<div class="mobMenu">
    <div class="mobMenu__layout">
        <div class="mobMenu__top">
            <?php ThemeMenus::mainMenu('mobNav'); ?>
        </div>

        <?php if( $cta_link ):
            $target_attr = $cta_link['target']
                ? 'target="'.$cta_link['target'].'"'
                : '';
            ?>
            <div class="mobMenu__bot">
                <a class="ghButton ghButton--large ghButton--block" href="<?php echo $cta_link['url']; ?>" <?php echo $target_attr; ?>>
                    <?php echo $cta_link['title']; ?>
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>