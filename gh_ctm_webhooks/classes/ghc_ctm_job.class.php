<?php
	class GHC_CTM_JOB {

		public static function create($payload, $recursive = false){
			# First thing's first: Does it already exist?
			$postID = self::getPostByCtmID($payload->assignmentid);

			# If it exists for some reason, update instead.
			if($postID && !$recursive){
				return self::update($payload);
			}

			# Only keep the Open status.
			if($payload->status != 'open'){
				return self::void($payload);
			}

			# Put the travel-local / travel-localapply split here.
			# - Massage the jobtype here instead of in GeneratePostArr
			# - Get the Facility details here too.
			# - If it's one of the new trigger types
			# - Set the 'is both' flag on the payload.
			# - Run the new function [see below]
			# - Manually change the job type to Local, run the new function again [see below].
			$facility 	= GHC_CTM_API::getMetaForFacility($payload->facilityid);
			$listing 	= GHC_CTM_API::getMetaForJob($payload->assignmentid);
			$payload->jobtype = self::massage('job_listing_type', $listing);

			# Always do it at least once.
			self::insertSingleJob($payload, $facility);

			# If it's a dual listing, so also do a local.
			# Testing only: Do 'no selection'.
			if(  strtolower($payload->jobtype) == 'travel' && ( strtolower($facility->clienttype) == 'travel-local' || strtolower($facility->clienttype) == 'travel-localapply')){
				$fake_payload = (object) array('clientregionname'=>'','ordertypelt'=>'Contract');
				$payload->jobtype = self::massage('job_listing_type', $fake_payload);
				self::insertSingleJob($payload, $facility);
			}
		}

		public static function insertSingleJob($payload, $facility){
			$postArr = self::generate_postarr($payload, $facility);

			if(!empty($postArr)){
				if(isset($postArr['meta_input'])){
					$meta = $postArr['meta_input'];
					unset($postArr['meta_input']);
				}

				$postID = wp_insert_post($postArr, true);
				self::do_meta($postID, $meta);

				# Facet isn't automatically picking up on the taxonomy stuff.  So make it.
				if ( function_exists( 'FWP' ) ) {
	  				FWP()->indexer->index($postID);
	  			}
			}
		}

		public static function update($payload, $recursive = false){
			$postID = self::getPostByCtmID($payload->assignmentid);
			
			# If it doesn't exist in the system, create.
			if(!$postID && !$recursive){
				return self::create($payload);
			}

			# Only keep the Open status.
			if($payload->status != 'open'){
				return self::void($payload);
			}

			# Pull these here, as they're needed for multi-listings.
			$facility 	= GHC_CTM_API::getMetaForFacility($payload->facilityid);
			$listing 	= GHC_CTM_API::getMetaForJob($payload->assignmentid);
			$payload->jobtype = self::massage('job_listing_type', $listing);

			# This is an update, so get each one individually. 
			# Make 
			$postID = self::getPostByCtmIDandJobtype($payload->assignmentid, $payload->jobtype);
			if($postID){
				self::updateSingleJob($payload, $facility, $postID);
			}
			else{
				self::insertSingleJob($payload, $facility);
			}

			
			# If it's a dual listing, so also do a local.
			if(  strtolower($payload->jobtype) == 'travel' && ( strtolower($facility->clienttype) == 'travel-local' || strtolower($facility->clienttype) == 'travel-localapply')){
				$fake_payload = (object) array('clientregionname'=>'','ordertypelt'=>'Contract');
				$payload->jobtype = self::massage('job_listing_type', $fake_payload);

				$postID = self::getPostByCtmIDandJobtype($payload->assignmentid, $payload->jobtype);
				if($postID){
					self::updateSingleJob($payload, $facility, $postID);
				}
				else{
					self::insertSingleJob($payload, $facility);
				}
			}

		}

		public static function updateSingleJob($payload, $facility, $postID){
			$postArr = self::generate_postarr($payload, $facility);

			if(!empty($postArr)){

				$postArr['ID'] = $postID;
				wp_update_post($postArr);

				if(isset($postArr['meta_input'])){
					$meta = $postArr['meta_input'];
					unset($postArr['meta_input']);
					self::do_meta($postID, $meta);
				}

				# Facet isn't automatically picking up on the taxonomy stuff.  So make it.
				if ( function_exists( 'FWP' ) ) {
	  				FWP()->indexer->index($postID);
	  			}

				return $postID;
			}
			else{
				return self::void($payload);
			}
		}

		public static function void($payload){
			$postIDs = self::getPostByCtmID($payload->assignmentid);

			if(empty($postIDs) || !is_object($payload)) return;

			foreach($postIDs as $id){
				if(is_numeric($id)){
					$id = (int)$id;
				}

  				wp_delete_post($id);
			}

			return;
		}

		public static function fill($payload){
			$postIDs = self::getPostByCtmID($payload->assignmentid);

			if(empty($postIDs) || !is_object($payload)) return;

			foreach($postIDs as $id){
  				update_post_meta($id, '_filled', 1);

				# Facet isn't automatically picking up on the taxonomy stuff.  So make it.
				if ( function_exists( 'FWP' ) ) {
	  				FWP()->indexer->index($id);
	  			}

			}
			
			return;
		}

		public static function do_meta($ID, $meta_array){
			if( empty($ID) ) return;

			if( !empty($meta_array) ){
				foreach($meta_array as $key=>$value ){
					update_post_meta($ID, $key, $value);
				}
			}
		}

		public static function generate_postarr($payload, $facility){
			# Gather Data 1: Gather a bunch of data.
			$profTermObj = GHC_CTM_CERT_SPEC::fetch_term_by_CTM_name($payload->cert, 'job_listing_profession');
			$specTermObj = GHC_CTM_CERT_SPEC::fetch_term_by_CTM_name($payload->spec, 'job_listing_specialty');

			# Exclusion 1: If Cert or Spec are blank...we're done here.
			if( empty($profTermObj) || empty($specTermObj) ){
				return;
			}

			# Exclusion 2: If Cert or Spec are filtered out...we're done here.
			if( get_term_meta($profTermObj->term_id, 'ctm_active', true) != 1 || get_term_meta($specTermObj->term_id, 'ctm_active', true) != 1 ){
				return;	
			} 
			
			# Gather Data 2: Get the various bits of meta:
			$listing 	= GHC_CTM_API::getMetaForJob($payload->assignmentid);

			# Exclusion 3: City or State are blank, we're done here.
			if( empty($listing->city) || empty($listing->state) ){
				return;
			}

			# Exclusion 4: "extension" jobs?  We're done here.
			if( strpos(strtolower($payload->facilityname), 'extension') !== false){
				return;
			} 

			# Exclusion 5: Order Rating "G or P" for "No-Go".
			if( strtoupper($listing->orderrating) == "G" || strtoupper($listing->orderrating) == "P"){
				return;
			}

			# Exclusion 6: TeaserNote contains 'DNP'
			if( strpos($listing->teasernote, 'DNP') !== false){
				return;
			}

			# Exclusion 7: If Local, the special travel-local-duping flag is set, and rating is E or S.
			if(  
				strtolower($payload->jobtype) == 'local' 
				&& ( strtolower($facility->clienttype) == 'travel-local' || strtolower($facility->clienttype) == 'travel-localapply')
				&& (strtoupper($listing->orderrating) == "E" || strtoupper($listing->orderrating) == "S")
			){
				return;
			}


			# Massage 2: Start Date
			$payload->shiftdatestart = self::massage('start_date',$payload);

			# And JobTypeID
			$term = get_terms( 	array(
									'taxonomy' => 'job_listing_type',
									'name' => $payload->jobtype,
									'parent' => 0,
									'hide_empty' => false,
								) 
					);

			if( empty($term) ) $payload->jobtypeid = '';
			else $payload->jobtypeid = $term[0]->term_id;

			# Exclusion 7: Start Date in the past and not 'school'
			if( date('Ymd',strtotime($payload->shiftdatestart)) < date('Ymd') && strtolower($payload->jobtype) !== 'school'){
				return;
			}

			# Gather Data 3: Pay Package
			$pay 		= GHC_CTM_API::getPayForJob($payload->assignmentid, $payload->jobtype);

			# Title Components:
			$title = array(
				0 => $payload->jobtype,
				1 => $specTermObj->name,
				2 => $profTermObj->name,
				3 => 'job in',
				4 => trim(rtrim($listing->city)).', '.trim(rtrim($listing->state))
			);

			# Gather necessary data for dupe checking.
			$cert_ctm = get_term_meta($profTermObj->term_id, 'ctm_text', true);
			$spec_ctm = get_term_meta($specTermObj->term_id, 'ctm_text', true);
			$spec_dupe = get_term_meta($specTermObj->term_id, 'ctm_dupe', true);

			# DQ the Specs if the display name OR the CTM name match:
			if( $specTermObj->name == $profTermObj->name ) unset($title[1]);
			if( $spec_ctm == $cert_ctm ) unset($title[1]);
			if( !empty($spec_dupe) && $spec_dupe == $profTermObj->term_id) unset($title[1]);

			# DQ the Spec if the Job Type matches the Display or CTM Name.
			if( !empty($spec_ctm) && strtolower($payload->jobtype) == strtolower($spec_ctm)) unset($title[1]);
			if( !empty($spec_dupe) && strtolower($payload->jobtype) == strtolower($spec_dupe)) unset($title[1]);


			# Step X: Create the post itself.
			$postarr = array(
				"post_content"		=> 	$listing->teasernote,
				"post_title"		=> 	implode(' ',$title),
				"post_status"		=> 	'publish',
				"post_type"			=> 	'job_listing',
				"post_name"			=>  sanitize_title($payload->assignmentid.' '.implode(' ',$title)),
				"tax_input"			=> 	array(
											'job_listing_profession'	=> array($profTermObj->name),
											'job_listing_specialty'		=> array($specTermObj->name),
											'job_listing_type'			=> array($payload->jobtypeid),
										),
				"meta_input"		=>	array(
											'job_id'				=> $payload->assignmentid,
											'region'				=> $listing->clientregionname,
											'start_date'			=> $payload->shiftdatestart,
											'end_date'				=> $payload->shiftdateend,
											'hot_job'				=> $listing->ishotjob,
											'therapia_job'			=> 0,
											'shift'					=> self::massage('shift_name',$listing->starttime),
											'shift_time'			=> self::massage('shift_hours',$listing),
											'hours'					=> floatval($listing->hoursperweek) + floatval($listing->othoursperweek), // Make sure hours per week / OT hours per week are the right data type, PHP occasionally gets it wrong.
											'week_day'				=> self::massage('weeks',$payload),
											'zip_code'				=> $listing->zipcode,
											'client_name'			=> $payload->facilityname,
											'num_beds'				=> $facility->numbeds,
											'_raw_pay'				=> (is_object($pay) && isset($pay->totalweeklypaypackage))? $pay->totalweeklypaypackage : '',
											'_raw_hourly_pay'		=> (is_object($pay) && isset($pay->hourlypayrate))? $pay->hourlypayrate : '',
											'pay_package'			=> self::massage('pay',$pay, $payload),
											'city'					=> $listing->city,
											'state'					=> $listing->state,
											'_job_location'			=> $listing->city .','.$listing->state,  // Used for Geocoding.  As much of the address (check Facility) as possible.
											'_job_expires'			=> self::massage('expires',$payload),
											'client_type'			=> $facility->clienttype,
											'order_rating'			=> $listing->orderrating,
											'subject'				=> $payload->subject,
										)
			);

			return $postarr;
		}

		public static function getPostByCtmID($ctm_id){
			if(!$ctm_id) return array();

			global $wpdb;
			$jobID = $ctm_id;

			$query = $wpdb->prepare("select * from {$wpdb->prefix}postmeta where meta_key='job_id' and meta_value=%s", array($jobID));
			$results = $wpdb->get_results($query);

			$return = array();

			foreach($results as $result){
				if(is_numeric($result->post_id)){
					$return[] = $result->post_id;	
				} 
			}

			return $return;
		}

		public static function getPostByCtmIDandJobtype($ctm_id, $massaged_jobtype){
			if(!$ctm_id || !$massaged_jobtype) return array();

			global $wpdb;
			$jobID = $ctm_id;

			$query = $wpdb->prepare("select * from {$wpdb->prefix}postmeta, {$wpdb->prefix}term_relationships, {$wpdb->prefix}term_taxonomy, {$wpdb->prefix}terms where meta_key='job_id' and meta_value=%s and post_id={$wpdb->prefix}term_relationships.object_id and {$wpdb->prefix}term_relationships.term_taxonomy_id={$wpdb->prefix}term_taxonomy.term_taxonomy_id and {$wpdb->prefix}term_taxonomy.term_id={$wpdb->prefix}terms.term_id and {$wpdb->prefix}terms.name=%s limit 1", array($jobID, $massaged_jobtype));
			$results = $wpdb->get_results($query);

			$return = null;
			foreach($results as $result){
				if(is_numeric($result->post_id)){
					$return = $result->post_id;	
				} 
			}

			return $return;
		}

		public static function massage($field, $value, $secondary_value = null){
			switch($field){
				case 'job_listing_type':
					return self::massage_job_listing_type($value);
					break;

				case 'shift_name':
					return self::massage_shift_name($value);
					break;

				case 'shift_hours':
					return self::massage_shift_hours($value);
					break;

				case 'weeks':
					return self::massage_weeks($value);
					break;

				case 'pay':
					return self::massage_pay($value, $secondary_value);
					break;

				case 'start_date':
					return self::massage_start_date($value);
					break;

				case 'expires':
					return self::massage_expires($value);
					break;					
			}
		}

		private static function massage_pay($payObject, $payload){
			if(empty($payObject)) return '';

			$base   	= $payObject->totalweeklypaypackage;
			$hourly 	= $payObject->hourlypayrate;
			$localOT   	= 16 * $hourly;
			$travelOT  	= 20 * $hourly;

			# Default: 90 to 100% of base pay.
			$min = ceil($base * 0.9);
			$max = ceil($base);

			# May need to adjust the upper or upper+lower bounds if 48hr contracts are involved.
			if(strtolower($payload->jobtype) == 'travel'){
				switch(strtolower($payload->subject)){
					case '48':
						$min += $travelOT;
						$max += $travelOT;
						break;

					case '3648':
					case '36or48':
						$max += $travelOT;
						break;
				}
			}

			if(strtolower($payload->jobtype) == 'local'){
				switch(strtolower($payload->subject)){
					case '48':
						$min += $localOT;
						$max += $localOT;
						break;

					case '3648':
					case '36or48':
						$max += $localOT;
						break;
				}
			}
			
			return '$'.number_format($min).' - $'.number_format($max).'/week';
		}

		private static function massage_weeks($listing){

			$start = new DateTimeImmutable($listing->shiftdatestart);
			$end = new DateTimeImmutable($listing->shiftdateend);
			$interval = $start->diff($end);

			# 'School' has some additional massaging.
			if(strtolower($listing->jobtype) == 'school'){
				$month = date('n', strtotime($listing->shiftdatestart));

				if($month <= 7){
					# If start date is between 1/1-7/31: show the contract length as is
					# Technically doesn't need to be here, but helps with 'get back up to speed' a year from now.
				}
				elseif($month >= 8 && $month <= 10){
					# If start date is between 8/1-10/31: subtract 6 weeks from the contract length
					$end = $end->sub(DateInterval::createFromDateString('42 day'));
					$interval = $start->diff($end);
				}
				elseif($month >= 11){
					# If start date is between 11/1-12/31: subtract 3 weeks from the contract length
					$end = $end->sub(DateInterval::createFromDateString('21 day'));
					$interval = $start->diff($end);
				}

				if($interval->format('%a') < 7){
					return '0';
				}
			}

			return ceil($interval->format('%a') / 7).' weeks';
		}

		private static function massage_shift_hours($listing){
			$startTime = date("g:i A", strtotime($listing->starttime));
			$endTime = date("g:i A", strtotime($listing->endtime));

			return "$startTime - $endTime";
		}

		private static function massage_shift_name($value){
			# Strip the value down to just the H:i part.
			$startTime = date("Hi", strtotime($value));

			# Now compare.
			if($startTime >= 1 && $startTime <= 559) return 'Other Shift';
			if($startTime >= 600 && $startTime <= 959) return 'Day Shift';
			if($startTime >= 1000 && $startTime <= 1759) return 'Mid Shift';
			if($startTime >= 1800 || $startTime == 0) return 'Night Shift';

			return null;
		}

		private static function massage_job_listing_type($listing){
			if( strtolower($listing->clientregionname) == 'government' || strtolower($listing->clientregionname) == 'rmrg - govt'){
				$value = 'government';
			}
			elseif( strtolower($listing->clientregionname) == 'ts-school local' || strtolower($listing->clientregionname) == 'ts-school travel') {
				$value = 'school';	
			}
			else{
				$value = $listing->ordertypelt;
			}

			switch(trim(strtolower($value))){
				case "covid-19 critical need":
				case "covid-19 travel":
				case "travel":
					$value = "Travel";
					break;

				case "contract":
				case "contract critical need":
				case "covid-19 contract":
					$value = "Local";
					break;

				case "critical need":
				case "prn - marketing":
					$value = "PRN";
					break;

				case "interim":
					$value = "Interim Leadership";
					break;

				case "government":
					$value = "Government";
					break;

				case "school":
					$value = "School";
					break;

				default:
					return null;
			}

			return $value;
		}

		private static function massage_start_date($listing){
			if(strtolower($listing->jobtype) == 'school'){
				# For School, start date is either "2 weeks in the future" or listed, whichever is greater.
				$today_ts = strtotime( date( 'Y-m-d 00:00:00'));
				$start_ts = strtotime( $listing->shiftdatestart);

				if($today_ts > $start_ts){
					# Add 2 weeks.
					return date('Y-m-d', $today_ts + (86400 * 14));
				}
				else{
					return $listing->shiftdatestart;
				}
			}
			else{
				return $listing->shiftdatestart;
			}
		}

		private static function massage_expires($listing){
			# This has to return something; if it's null, Job Manager will insert "+30 Days" the first time you edit through the UI.
			if(strtolower($listing->jobtype) == 'school'){
				return '2199-12-31';
			}
			else{
				return $listing->shiftdatestart;
			}
		}
	}
?>