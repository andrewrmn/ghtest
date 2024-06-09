<?php
$s = $settings;
?>
<div class="gh-container">
    <div class="featuredJobs | resetType">

        <div class="featuredJobs__top">

            <div class="featuredJobs__content">
                <div class="flow flow--32">
                    <h2 class="t-large"><?php echo $s->title; ?></h2>
                    <div class="flow flow--24">
                        <?php echo $s->text; ?>
                    </div>
                </div>
            </div>
            <?php if( $s->cta_link ):
                $target_attr = $s->cta_target ? 'target="'.$s->cta_target.'"' : '';
                ?>
                <div class="featuredJobs__cta">
                    <a class="ghButton ghButton--mid ghButton--teal" href="<?php echo $s->cta_link; ?>">
                        <?php echo $s->cta_text; ?>
                    </a>
                </div>
            <?php endif; ?>
        </div>

        <div class="featuredJobs__bottom">
            <?php for( $i = 0; $i < 3; $i ++):
                $url = $s->{'link_'. $i};
                $image = $s->{'image_' . $i};
                $text = $s->{'text_' . $i};
                ?>
                <a class="featuredJobs__card flow flow" href="<?php echo $url; ?>">
                    <?php if( $image ): ?>
                        <div class="featuredJobs__cardImg">
                            <?php echo wp_get_attachment_image( $image, 'medium_large'); ?>
                        </div>
                    <?php endif; ?>
                    <?php echo $text; ?>
                </a>
            <?php endfor; ?>
        </div>


    </div>
</div>