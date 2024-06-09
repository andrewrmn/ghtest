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

global $post;
$therapia_job = get_post_meta( get_the_ID(), 'therapia_job', true );
$job_type = get_post_meta( get_the_ID(), 'order_type', true );
$specialty = get_post_meta( get_the_ID(), 'cert', true );
$shifts = get_post_meta( get_the_ID(), 'shift_time', true );
$hour = get_post_meta( get_the_ID(), 'hours', true );
$shift_num = get_post_meta( get_the_ID(), 'shift', true );
$time_start = get_post_meta( get_the_ID(), 'start_date', true );
$time_end = get_post_meta( get_the_ID(), 'end_date', true );
$hot_job = get_post_meta( get_the_ID(), 'hot_job', true );
$week_day = get_post_meta( get_the_ID(), 'week_day', true );
$pay = get_post_meta( get_the_ID(), 'pay_package', true );
$custom_job_location = get_post_meta( get_the_ID(), 'custom_job_location', true );

$specialty = wp_get_post_terms( get_the_ID(), 'job_listing_specialty');
if(is_array($specialty) && !empty($specialty)){
    $specialty = $specialty[0]->name;
}
else{
    $specialty = '';
}


//$week_day="1 week and 2 days";
//$hot_job=1;
if($hot_job>0)
{
	if($hot_job==1)
	{
		$likely="Likely to be filled in ".$hot_job." week";
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
if($job_type=="Contract")
{
	$job_type="Local Contract";
}

if($job_type=="PRN - Marketing")
{
	$job_type="Per Diem/PRN";
	$week_day="Variable";
	$start_date="ASAP";
	$shifts="ALL";
}
else{
	$start_date=date('Y',strtotime($post->start_date));
	$start_date=$start_date.'-'.date('Y', strtotime('+1 year', strtotime($start_date)) );
}
$start_date=date('Y',strtotime($post->start_date));
$start_date=$start_date.'-'.date('Y', strtotime('+1 year', strtotime($start_date)) );
?>
<div class="jobsearch-main-section">
                    <div class="jobsearch-plugin-default-container">
                        <div class="jobsearch-row">

                            <div class="cat">
                                <div class="row1">

                                    <div class="col-sm1">
                                        <div class="cat">

                                        </div>
                                        <div class="col-sm16">
                                            <div class="row-4">
                                                <div class="row5">
                                                    <div class="col-sm-55">
                                                        <p class="above-text"><?php echo $likely;?></p>
                                                    </div>
                                                    <div class="col-sm-56">
<!--                                                         <p class="left-text">$3,754/wk</p> -->
                                                    </div>

                                                </div>
                                                <div class="row1 title_row_job_search">
                                                    <div class="col-sm11">
<!--                                                         <span class="picture">
                                                            <a href=""><?php the_company_logo(); ?></a>
                                                        </span> -->
                                                    </div>
                                                    <div class="col-sm12 title_col_job_search">
                                                      <?php echo $image;?>
														 <a class="job_search_job_title" href="<?php the_job_permalink(); ?>"><h2 class="text9">
                                                             <?php //wpjm_the_job_title(); ?>
															 <?php echo str_replace("PRN - Marketing","Per Diem/PRN",$post->post_title);?> 
                                                        </h2>
													   </a>
                                                    </div>
                                                </div>
                                                <div class="col-sm-22 align-items-center">
                                                    <div class="text-start ps-4">


                                                        <!-- Job references - Location/Category/Date Posted -->
                                                        <div class="row2">

                                                            <div class="col-sumfirst">
                                                                <p class="post"><img class="image"
                                                                        src="/wp-content/uploads/2022/04/heart.png"
                                                                        alt=""><span class="space"><?php echo $specialty;?></span></p>
                                                            </div>
                                                            <div class="col-sumfirst">

                                                                <p class="post"><img class="image"
                                                                        src="/wp-content/uploads/2022/04/date.png"
                                                                        alt=""><span class="space">
																	<?php echo $start_date; ?>
<!-- 																	<?php echo $time_start;?>  -->
<!-- 																	to <?php echo $time_end;?> -->
																	</span></p>
                                                            </div>
                                                            <div class="col-sumsecond">
                                                                <p class="post"><img class="image"
                                                                        src="/wp-content/uploads/2022/04/location.png"
                                                                        alt=""><span class="space"><?php echo $custom_job_location; ?> </span>
                                                                </p>
                                                            </div>
															<div class="col-sumfirst">
                                                                <p class="post"><svg xmlns="http://www.w3.org/2000/svg" height="13px" style="position:relative;top:3px;" viewBox="0 0 512 512"><!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M320 96H192L144.6 24.9C137.5 14.2 145.1 0 157.9 0H354.1c12.8 0 20.4 14.2 13.3 24.9L320 96zM192 128H320c3.8 2.5 8.1 5.3 13 8.4C389.7 172.7 512 250.9 512 416c0 53-43 96-96 96H96c-53 0-96-43-96-96C0 250.9 122.3 172.7 179 136.4l0 0 0 0c4.8-3.1 9.2-5.9 13-8.4zm84 88c0-11-9-20-20-20s-20 9-20 20v14c-7.6 1.7-15.2 4.4-22.2 8.5c-13.9 8.3-25.9 22.8-25.8 43.9c.1 20.3 12 33.1 24.7 40.7c11 6.6 24.7 10.8 35.6 14l1.7 .5c12.6 3.8 21.8 6.8 28 10.7c5.1 3.2 5.8 5.4 5.9 8.2c.1 5-1.8 8-5.9 10.5c-5 3.1-12.9 5-21.4 4.7c-11.1-.4-21.5-3.9-35.1-8.5c-2.3-.8-4.7-1.6-7.2-2.4c-10.5-3.5-21.8 2.2-25.3 12.6s2.2 21.8 12.6 25.3c1.9 .6 4 1.3 6.1 2.1l0 0 0 0c8.3 2.9 17.9 6.2 28.2 8.4V424c0 11 9 20 20 20s20-9 20-20V410.2c8-1.7 16-4.5 23.2-9c14.3-8.9 25.1-24.1 24.8-45c-.3-20.3-11.7-33.4-24.6-41.6c-11.5-7.2-25.9-11.6-37.1-15l0 0-.7-.2c-12.8-3.9-21.9-6.7-28.3-10.5c-5.2-3.1-5.3-4.9-5.3-6.7c0-3.7 1.4-6.5 6.2-9.3c5.4-3.2 13.6-5.1 21.5-5c9.6 .1 20.2 2.2 31.2 5.2c10.7 2.8 21.6-3.5 24.5-14.2s-3.5-21.6-14.2-24.5c-6.5-1.7-13.7-3.4-21.1-4.7V216z"/></svg><span class="space"><?php echo $pay;?></span></p>
                                                            </div>

                                                        </div>
                                                         <div class="row3">

                                                            
															 
                                                            <!--<div class="col-sumfirst">

                                                                <p class="post"><img class="image"
                                                                        src="/wp-content/uploads/2022/04/sun.png"
                                                                        alt=""><span class="space"><?php echo $shifts;?></span></p>
                                                            </div>
                                                            <div class="col-sumsecond">
                                                                <p class="post"><img class="image"
                                                                        src="/wp-content/uploads/2022/04/clock.png"
                                                                        alt="">
																	 <span class="space"><?php echo $week_day;?></span></p>
                                                            </div>-->
                                                        </div> 
                                                    </div>
                                                </div>
                                                <div class="col-sm23 align-items-center">
                                                    <div class="d-flex mb-3">

<!--                                                         <a class="btn buttonprimary" href="<?php echo esc_url( get_permalink($data->ID) ); ?>?job-search=yes" style="width: 100%;">Apply
                                                            Now</a> -->
<a class="btn buttonprimary" href="<?php echo esc_url( get_permalink($data->ID) ); ?>?job-search=yes" style="width: 100%;">Apply
                                                            Now</a>

                                                    </div>
                                                    <a class="btn buttonprmary-2" href="<?php the_job_permalink(); ?>">View Job Details </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div>
                            </div>
                        </div>
                    </div>
                </div>
