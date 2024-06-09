<?php

$search_term = $_GET['_keyword_search'] ?? '';

?>

<div class="ghHeader">
    <div class="gh-container">

        <div class="ghHeader__layout">

            <h1 class="ghHeader__title">
                <?php echo $settings->title; ?>
            </h1>

            <div class="ghHeader__search">
                <?php gh_facet( '[facetwp facet="keyword_search"]'); ?>
            </div>

        </div>

    </div>
</div>

<div class="gh-container">

    <div class="jobListings__layout">

        <div class="jobListings__filtersActions">
            <button id="job-listings-search-btn" class="ghButton ghButton--hollow ghButton--large ghButton--block" type="button">
                <i class="ri-map-pin-2-line"></i>
                Radius Search
            </button>
            <button id="job-listings-filter-btn" class="ghButton ghButton--hollow ghButton--large ghButton--block" type="button">
                <i class="ri-filter-3-line"></i>
                Filter
            </button>
        </div>

        <div class="jobListings__filtersCol">
            <div class="flow">
                <div class="jobListings__radiusSearch | radiusSearch" id="job-listings-search">
                    <div class="flow">
                        <h2 class="t-h2">
                            Radius Search
                        </h2>

                        <div class="radiusSearch__form">
                            <?php gh_facet('[facetwp facet="new_distance"]'); ?>
                        </div>

                        <div class="radiusSearch__actions">
                            <button id="job-listings-search-submit" type="button" class="ghButton ghButton--large ghButton--block ghButton--teal">
                                View Results
                            </button>
                            <button id="job-listings-search-cancel" type="button" class="ghButton ghButton--large ghButton--block ghButton--hollow">
                                Clear All
                            </button>
                        </div>
                    </div>
                </div>
                <div class="jobListings__filters" id="job-listings-filters">
                    <?php get_template_part('parts/job-listings/filters'); ?>
                </div>
            </div>
        </div>

        <div class="jobListings__results">
            <?php gh_facet( '[facetwp template="jobs"]'); ?>

            <div class="jobListings__pagination">
                <?php gh_facet('[facetwp facet="paiger"]'); ?>
            </div>
        </div>

    </div>

</div>