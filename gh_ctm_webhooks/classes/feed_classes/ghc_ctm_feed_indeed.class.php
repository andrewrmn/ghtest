<?php
	class GHC_CTM_FEED_INDEED extends GHC_CTM_FEED {

		static function build(){
			$args = array(
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
		            	'date'              => get_the_date('m/d/Y H:i:s'),
		            	'referencenumber'   => get_post_meta($id, 'job_id', true),
		            	'requisitionid'		=> get_post_meta($id, 'job_id', true),
		            	'url'               => get_the_permalink(),
		            	'company'           => 'Gifted Healthcare',
		            	'city'              => get_post_meta($id, 'city', true),
		            	'state'             => get_post_meta($id, 'state', true),
		            	'country'           => 'US',
		            	'postalcode'        => get_post_meta($id, 'zip_code', true),
		            	'email'				=> 'info@giftedhealthcare.com',
		            	'description'       => self::buildDescription($id, self::buildThreeByTwo($id), true, true),
		            	'salary'			=> get_post_meta($id, 'pay_package',true),
		            	'jobtype'           => self::getTerm($id, 'job_listing_type'),
		            	'category'          => self::getTermCTM($id, 'job_listing_profession').','.self::getTermCTM($id, 'job_listing_specialty'),
			            'sponsored'         => (get_post_meta($id, 'zip_code', true))? 'Y':'N',
			        );

			        $start  = get_post_meta($id, 'start_date', true);
			        $end    = get_post_meta($id, 'end_date', true);

			        if(date('m/d/Y',strtotime($start)) != '01/01/1970' && date('Y',strtotime($start)) != '-0001'){
			            $xmlArray['start'] = '';

			            if(!empty($start)){
			                $xmlArray['start'] = date('m/d/Y',strtotime($start));
			            }
			        }

			        if(date('m/d/Y',strtotime($end)) != '01/01/1970' && date('Y',strtotime($end)) != '-0001'){
			            $xmlArray['end'] = '';

			            if(!empty($end)){
			                $xmlArray['end'] = date('m/d/Y',strtotime($end));
			            }
			        }

			        self::outputNode($xmlArray);
				}
			}
		}
	}
?>