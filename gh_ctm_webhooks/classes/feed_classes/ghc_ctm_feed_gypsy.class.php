<?php
	class GHC_CTM_FEED_GYPSY extends GHC_CTM_FEED {

		static function build(){
			$args = array(
				'tax_query' => array(
					'relation'=> 'AND',
					array(
						'relation' => 'OR',
						array(
							'taxonomy' => 'job_listing_profession',
							'field' => 'name',
							'terms' => 'Registered Nurse (RN)',
						),
						array(
							'taxonomy' => 'job_listing_profession',
							'field' => 'name',
							'terms' => 'Licensed Practical Nurse (LPN)',
						),						
					),
					array(
						'relation' => 'OR',
						array(
							'taxonomy' => 'job_listing_type',
							'field' => 'name',
							'terms' => 'Travel',
						),
						array(
							'taxonomy' => 'job_listing_type',
							'field' => 'name',
							'terms' => 'Local',
						),
					),
				)	
			);

			$query = new WP_Query( $args );
			
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
			            'date'          	=> get_the_date('m/d/Y'),
			            'jobtitle'          => get_the_title(),
			            'jobid'		   		=> get_post_meta($id, 'job_id', true),
			            'city'              => get_post_meta($id, 'city', true),
			            'state'             => get_post_meta($id, 'state', true),
			            'zipcode'        	=> get_post_meta($id, 'zip_code', true),
			            'license'          	=> self::getTermCTM($id, 'job_listing_profession'),
			            'specialty'         => self::getTermCTM($id, 'job_listing_specialty'),
			            'description'       => self::buildDescription($id, get_the_content(), true, false),
			            'startdate'         => date('m/d/Y',strtotime(get_post_meta($id, 'start_date', true))),
			            'enddate'         	=> date('m/d/Y',strtotime(get_post_meta($id, 'end_date', true))),
			            'shift'				=> self::filterShift(get_post_meta($id, 'shift', true)),
			            'requirements'		=> get_the_content(),
			            'benefits'			=> self::benefits(),
			            'pay'				=> get_post_meta($id, 'pay_package', true),
			        );
			        self::outputNode($xmlArray);
				}
			}
		}

		static function filterShift($shift){
			if($shift == 'Other Shift' || $shift == 'Night Shift') return 'Nights';

			return 'Days';
		}

		static function filterSpecialty($spec){

			$map = array(
				'Admn'						=> 'Admin',
				'Ambulatory Care Clinic'	=> 'Ambulatory',
				'Cardiovascular Services'	=> 'Cardiac',
				'Case Mgmt'					=> 'Case Management',
				'Cath Lab'					=> 'Cath Lab',
				'Clinic'					=> 'Clinic',
				'Corrections'				=> 'Correctional',
				'COTA'						=> 'COTA',
				'CRNA'						=> 'CRNA',
				'CT Tech'					=> 'CT Scan Tech',
				'CVICU'						=> 'CVICU',
				'CVOR'						=> 'CVOR',
				'Dialysis'					=> 'Dialysis',
				'Echocardiography'			=> 'Echo Tech',
				'Endoscopy'					=> 'Endoscopy',
				'Environmental Health'		=> 'Environmental Health',
				'ER'						=> 'ER',
				'Home Health'				=> 'Home Health',
				'Hospice'					=> 'Hospice',
				'ICU'						=> 'ICU',
				'Infection Control'			=> 'Infectious Disease',
				'ICU'						=> 'Intermediate Care Unit',
				'Interventional Radiology'	=> 'Interventional Radiology',
				'L&D'						=> 'L&D',
				'Medical Lab Technologist'	=> 'Lab Tech',
				'LPN'						=> 'LPN',
				'LTAC'						=> 'LTAC',
				'LTC'						=> 'LTC',
				'MedSurg'					=> 'Medical-Surgical',
				'Mother/Baby'				=> 'Mother Baby',
				'MRI'						=> 'MRI Tech',
				'NICU 3'					=> 'NICU',
				'Neuro'						=> 'Neurology',
				'Ob/Gyn'					=> 'Obstetrics Gynecology',
				'Oncol'						=> 'Oncology',
				'OR'						=> 'OR',
				'Ortho'						=> 'Ortho',
				'OT'						=> 'OT',
				'PACU'						=> 'PACU',
				'PCU'						=> 'PCU',
				'Peds'						=> 'Pediatric',
				'Peds - ER' 				=> 'Pediatric ER',
				'Peds - OR'					=> 'Pediatric OR',
				'Pharmacy'					=> 'Pharmacy',
				'PICU'						=> 'PICU',
				'Pre-Op'					=> 'Pre-Operative',
				'Private Duty'				=> 'Private Duty',
				'Psych'						=> 'Psychiatric',
				'PT'						=> 'PT',
				'PTA'						=> 'PTA',
				'Public Health'				=> 'Public Health',
				'Radiology'					=> 'Radiology',
				'Rehab'						=> 'Rehab',
				'Dialysis'					=> 'Renal Dialysis',
				'Respiratory'				=> 'RRT',
				'Skilled Nursing'			=> 'Skilled Nursing Facility',
				'Sleep Technologist'		=> 'Sleep Tech',
				'Stepdown'					=> 'Stepdown',
				'Sterile Processing Tech '	=> 'Sterile Processing Tech',
				'Sterile Processing Tech'	=> 'Sterile Processing Tech',
				'Certified Surgical Tech'	=> 'Surgical Tech',
				'Tele'						=> 'Tele',
				'Ultrasound Tech'			=> 'Ultrasound Tech',
				'X-Ray'						=> 'X-Ray Tech',
			);

			if(isset($map[$spec])) return $map[$spec];

			return 'Other';
		}		
	}
?>