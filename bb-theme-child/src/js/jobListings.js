jQuery( function ($){

    const $html = $('html');
    const $search_btn = $('#job-listings-search-btn');
    const $search = $('#job-listings-search');
    const $filter_btn = $('#job-listings-filter-btn');
    const $filters = $('#job-listings-filters');

    const $search_submit = $('#job-listings-search-submit');
    const $search_cancel = $('#job-listings-search-cancel');

    const $filter_submit = $('#job-listings-filter-submit');
    const $filter_cancel = $('#job-listings-filter-cancel');
    const $filter_close  = $('#job-listings-filter-close');

    $search_btn.on('click', () => openModal( $search ));
    $filter_btn.on('click', () => openModal( $filters ));

    // Search submit and clear
    $search_submit.on('click', () => closeModal( $search ));
    $search_cancel.on('click', function(){
        FWP.reset('new_distance');
        closeModal( $search );
    });

    // Filter submit, close, and clear
    $filter_submit.on('click', () => closeModal( $filters ));
    $filter_close.on('click', () => closeModal( $filters ));
    $filter_cancel.on('click', function(){
        FWP.reset(['job_type', 'profession', 'speciality', 'location']);
        closeModal( $filters );
    });



    function closeModal( $modal ) {
        $modal.removeClass('active');
        $html.removeClass('filters-open');
    }

    function openModal( $modal ) {
        $modal.addClass('active');
        $html.addClass('filters-open');
    }

});