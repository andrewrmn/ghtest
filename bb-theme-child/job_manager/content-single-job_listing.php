<?php
/**
 * Single job listing.
 *
 * This template can be overridden by copying it to yourtheme/job_manager/content-single-job_listing.php.
 *
 * @see         https://wpjobmanager.com/document/template-overrides/
 * @author      Automattic
 * @package     wp-job-manager
 * @category    Template
 * @since       1.0.0
 * @version     1.28.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

# Setup display filter helper
GHC_CTM_JOB_DISPLAY::load( get_the_ID() );

# Enqueue Scripts
wp_enqueue_script( 'wp-job-manager-job-application' );

$description = wpjm_get_the_job_description();
$description = str_replace('RESPONSIBILITIES:', 'Responsibilities', $description);

?>

    <div class="singleJob">

        <a href="/job-search/" class="singleJob__back">
            <i class="ri-arrow-left-line"></i>
            Back to Search
        </a>

        <div class="singleJob__layout">

            <div>

                <h1 class="singleJob__title">
                    <?php the_title(); ?>
                </h1>

                <ul class="singleJob__details gridDetails">
                    <li>
                        <i class="ri-nurse-fill"></i>
                        <?php GHC_CTM_JOB_DISPLAY::echo('profession') ?>
                    </li>
                    <li>
                        <i class="ri-calendar-2-fill"></i>
                        <?php GHC_CTM_JOB_DISPLAY::filtered_date_range(); //echo $date_range; ?>
                    </li>
                    <li>
                        <i class="ri-money-dollar-circle-fill"></i>
                        <?php GHC_CTM_JOB_DISPLAY::filtered_pay(); ?>
                    </li>
                    <li>
                        <i class="ri-car-fill"></i>
                        <?php GHC_CTM_JOB_DISPLAY::echo('job_type'); ?>
                    </li>
                    <li>
                        <i class="ri-sun-fill"></i>
                        <?php GHC_CTM_JOB_DISPLAY::filtered_shift(); ?>
                    </li>
                    <li>
                        <i class="ri-time-fill"></i>
                        <?php GHC_CTM_JOB_DISPLAY::filtered_contract_length(); ?>
                    </li>
                </ul>

                <div class="singleJob__mobileLink">
                    <a class="ghButton ghButton--large ghButton--hollow | js-jump-link" href="#apply-now">
                        Apply Today
                        <i class="ri-arrow-down-line"></i>
                    </a>
                </div>

                <div class="singleJob__description">
                    <?php echo $description; ?>

                    <h2 class="text10">Benefits of Gifted Healthcare</h2>

                    <div class="singleJob__benefits">
                        <ul>
                            <li>Competitive pay packages</li>
                            <li>Career Flexibility</li>
                            <li>Day one Medical, Dental and Vision <br>Plan from a National Provider</li>
                            <li>Housing and Meal Stipend</li>
                            <li>Contracts with Premier Facilities </li>
                        </ul>
                        <ul>
                            <li>Referral bonuses – earn $1,000 for each <br>friend you refer to Gifted Healthcare</li>
                            <li>24/7 support with Career Specialist</li>
                            <li>Access to Social Worker 24/7</li>
                            <li>Access to Chief Nursing Officer 24/7</li>
                            <li>Short- and Long-Term Disability</li>
                            <li>AD&D Insurance</li>
                        </ul>
                    </div>

                    <h3>Refer a friend</h3>
                    <p>
                        Earn $1,000 for each friend you refer to Gifted
                    </p>
                    <p>
                        <a class="ghButton ghButton--large ghButton--hollow" href="https://giftedjobs.com/refer" target="_blank">
                            Refer a Friend
                        </a>
                    </p>


                    <script type='text/javascript' src='https://click.appcast.io/pixels/homegrown1-23249.js?ent=417'></script>

                </div>

            </div>

            <div>

                <div class="jobApplication" id="apply-now">

                    <h2 class="jobApplication__title">Apply Today</h2>

                    <?php do_action( 'job_application_start', null ); ?>
                    <div class="application_details 123">
                        <script defer src="/react/quickapply.js?ts=<?php echo time(); ?>"></script>
                        <script defer src="/react/quickapply-init.js?ts=<?php echo time(); ?>"></script>
                        <link rel="stylesheet" type="text/css" href="/react/quickapply.css?ts=<?php echo time(); ?>" />
                        <input type="hidden" id="JobID" value="<?php GHC_CTM_JOB_DISPLAY::echo('job_id'); ?>">
                        <div id="root"></div>
                    </div>
                    <?php do_action( 'job_application_end', null ); ?>

                </div>


            </div>


        </div>

    </div>