<div class="ghHeader ghHeader--overlapped">
    <div class="gh-container">

        <div class="ghHeader__layout">

            <h1 class="ghHeader__title">
                <?php echo $settings->title; ?>
            </h1>

            <?php if( $settings->text ): ?>
                <p class="ghHeader__text">
                    <?php echo $settings->text; ?>
                </p>
            <?php endif; ?>

            <div class="ghHeader__search">
                <form action="/job-search/">
                    <input type="text" name="_keyword_search" placeholder="Search for healthcare jobs" aria-label="Search for healthcare jobs" />
                    <button type="submit">
                        <i class="ri-search-line"></i>
                    </button>
                </form>
            </div>

        </div>

    </div>
</div>