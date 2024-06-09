<?php
	class GHC_CTM_FEED_ACS extends GHC_CTM_FEED {

		static function build(){
			$args = array(
				'tax_query' => array(
					'relation' => 'OR',
					array(
						'taxonomy' => 'job_listing_profession',
						'field' => 'name',
						'terms' => 'Cath Lab Technician',
					),
					array(
						'taxonomy' => 'job_listing_profession',
						'field' => 'name',
						'terms' => 'Certified Surgical Technologist',
					),
					array(
						'taxonomy' => 'job_listing_profession',
						'field' => 'name',
						'terms' => 'CT Technologist',
					),
					array(
						'taxonomy' => 'job_listing_profession',
						'field' => 'name',
						'terms' => 'Emergency Medical Technician',
					),
					array(
						'taxonomy' => 'job_listing_profession',
						'field' => 'name',
						'terms' => 'Endoscopy Tech',
					),
					array(
						'taxonomy' => 'job_listing_profession',
						'field' => 'name',
						'terms' => 'Medical Assistant',
					),
					array(
						'taxonomy' => 'job_listing_profession',
						'field' => 'name',
						'terms' => 'Medical Technologist',
					),
					array(
						'taxonomy' => 'job_listing_profession',
						'field' => 'name',
						'terms' => 'Monitor Tech',
					),
					array(
						'taxonomy' => 'job_listing_profession',
						'field' => 'name',
						'terms' => 'MRI Technologist',
					),
					array(
						'taxonomy' => 'job_listing_profession',
						'field' => 'name',
						'terms' => 'Operating Room Technician',
					),
					array(
						'taxonomy' => 'job_listing_profession',
						'field' => 'name',
						'terms' => 'Rad Tech',
					),
					array(
						'taxonomy' => 'job_listing_profession',
						'field' => 'name',
						'terms' => 'Registered Respiratory Therapist',
					),
					array(
						'taxonomy' => 'job_listing_profession',
						'field' => 'name',
						'terms' => 'Sterile Processing Tech',
					),
					array(
						'taxonomy' => 'job_listing_profession',
						'field' => 'name',
						'terms' => 'Surgical First Assistant',
					),
					array(
						'taxonomy' => 'job_listing_profession',
						'field' => 'name',
						'terms' => 'Ultrasound Tech',
					),
				)	
			);

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
					
  			        $xmlArray = array(
	            		'title'             => get_the_title(),
	            		'referenceNumber'   => get_post_meta($id, 'job_id', true),
	            		'city'              => get_post_meta($id, 'city', true),
	            		'state'             => get_post_meta($id, 'state', true),
	            		'postalCode'        => get_post_meta($id, 'zip_code', true),
	            		'license'          	=> self::getTermCTM($id, 'job_listing_profession'),
		            	'specialty'         => self::getTermCTM($id, 'job_listing_specialty'),
		            	'payFrequency'		=> 'weekly',
		            	'startDate'         => date('m/d/Y',strtotime(get_post_meta($id, 'start_date', true))),
	            		'shiftType'         => self::filterShift(get_post_meta($id, 'shift', true)),
		            	'description'		=> self::buildDescription($id, get_the_content(), true, true),
			            'durationLength'	=> str_replace(' weeks', '', get_post_meta($id, 'week_day', true)),
			            'durationUnit'		=> 'week',

			        );

			        if(self::filterPay(get_post_meta($id, 'pay_package',true),true) !== false){
			        	$xmlArray['payAmountMin'] = self::filterPay(get_post_meta($id, 'pay_package',true),true);
			        }

			        if(self::filterPay(get_post_meta($id, 'pay_package',true),false) !== false){
			        	$xmlArray['payAmountMax'] = self::filterPay(get_post_meta($id, 'pay_package',true),false);
			        }

			        self::outputNode($xmlArray);
				}
			}
		}

		static function filterShift($shift){
			if($shift == 'Other Shift' || $shift == 'Night Shift') return 'overnight';

			if($shift == 'Mid Shift') return 'evening';

			return 'day';
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
	}
?>