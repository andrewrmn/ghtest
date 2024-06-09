<?php

// Defines
define( 'FL_CHILD_THEME_DIR', get_stylesheet_directory() );
define( 'FL_CHILD_THEME_URL', get_stylesheet_directory_uri() );

// Classes
require_once 'classes/class-fl-child-theme.php';

// Custom modules
require_once 'includes/autoload.php';
require_once 'setup.php';

add_filter('acf/settings/remove_wp_meta_box', '__return_false');

// Actions
add_action( 'wp_enqueue_scripts', 'FLChildTheme::enqueue_scripts', 1000 );

add_filter( 'facetwp_result_count', function( $output, $params ) {
	$output ='<p class="jobs_count">Showing <span class="date_count_amount">'.$params['total'].'</span> <span class="jobs_text">jobs</span></p>';
    //$output = 'Showing ' . $params['total'] . ' jobs';
    return $output;
}, 10, 2 );

add_filter( 'facetwp_use_search_relevancy', '__return_false' );

//job count shortcode start
function get_job_function()
	{
		$query = array(
		'post_type' => 'job_listing',
		'post_status' => 'publish',
		'fields' => 'ids',
	);
	$results = new WP_Query($query);
	$html = $results->found_posts;
	return $html;
	wp_reset_postdata();
}
add_shortcode( 'getjobs', 'get_job_function' );

function get_job_therapia_function()
	{

	$query = new WP_Query( array(
    'post_type' => 'job_listing',
	'post_status' => 'publish',
    'meta_query' => array(
        array(
           'key' => 'therapia_job',
           'value' => 'Yes',
           'compare' => '='
        )
     )
));


	$html = $query->found_posts;
	return $html;
	wp_reset_postdata();
}
add_shortcode( 'getjobstherapia', 'get_job_therapia_function' );

add_filter( 'facetwp_facet_render_args', function( $args ) {
    if ( 'job_type' == $args['facet']['name'] ) {
        $translations = [
            'Contract' => __( 'Local Contract', 'fwp' ),
			//'Critical Need' => __( 'Critical Response', 'fwp' ),
			//'Contract Critical Need' => __( 'Critical Response', 'fwp' )
        ];

        if ( ! empty( $args['values'] ) ) {
            foreach ( $args['values'] as $key => $val ) {
                $display_value = $val['facet_display_value'];
                if ( isset( $translations[ $display_value ] ) ) {
                    $args['values'][ $key ]['facet_display_value'] = $translations[ $display_value ];
                }
            }
        }
    }
    return $args;
});

add_filter( 'facetwp_facet_render_args', function( $args ) {
    if ( 'job_type' == $args['facet']['name'] ) {
        $translations = [
            'PRN - Marketing' => __( 'Per Diem/PRN', 'fwp' )
        ];

        if ( ! empty( $args['values'] ) ) {
            foreach ( $args['values'] as $key => $val ) {
                $display_value = $val['facet_display_value'];
                if ( isset( $translations[ $display_value ] ) ) {
                    $args['values'][ $key ]['facet_display_value'] = $translations[ $display_value ];
                }
            }
        }
    }
	if ( 'job_type_dropdown' == $args['facet']['name'] ) {
        $translations = [
            'PRN - Marketing' => __( 'Per Diem/PRN', 'fwp' )
        ];

        if ( ! empty( $args['values'] ) ) {
            foreach ( $args['values'] as $key => $val ) {
                $display_value = $val['facet_display_value'];
                if ( isset( $translations[ $display_value ] ) ) {
                    $args['values'][ $key ]['facet_display_value'] = $translations[ $display_value ];
                }
            }
        }
    }
    return $args;
});


add_filter( 'gform_pre_render_7', 'populate_choices1' );

//Note: when changing choice values, we also need to use the gform_pre_validation so that the new values are available when validating the field.
add_filter( 'gform_pre_validation_7', 'populate_choices1' );

