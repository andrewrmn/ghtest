<?php
	class GHC_CTM_FEED_WANDERLY extends GHC_CTM_FEED {

		static function build(){
			$args = array();

			self::XMLHeader();
			self::outputXML($args);
			self::XMLFooter();
		}

		static function outputXML($additional_args){
		    date_default_timezone_set('America/Los_Angeles');

			$args = array(
				'post_type' => 'job_listing',
				'post_status' => 'publish',
				'posts_per_page' => -1,
			);

			$args = array_merge($args, $additional_args);
			$jobs = new WP_Query($args);

			if($jobs->have_posts()){
				while($jobs->have_posts()){
					$jobs->the_post();

					$id = get_the_ID();

					$ctm_id = get_post_meta($id, 'job_id', true);
					$client_type = strtolower(get_post_meta($id, 'client_type', true));
					$job_type = strtolower(self::getTerm($id, 'job_listing_type'));
					$job_length = get_post_meta( $id, 'week_day', true );

					$pay = self::filterPay(
						get_post_meta($id, '_raw_pay', true), 
						get_post_meta($id, '_raw_hourly_pay', true), 
						$job_type, 
						$client_type,
						get_post_meta($id, 'subject', true)
					);

					$start_date = date('m/d/Y',strtotime(get_post_meta($id, 'start_date', true)));


					if(!is_numeric($pay)){ continue; }

  			        $xmlArray = array(
		            	'referenceNumber'   => self::filterId( $ctm_id, $client_type, $job_type),
		            	'pay'				=> $pay,
		            	'profession'       	=> self::getTermCTM($id, 'job_listing_profession'),
		            	'specialty'         => self::getTermCTM($id, 'job_listing_specialty'),
			            'startDate'         => self::filterDate($start_date, $job_type, $job_length),
		            	'shift'				=> get_post_meta($id, 'shift', true),
		            	'hours'				=> get_post_meta($id, 'hours', true),
		            	'duration'			=> $job_length,
		            	'facility'			=> get_post_meta($id, 'client_name', true),
		            	'city'				=> get_post_meta($id, 'city', true),
		            	'state'				=> get_post_meta($id, 'state', true),
		            	'title'				=> get_the_title(),
		            	'description'		=> get_the_content(),
		            	'hourPerShift'		=> self::filterHoursPerShift(get_post_meta($id, 'shift_time', true))
			        );

			        self::outputNode($xmlArray);
				}
			}
		}

		static function filterId($id, $clientType, $listingType){
			if($listingType=='local' && ( $clientType=='travel-local' || $clientType=='travel-localapply')){
				return $id."L";
			}
			else{
				return $id;
			}

		}

		static function filterPay($pay_raw, $hourly_raw, $listingType, $clientType, $hoursFlag){
			if(empty($pay_raw)) return null;
			if(empty($hourly_raw)) $hourly_raw = 0;

			if(strtolower($listingType) == 'prn' || strtolower($listingType) == 'government') return null;

			if(strtolower($listingType) == 'local' && strtolower($clientType) == 'travel-localapply' ) return null;

			$localOT   	= 16 * $hourly_raw;
			$travelOT  	= 20 * $hourly_raw;

			# May need to adjust if 48hr contracts are involved.
			if(strtolower($listingType) == 'travel'){
				switch(strtolower($hoursFlag)){
					case '3648':
					case '36or48':
					case '48':
						$pay_raw += $travelOT;
						break;
				}
			}

			if(strtolower($listingType) == 'local'){
				switch(strtolower($hoursFlag)){
					case '3648':
					case '36or48':
					case '48':
						$pay_raw += $localOT;
						break;
				}
			}

			return $pay_raw;
		}

		static function filterDate($startDate, $jobType, $contractLength){
			if(strtolower($jobType) == 'prn' || strtolower($jobType) == 'government'){
				return 'ASAP';
			}
			elseif(strtolower($jobType) == 'school' && empty($contractLength)){
			        return 'ASAP';
			}			
			else{
				return $startDate;
			}
		}

		static function filterHoursPerShift($shiftHours){
			$shift = explode(' - ', $shiftHours);

			$startTime = strtotime($shift[0]);
			$endTime = strtotime($shift[1]);

			// Overnight shift?
			if($startTime > $endTime){ $endTime += 86400; }

			return round(($endTime - $startTime) / 3600, 2);
		}


	}
?>