    <div class="ghFilters">

        <div class="ghFilters__header">
            <button class="ghFilters__close" type="button" id="job-listings-filter-close">
                <i class="ri-close-line"></i>
            </button>
            Filters
        </div>

        <div class="ghFilters__body">

            <div class="flow flow--24">

                <h2 class="t-h2">Filter by</h2>

                <div class="ghFilters__group">
                    <h3 class="t-h3">Job Type</h3>
                    <?php gh_facet('[facetwp facet="job_type"]'); ?>
                </div>

                <div class="ghFilters__group">
                    <h3 class="t-h3">Profession</h3>
                    <?php gh_facet('[facetwp facet="profession"]'); ?>
                </div>

                <div class="ghFilters__group">
                    <h3 class="t-h3">Specialty</h3>
                    <?php gh_facet('[facetwp facet="speciality"]'); ?>
                </div>

                <div class="ghFilters__group">
                    <h3 class="t-h3">Location</h3>
                    <?php gh_facet('[facetwp facet="location"]'); ?>
                </div>

            </div>
        </div>

        <div class="ghFilters__footer">
            <button id="job-listings-filter-cancel" type="button" class="ghButton ghButton--large ghButton--hollow ghButton--block">
                Clear All
            </button>
            <button id="job-listings-filter-submit" type="button" class="ghButton ghButton--large ghButton--teal ghButton--block">
                View results
            </button>
        </div>


    </div>