//Note: when changing choice values, we also need to use the gform_admin_pre_render so that the right values are displayed when editing the entry.
add_filter( 'gform_admin_pre_render_7', 'populate_choices1' );

//Note: this will allow for the labels to be used during the submission process in case values are enabled
add_filter( 'gform_pre_submission_filter_7', 'populate_choices1' );
function populate_choices1( $form) {



	global $wpdb;
	$items = array();
//Adding post titles to the items array
   $metakey = 'cert';
 $counties = $wpdb->get_col($wpdb->prepare("SELECT DISTINCT meta_value FROM $wpdb->postmeta WHERE meta_key = %s ORDER BY meta_value ASC", $metakey) );
 if ($counties) {
 foreach ($counties as $county) {

	 //$items[] = array( 'value' => str_replace(' ', '-', strtolower($county)), 'text' => $county );
	 $items[] = array( 'value' => sanitize_title_with_dashes($county), 'text' => $county );
 }
 }
	$items1 = array();
//Adding post titles to the items array
   $metakey1 = 'specialty';
 $counties1 = $wpdb->get_col($wpdb->prepare("SELECT DISTINCT meta_value FROM $wpdb->postmeta WHERE meta_key = %s ORDER BY meta_value ASC", $metakey1) );
 if ($counties1) {
 foreach ($counties1 as $county1) {
	 $items1[] = array( 'value' => str_replace(' ', '-', strtolower($county1)), 'text' => $county1 );
 }
 }
	$items2 = array();
//Adding post titles to the items array
   $metakey2 = 'state';
 $counties2 = $wpdb->get_col($wpdb->prepare("SELECT DISTINCT meta_value FROM $wpdb->postmeta WHERE meta_key = %s ORDER BY meta_value ASC", $metakey2) );
 if ($counties2) {
 foreach ($counties2 as $county2) {
	 $items2[] = array( 'value' => strtolower($county2), 'text' => $county2 );
 }
 }

    //only populating drop down for form id 5
    if ( $form['id'] != 7 ) {
       return $form;
    }



    //Add a placeholder to field id 8, is not used with multi-select or radio, will overwrite placeholder set in form editor.
    //Replace 8 with your actual field id.
    /*$fields = $form['fields'];
    foreach( $form['fields'] as &$field ) {
      if ( $field->id == 2 ) {
        $field->placeholder = 'Location';
      }
	  if ( $field->id == 3 ) {
        $field->placeholder = 'Category';
      }
    }*/






    //Adding items to field id 8. Replace 8 with your actual field id. You can get the field id by looking at the input name in the markup.
    foreach ( $form['fields'] as &$field ) {
        /*if ( $field->id == 1 ) {
            $field->choices = $items;
        }*/
		if ( $field->id == 3 ) {
            $field->choices = $items1;
        }
		if ( $field->id == 2 ) {
            $field->choices = $items2;
        }
    }

    return $form;
}


