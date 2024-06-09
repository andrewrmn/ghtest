<div class="gh-container">

    <div class="mediaText mediaText--position-<?php echo $settings->image_position; ?>">

        <div class="mediaText__image">
            <?php if( $settings->image ): ?>
                <?php echo wp_get_attachment_image( $settings->image, 'large'); ?>
            <?php endif; ?>
        </div>

        <div class="mediaText__content">
            <div class="flow flow--32">
                <div class="flow flow--24">
                    <h2 class="t-large">
                        <?php echo $settings->title; ?>
                    </h2>
                    <?php if( $subtitle = $settings->subtitle ): ?>
                        <p class="t-h2">
                            <?php echo $subtitle; ?>
                        </p>
                    <?php endif; ?>
                </div>
                <div class="mediaText__text flow--24 resetType">
                    <?php echo $settings->text; ?>
                </div>

                <?php if( $settings->cta_url && $settings->cta_text ): ?>
                    <div class="mediaText__cta">
                        <a class="ghButton ghButton--mid" href="<?php echo $settings->cta_url; ?>">
                            <?php echo $settings->cta_text; ?>
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>

    </div>
</div>
