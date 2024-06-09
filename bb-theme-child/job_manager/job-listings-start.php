<?php
/**
 * Content shown before job listings in `[jobs]` shortcode.
 *
 * This template can be overridden by copying it to yourtheme/job_manager/job-listings-start.php.
 *
 * @see         https://wpjobmanager.com/document/template-overrides/
 * @author      Automattic
 * @package     wp-job-manager
 * @category    Template
 * @version     1.15.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

global $wp_query;
$found_posts = $wp_query->found_posts;

?>

<div class="jobListings__top">
    <div class="jobListings__count">
        <?php if( $found_posts > 0 ): ?>
            Showing
            <strong>
                <?php echo number_format( $found_posts, 0, '.',','); ?>
                <?php echo _n('job', 'jobs', $found_posts ); ?>
            </strong>
        <?php endif; ?>
    </div>

    <div class="jobListings__account">
        Already have a profile? <a href="https://ctms.contingenttalentmanagement.com/giftednurses/WorkforcePortal/login.cfm" target="_blank">Log in</a>
    </div>
</div>

<div class="jobArticle__list">