add_action( 'gform_pre_submission_2', 'pre_submission_handler' );
function pre_submission_handler( $form ) {

	$profession=rgpost( 'input_35' );
	$looking=rgpost( 'input_30.4' );
	$allied_certification=rgpost( 'input_40' );
	if($profession=="RN")
	{
		$specialty=rgpost( 'input_36' );
		$_POST['input_52'] = rgpost( 'input_36' );
		$_POST['input_53'] = "1";
		if($specialty=="Infusion")
		{
			$_POST['input_54'] = "13";
		}
		else if($specialty=="LTAC")
		{
			$do_you_have_vent=rgpost( 'input_34' );
			if($do_you_have_vent=="Yes")
			{
				$_POST['input_54'] = "29";
			}
			else{
				if($looking=="Government")
				{
					$_POST['input_54'] = "20";
				}
				else{
					$_POST['input_54'] = "8";
				}
				$_POST['input_52'] = "LTC";
			}

		}

	}
	else if($profession=="LPN")
	{
		$_POST['input_52'] = rgpost( 'input_38' );
		$_POST['input_53'] = "2";
	}
	else if($profession=="BCBA")
	{
		$_POST['input_52'] = rgpost( 'input_61' );
		$_POST['input_53'] = "130";
	}
	else if($profession=="Therapist")
	{
		$_POST['input_52'] = rgpost( 'input_62' );
		$_POST['input_53'] = "138";
	}
	else if($profession=="Paraprofessional")
	{
		$_POST['input_52'] = rgpost( 'input_63' );
		$_POST['input_53'] = "133";
	}
	else if($profession=="RBT")
	{
		$_POST['input_52'] = rgpost( 'input_64' );
		$_POST['input_53'] = "135";
	}
	else if($profession=="COTA")
	{
		$_POST['input_52'] = rgpost( 'input_65' );
		$_POST['input_53'] = "131";
	}
	else if($profession=="Counselor")
	{
		$_POST['input_52'] = rgpost( 'input_66' );
		$_POST['input_53'] = "132";
	}
	else if($profession=="Educational Diagnostician")
	{
		$_POST['input_52'] = rgpost( 'input_76' );
		$_POST['input_53'] = "141";
	}
	else if($profession=="Social Worker")
	{
		$_POST['input_52'] = rgpost( 'input_67' );
		$_POST['input_53'] = "33";
	}
	else if($profession=="Manager")
	{
		$_POST['input_52'] = rgpost( 'input_81' );
		$_POST['input_53'] = "148";
	}
	else if($profession=="Director")
	{
		$_POST['input_52'] = rgpost( 'input_82' );
		$_POST['input_53'] = "147";
	}
	else if($profession=="Vice President")
	{
		$_POST['input_52'] = rgpost( 'input_83' );
		$_POST['input_53'] = "143";
	}
	else if($profession=="CFO")
	{
		$_POST['input_52'] = rgpost( 'input_78' );
		$_POST['input_53'] = "145";
	}
	else if($profession=="CNO")
	{
		$_POST['input_52'] = rgpost( 'input_79' );
		$_POST['input_53'] = "144";
	}
	else if($profession=="CEO")
	{
		$_POST['input_52'] = rgpost( 'input_84' );
		$_POST['input_53'] = "150";
	}
	else if($profession=="COO")
	{
		$_POST['input_52'] = rgpost( 'input_85' );
		$_POST['input_53'] = "149";
	}
	else if($profession=="Controller")
	{
		$_POST['input_52'] = rgpost( 'input_80' );
		$_POST['input_53'] = "146";
	}
	else if($profession=="OT")
	{
		$_POST['input_52'] = rgpost( 'input_68' );
		$_POST['input_53'] = "11";
	}
	else if($profession=="Psychologist")
	{
		$_POST['input_52'] = rgpost( 'input_69' );
		$_POST['input_53'] = "134";
	}
	else if($profession=="PT")
	{
		$_POST['input_52'] = rgpost( 'input_70' );
		$_POST['input_53'] = "10";
	}
	else if($profession=="PTA")
	{
		$_POST['input_52'] = rgpost( 'input_75' );
		$_POST['input_53'] = "19";
	}
	else if($profession=="SLP")
	{
		$_POST['input_52'] = rgpost( 'input_71' );
		$_POST['input_53'] = "139";
	}
	else if($profession=="SLPA")
	{
		$_POST['input_52'] = rgpost( 'input_72' );
		$_POST['input_53'] = "140";
	}
	else if($profession=="SLI")
	{
		$_POST['input_52'] = rgpost( 'input_73' );
		$_POST['input_53'] = "136";
	}
	else if($profession=="Teacher")
	{
		$_POST['input_52'] = rgpost( 'input_74' );
		$_POST['input_53'] = "137";
	}
	else if($profession=="CNA")
	{
		$_POST['input_52'] = rgpost( 'input_37' );
		$_POST['input_53'] = "3";
	}
	else if($profession=="Nurse Practitioner")
	{
		$_POST['input_52'] = rgpost( 'input_39' );
		$_POST['input_53'] = "21";
	}
	else if($profession=="Allied Health Professional")
	{
		$_POST['input_52'] = rgpost( 'input_40' );
		if($allied_certification=="CST")
		{
			$_POST['input_59'] = rgpost( 'input_43' );
		}
		else if($allied_certification=="ORT")
		{
			$_POST['input_59'] = rgpost( 'input_44' );
		}
		else if($allied_certification=="Surgical First Assistant")
		{
			$_POST['input_59'] = rgpost( 'input_45' );
		}
		else if($allied_certification=="CT Technologist")
		{
			$_POST['input_59'] = rgpost( 'input_46' );
		}
		else if($allied_certification=="Rad Tech")
		{
			$_POST['input_59'] = rgpost( 'input_47' );
		}
		else if($allied_certification=="Ultrasound Tech")
		{
			$_POST['input_59'] = rgpost( 'input_48' );
		}
		else if($allied_certification=="X-Ray Tech")
		{
			$_POST['input_59'] = rgpost( 'input_49' );
		}
		else if($allied_certification=="Medical Technologist")
		{
			$_POST['input_59'] = rgpost( 'input_50' );
		}
		else if($allied_certification=="Medical Assistant")
		{
			$_POST['input_59'] = rgpost( 'input_51' );
		}
	}


}

