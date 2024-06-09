<?php
$testimonials = $settings->testimonials;
$testimonials = is_array( $testimonials ) ? $testimonials : [];
?>
<div class="gh-container">

    <div class="ghTestimonials | resetType">

        <div class="owl-carousel owl-theme | ghTestimonials__carousel">

            <?php foreach( $testimonials as $t ): ?>
            <div class="ghTestimonials__item">
                <div class="flow" style="--flow-gap: 40px;">

                    <?php if( $t->image ): ?>
                        <div class="ghTestimonials__image">
                            <?php echo wp_get_attachment_image( $t->image, 'medium_large' ); ?>
                        </div>
                    <?php endif; ?>

                    <div class="ghTestimonials__text">
                        <?php echo $t->text; ?>
                    </div>

                    <div class="ghTestimonials__author">
                        <?php if( $t->author_pic ): ?>
                            <div class="ghTestimonials__authorPic">
                                <?php echo wp_get_attachment_image( $t->author_pic ); ?>
                            </div>
                        <?php endif; ?>
                        <?php echo $t->author_name; ?>
                    </div>

                </div>
            </div>
            <?php endforeach; ?>
        </div>

    </div>
</div>
