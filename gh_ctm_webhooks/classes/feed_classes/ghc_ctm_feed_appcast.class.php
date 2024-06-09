<?php
	class GHC_CTM_FEED_APPCAST extends GHC_CTM_FEED {

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

					if(strtolower(get_post_meta($id, 'order_rating', true)) == 'c'){ continue;}

					$ctm_id = get_post_meta($id, 'job_id', true);
					$client_type = strtolower(get_post_meta($id, 'client_type', true));
					$job_type = self::getTerm($id, 'job_listing_type');
					$job_length = get_post_meta( $id, 'week_day', true );

					$start_date = date('m/d/Y',strtotime(get_post_meta($id, 'start_date', true)));
					$end_date = date('m/d/Y',strtotime(get_post_meta($id, 'end_date', true)));

  			        $xmlArray = array(
		            	'referenceNumber'   => self::filterId( $ctm_id, $client_type, $job_type),
		            	'url'               => get_the_permalink(),
		            	'title'				=> get_the_title(),
		            	'description'		=> get_the_content(),
		            	'city'				=> get_post_meta($id, 'city', true),
		            	'state'				=> get_post_meta($id, 'state', true),
		            	'facility'			=> get_post_meta($id, 'client_name', true),
		            	'pay'				=> self::filterPay(get_post_meta($id, 'pay_package', true), false),
		            	'profession'       	=> self::getTermCTM($id, 'job_listing_profession'),
		            	'specialty'         => self::getTermCTM($id, 'job_listing_specialty'),
		            	'jobType'			=> $job_type,
			            'startDate'         => self::filterDate($start_date, $job_type, $job_length),
			            'endDate'         	=> self::filterDate($start_date, $job_type, $job_length),
		            	'shift'				=> get_post_meta($id, 'shift', true),
		            	'hourPerShift'		=> self::filterHoursPerShift(get_post_meta($id, 'shift_time', true)),
		            	'duration'			=> $job_length,
		            	'hours'				=> get_post_meta($id, 'hours', true),
			        );

			        self::outputNode($xmlArray);
				}
			}
		}

		static function filterId($id, $clientType, $listingType){
			$listingType = strtolower($listingType);
			if($listingType=='local' && ( $clientType=='travel-local' || $clientType=='travel-localapply')){
				return $id."L";
			}
			else{
				return $id;
			}

		}

		static function filterPay($pay, $low=true){
			$pay = str_replace('$','',$pay);
			$pay = str_replace('/week','',$pay);
			$pay = str_replace(',','',$pay);
			$pay = explode(' - ',$pay);

			if($low && isset($pay[0])) return $pay[0];
			if(!$low && isset($pay[1])) return $pay[1];

			return false;
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