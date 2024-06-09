<?php
/**
 * Job listing in the loop.
 *
 * This template can be overridden by copying it to yourtheme/job_manager/content-job_listing.php.
 *
 * @see         https://wpjobmanager.com/document/template-overrides/
 * @author      Automattic
 * @package     wp-job-manager
 * @category    Template
 * @since       1.0.0
 * @version     1.34.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

# Setup display filter helper
GHC_CTM_JOB_DISPLAY::load( get_the_ID() );

global $post;

# Confirmed / Tweaked.
$start_date = date( 'n/j/Y', strtotime(get_post_meta( get_the_ID(), 'start_date', true )) );
$contract_length = get_post_meta( get_the_ID(), 'week_day', true );
$hours = get_post_meta( get_the_ID(), 'hours', true );
$client_type = get_post_meta( get_the_ID(), 'client_type', true );

$profession = wp_get_post_terms( get_the_ID(), 'job_listing_profession');
if(is_array($profession) && !empty($profession)){
    $profession = $profession[0]->name;
}
else{
    $profession = '';
}

if(!empty($hours) && !empty($contract_length)){
    $contract_length .= " at $hours hours/week";
}

$job_type = wp_get_post_terms( get_the_ID(), 'job_listing_type');
$pay = get_post_meta( get_the_ID(), 'pay_package', true);
$shift = get_post_meta( get_the_ID(), 'shift', true );

if(is_array($job_type) && !empty($job_type)){
    $job_type = $job_type[0]->name;
}
else{
    $job_type = '';
}

if(strtolower($job_type) == 'prn' || strtolower($job_type) == 'government'){
    $pay = '';
    $contract_length = '';
}

if(strtolower($job_type) == 'school'){
    $shift = '';

    if(empty($contract_length)){
        $start_date = '';
    }
}

if(strtolower($job_type) == 'local' && strtolower($client_type) == 'travel-localapply' ){
    $pay = '';
}

$toCheck = array('job_type','start_date','contract_length','pay','profession','shift');
foreach($toCheck as $variable){
    if(empty(${$variable})){
        ${$variable} = 'Apply now for details';
    }
}

# Also check for "Pay: $0 to $0".
if($pay == "$0 - $0/week") $pay = 'Apply now for details';


# SF Legacy Stuff.  Is it still needed?
$hot_job = get_post_meta( get_the_ID(), 'hot_job', true );

if($hot_job>0)
{
	if($hot_job==1)
	{
		$likely="Likely to be filled in 1 week";
	}
	else{
		$likely="Likely to be filled in ".$hot_job." weeks";
	}
	$image='<span class="picture"><a href=""><img class="company_logo" src="/wp-content/uploads/2021/12/cropped-Gifted-Healthcare-Logo-1-e1640112144819-150x150.png" alt="Gifted Healthcare"></a></span>';

}
else{
	$likely="";
	$image="";
}

?>

    <a href="<?php the_job_permalink(); ?>" class="jobArticle">
        <div class="flow flow--24">

            <?php if( $likely ): ?>
                <div class="jobArticle__article">
                    <?php echo $likely; ?>
                </div>
            <?php endif; ?>

            <h2 class="jobArticle__titlle t-h2">
                <?php the_title();?>
            </h2>

            <ul class="gridDetails">
                <li>
                    <i class="ri-nurse-fill"></i>
                    <?php GHC_CTM_JOB_DISPLAY::echo('profession'); ?>
                </li>
                <li>
                    <i class="ri-calendar-2-fill"></i>
                    <?php GHC_CTM_JOB_DISPLAY::filtered_start_date(); ?>
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

        </div>
    </a>