add_action( 'gform_pre_submission_16', 'pre_submission_handler_apply' );
function pre_submission_handler_apply( $form ) {

	$profession=rgpost( 'input_35' );
	$looking=rgpost( 'input_30.4' );
	$allied_certification=rgpost( 'input_40' );
	if($profession=="RN")
	{
		$specialty=rgpost( 'input_36' );
		$_POST['input_52'] = rgpost( 'input_36' );
		$_POST['input_53'] = "1";
		if($specialty=="Infusion")
		{
			$_POST['input_54'] = "13";
		}
		else if($specialty=="LTAC")
		{
			$do_you_have_vent=rgpost( 'input_34' );
			if($do_you_have_vent=="Yes")
			{
				$_POST['input_54'] = "29";
			}
			else{
				if($looking=="Government")
				{
					$_POST['input_54'] = "20";
				}
				else{
					$_POST['input_54'] = "8";
				}
				$_POST['input_52'] = "LTC";
			}

		}

	}
	else if($profession=="LPN")
	{
		$_POST['input_52'] = rgpost( 'input_38' );
		$_POST['input_53'] = "2";
	}
	else if($profession=="BCBA")
	{
		$_POST['input_52'] = rgpost( 'input_61' );
		$_POST['input_53'] = "130";
	}
	else if($profession=="Therapist")
	{
		$_POST['input_52'] = rgpost( 'input_62' );
		$_POST['input_53'] = "138";
	}
	else if($profession=="Paraprofessional")
	{
		$_POST['input_52'] = rgpost( 'input_63' );
		$_POST['input_53'] = "133";
	}
	else if($profession=="RBT")
	{
		$_POST['input_52'] = rgpost( 'input_64' );
		$_POST['input_53'] = "135";
	}
	else if($profession=="COTA")
	{
		$_POST['input_52'] = rgpost( 'input_65' );
		$_POST['input_53'] = "131";
	}
	else if($profession=="Counselor")
	{
		$_POST['input_52'] = rgpost( 'input_66' );
		$_POST['input_53'] = "132";
	}
	else if($profession=="Educational Diagnostician")
	{
		$_POST['input_52'] = rgpost( 'input_76' );
		$_POST['input_53'] = "141";
	}
	else if($profession=="Social Worker")
	{
		$_POST['input_52'] = rgpost( 'input_67' );
		$_POST['input_53'] = "33";
	}
	else if($profession=="Manager")
	{
		$_POST['input_52'] = rgpost( 'input_78' );
		$_POST['input_53'] = "148";
	}
	else if($profession=="Director")
	{
		$_POST['input_52'] = rgpost( 'input_79' );
		$_POST['input_53'] = "147";
	}
	else if($profession=="Vice President")
	{
		$_POST['input_52'] = rgpost( 'input_80' );
		$_POST['input_53'] = "143";
	}
	else if($profession=="CFO")
	{
		$_POST['input_52'] = rgpost( 'input_81' );
		$_POST['input_53'] = "145";
	}
	else if($profession=="CNO")
	{
		$_POST['input_52'] = rgpost( 'input_82' );
		$_POST['input_53'] = "144";
	}
	else if($profession=="CEO")
	{
		$_POST['input_52'] = rgpost( 'input_84' );
		$_POST['input_53'] = "150";
	}
	else if($profession=="COO")
	{
		$_POST['input_52'] = rgpost( 'input_85' );
		$_POST['input_53'] = "149";
	}
	else if($profession=="Controller")
	{
		$_POST['input_52'] = rgpost( 'input_83' );
		$_POST['input_53'] = "146";
	}
	else if($profession=="OT")
	{
		$_POST['input_52'] = rgpost( 'input_68' );
		$_POST['input_53'] = "11";
	}
	else if($profession=="Psychologist")
	{
		$_POST['input_52'] = rgpost( 'input_69' );
		$_POST['input_53'] = "134";
	}
	else if($profession=="PT")
	{
		$_POST['input_52'] = rgpost( 'input_70' );
		$_POST['input_53'] = "10";
	}
	else if($profession=="PTA")
	{
		$_POST['input_52'] = rgpost( 'input_75' );
		$_POST['input_53'] = "19";
	}
	else if($profession=="SLP")
	{
		$_POST['input_52'] = rgpost( 'input_71' );
		$_POST['input_53'] = "139";
	}
	else if($profession=="SLPA")
	{
		$_POST['input_52'] = rgpost( 'input_72' );
		$_POST['input_53'] = "140";
	}
	else if($profession=="SLI")
	{
		$_POST['input_52'] = rgpost( 'input_73' );
		$_POST['input_53'] = "136";
	}
	else if($profession=="Teacher")
	{
		$_POST['input_52'] = rgpost( 'input_74' );
		$_POST['input_53'] = "137";
	}
	else if($profession=="CNA")
	{
		$_POST['input_52'] = rgpost( 'input_37' );
		$_POST['input_53'] = "3";
	}
	else if($profession=="Nurse Practitioner")
	{
		$_POST['input_52'] = rgpost( 'input_39' );
		$_POST['input_53'] = "21";
	}
	else if($profession=="Allied Health Professional")
	{
		$_POST['input_52'] = rgpost( 'input_40' );
		if($allied_certification=="CST")
		{
			$_POST['input_59'] = rgpost( 'input_43' );
		}
		else if($allied_certification=="ORT")
		{
			$_POST['input_59'] = rgpost( 'input_44' );
		}
		else if($allied_certification=="Surgical First Assistant")
		{
			$_POST['input_59'] = rgpost( 'input_45' );
		}
		else if($allied_certification=="CT Technologist")
		{
			$_POST['input_59'] = rgpost( 'input_46' );
		}
		else if($allied_certification=="Rad Tech")
		{
			$_POST['input_59'] = rgpost( 'input_47' );
		}
		else if($allied_certification=="Ultrasound Tech")
		{
			$_POST['input_59'] = rgpost( 'input_48' );
		}
		else if($allied_certification=="X-Ray Tech")
		{
			$_POST['input_59'] = rgpost( 'input_49' );
		}
		else if($allied_certification=="Medical Technologist")
		{
			$_POST['input_59'] = rgpost( 'input_50' );
		}
		else if($allied_certification=="Medical Assistant")
		{
			$_POST['input_59'] = rgpost( 'input_51' );
		}
	}


}
add_action('http_api_curl', 'sar_custom_curl_timeout', 9999, 1);
	function sar_custom_curl_timeout( $handle ){
		curl_setopt( $handle, CURLOPT_CONNECTTIMEOUT, 30 ); // 30 seconds. Too much for production, only for testing.
		curl_setopt( $handle, CURLOPT_TIMEOUT, 30 ); // 30 seconds. Too much for production, only for testing.
	}
	// Setting custom timeout for the HTTP request
	add_filter( 'http_request_timeout', 'sar_custom_http_request_timeout', 9999 );
	function sar_custom_http_request_timeout( $timeout_value ) {
		return 30; // 30 seconds. Too much for production, only for testing.
	}
	// Setting custom timeout in HTTP request args
	add_filter('http_request_args', 'sar_custom_http_request_args', 9999, 1);
	function sar_custom_http_request_args( $r ){
		$r['timeout'] = 30; // 30 seconds. Too much for production, only for testing.
		return $r;
	}


