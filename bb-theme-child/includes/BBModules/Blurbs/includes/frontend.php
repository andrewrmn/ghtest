<?php
$blurbs = $settings->blurbs;
$blurbs = is_array( $blurbs ) ? $blurbs : [];
?>
<div class="gh-container">
    <div class="ghBlurbs | resetType">

        <?php foreach( $blurbs as $i => $one_blurb ): ?>
            <div class="ghBlurbs__item flow flow--32">
                <?php if( $one_blurb->icon ): ?>
                    <div class="ghBlurbs__image">
                        <?php echo wp_get_attachment_image( $one_blurb->icon, 'medium'); ?>
                    </div>
                <?php else: ?>
                    <div class="ghBlurbs__number">
                        <?php echo $i + 1; ?>
                    </div>
                <?php endif; ?>
                <div class="flow flow--24">
                    <h3 class="t-h2"><?php echo $one_blurb->title; ?></h3>
                    <div class="ghBlurbs__text">
                        <?php echo $one_blurb->text; ?>
                    </div>
                    <?php if( $one_blurb->link && $one_blurb->link_text ): ?>
                        <div class="ghBlurbs__cta">
                            <a href="<?php echo $one_blurb->link; ?>">
                                <?php echo $one_blurb->link_text; ?>
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>

    </div>
</div>
