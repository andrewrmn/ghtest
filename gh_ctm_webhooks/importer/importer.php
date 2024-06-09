<?php
	require_once($_SERVER['DOCUMENT_ROOT'].'/wp-load.php');

	$certs = json_decode(file_get_contents('cert-config.json'));
	$specs = json_decode(file_get_contents('spec-config.json'));

	global $wpdb;

	foreach($certs as $cert){
		$tData = wp_create_term($cert->certLabel, 'job_listing_profession');

		$termID = null;

		if(is_int($tData)) $termID = $tData;

		if(is_array($tData)) $termID = $tData['term_id'];

		if($termID){
			update_term_meta($termID, 'ctm_text', $cert->certName);
			update_term_meta($termID, 'ctm_active', $cert->active);
		}
	}

	foreach($specs as $spec){
		$tData = wp_create_term($spec->specLabel, 'job_listing_specialty');

		$termID = null;

		if(is_int($tData)) $termID = $tData;

		if(is_array($tData)) $termID = $tData['term_id'];

		if($termID){
			update_term_meta($termID, 'ctm_text', $spec->specName);
			update_term_meta($termID, 'ctm_active', $spec->active);
		}
	}

?>