add_filter( 'facetwp_index_row', function( $params, $class ) {
  if ( 'location' == $params['facet_name'] ) {
    $raw_value                     = $params['facet_value'];
    $params['facet_value']         = str_replace( ', ', '', $params['facet_display_value'] );
	$params['facet_display_value'] = $params['facet_display_value'];
  }
		if ( 'profession' == $params['facet_name'] ) {
    $raw_value                     = $params['facet_value'];
    $params['facet_value']         = str_replace(array(' ,', '&'), '', $params['facet_display_value'] );
	$params['facet_display_value'] = $params['facet_display_value'];
  }
	if ( 'speciality' == $params['facet_name'] ) {
    $raw_value                     = $params['facet_value'];
    $params['facet_value']         = str_replace(array(' ,', '&'), '', $params['facet_display_value'] );
	$params['facet_display_value'] = $params['facet_display_value'];
  }

  return $params;
}, 10, 2 );

add_filter( 'gform_confirmation', 'custom_confirmation', 10, 4 );
function custom_confirmation( $confirmation, $form, $entry, $ajax ) {
	$query="?";
	$profession=rgar( $entry, '1' );
	$specialty=rgar( $entry, '3' );
	$location=rgar( $entry, '2' );
	if(!empty($profession))
	{
		$profession=str_replace(array(' ,', '&'), '', $profession);
		$query .="_profession=".$profession."&";
	}
	if(!empty($specialty))
	{
		$specialty=str_replace(array(' ,', '&'), '', $specialty);
		$query .="_speciality=".sanitize_title_with_dashes($specialty)."&";

	}
	if(!empty($location))
	{
		//$location=str_replace(array(' ,', '&'), '', $location);
		$location=str_replace(", ","",$location);
		$query .="_location=".sanitize_title_with_dashes($location)."&";

	}
	$query=rtrim($query,"&");
    if( $form['id'] == '7' ) {
        //$confirmation = array( "redirect" => "https://jobs.axcessjobs.com/job-search/?_keyword='.$keyword.'";
		$confirmation = array( 'redirect' => 'https://www.giftedhealthcare.com/job-search/'.$query.'' );
    }
    return $confirmation;
}
add_filter( 'facetwp_use_search_relevancy', '__return_false' );

add_filter( 'gform_phone_formats', 'us_phone_format' );
function us_phone_format( $phone_formats ) {
    $phone_formats['us'] = array(
        'label'       => '(###) ###-####',
		'mask'        => '(999) 999-9999',
		'regex'       => '/^\D?([023456789]{1})(\d{1})(\d{1})\D?\D?(\d{3})\D?(\d{4})$/',
        'instruction' => 'Number must not start with 1',
    );

    return $phone_formats;
}


function gh_facet( $shortcode ) : void
{
    echo do_shortcode($shortcode);
}