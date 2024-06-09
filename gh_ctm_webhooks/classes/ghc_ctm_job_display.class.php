<?php
	class GHC_CTM_JOB_DISPLAY {

		# Trim these in the near future; we're not using most of them in templates.
		
		# Messages:
		private static $placeholder = 'Apply now for details';

		# Taxonomy fields
		private static $job_type;
		private static $profession;

		# Meta fields
		private static $job_id;
		private static $region;
		private static $start_date;
		private static $end_date;
		private static $hot_job;
		private static $therapia_job;
		private static $shift;
		private static $shift_time;
		private static $hours;
		private static $week_day;		// Contract length, in weeks
		private static $zip_code;
		private static $client_name;
		private static $num_beds;
		private static $_raw_pay;
		private static $pay_package;
		private static $city;
		private static $state;
		private static $_job_location;
		private static $_job_expires;
		private static $client_type;
		private static $order_rating;
		private static $subject;

		public static function load($id){
			# Set taxonomy data
			$profession = wp_get_post_terms( $id, 'job_listing_profession');
			self::$profession = (is_array($profession) && !empty($profession))? $profession[0]->name : self::$placeholder;

			$job_type = wp_get_post_terms( $id, 'job_listing_type');
			self::$job_type = (is_array($job_type) && !empty($job_type))? $job_type = $job_type[0]->name : self::$placeholder;

			# Set meta data
			self::$job_id = get_post_meta($id, 'job_id', true);
			self::$region = get_post_meta($id, 'region', true);
			self::$start_date = get_post_meta($id, 'start_date', true);
			self::$end_date = get_post_meta($id, 'end_date', true);
			self::$hot_job = get_post_meta($id, 'hot_job', true);
			self::$therapia_job = get_post_meta($id, 'therapia_job', true);
			self::$shift = get_post_meta($id, 'shift', true);
			self::$shift_time = get_post_meta($id, 'shift_time', true);
			self::$hours = get_post_meta($id, 'hours', true);
			self::$week_day = get_post_meta($id, 'week_day', true);
			self::$zip_code = get_post_meta($id, 'zip_code', true);
			self::$client_name = get_post_meta($id, 'client_name', true);
			self::$num_beds = get_post_meta($id, 'num_beds', true);
			self::$_raw_pay = get_post_meta($id, '_raw_pay', true);
			self::$pay_package = get_post_meta($id, 'pay_package', true);
			self::$city = get_post_meta($id, 'city', true);
			self::$state = get_post_meta($id, 'state', true);
			self::$_job_location = get_post_meta($id, '_job_location', true);
			self::$_job_expires = get_post_meta($id, '_job_expires', true);
			self::$client_type = get_post_meta($id, 'client_type', true);
			self::$order_rating = get_post_meta($id, 'order_rating', true);
			self::$subject = get_post_meta($id, 'subject', true);
		}

		public static function get($field){
			if( isset(self::${$field}) ){
				return self::${$field};
			}
		}

		public static function echo($field){
			if( isset(self::${$field}) ){
				echo self::${$field};
			}
		}

		private static function maybe_echo($echo, $output){
			if($echo){
				echo $output;
			} 
			else {
				return $output;
			}			
		}

		public static function filtered_start_date($echo = true){
			# Basic
			if(strtolower(self::$job_type) == 'school' && empty(self::$week_day)){
				$out = self::$placeholder;
			}
			elseif(empty(self::$start_date)){
				$out = self::$placeholder;	
			}
			else {
				$out	= date( 'n/j/Y', strtotime(self::$start_date) );
			}

			return self::maybe_echo($echo, $out);
		}

		public static function filtered_date_range($echo = true){
			# Basic
			if(strtolower(self::$job_type) == 'school' && empty(self::$week_day)){
				$out = self::$placeholder;
			}
			elseif(empty(self::$start_date)){
				$out = self::$placeholder;	
			}
			else {
				$start_formatted	= date( 'n/j/Y', strtotime(self::$start_date) );
				$end_formatted		= date( 'n/j/Y', strtotime(self::$end_date) );
				$out = "$start_formatted to $end_formatted";
			}

			return self::maybe_echo($echo, $out);
		}

		public static function filtered_pay($echo = true){
			if(strtolower(self::$job_type) == 'prn' || strtolower(self::$job_type) == 'government' ){
				$out = self::$placeholder;
			}
			elseif(strtolower(self::$job_type) == 'local' && self::$client_type && strtolower(self::$client_type) == 'travel-localapply'){
				$out = self::$placeholder;
			}
			elseif(self::$pay_package == "$0 - $0/week"){
				$out = self::$placeholder;
			}
			elseif(empty(self::$pay_package)){
				$out = self::$placeholder;
			}
			else {
				$out = self::$pay_package;
			}

			return self::maybe_echo($echo, $out);
		}

		public static function filtered_shift($echo = true){
			if(strtolower(self::$job_type) == 'school'){
				$out = self::$placeholder;
			}
			elseif(empty(self::$shift)){
				$out = self::$placeholder;
			}
			else{
				$out = self::$shift;
			}

			return self::maybe_echo($echo, $out);
		}

		public static function filtered_contract_length($echo = true){
			if(strtolower(self::$job_type) == 'prn' || strtolower(self::$job_type) == 'government' ){
				$out = self::$placeholder;
			}
			elseif(empty(self::$hours) || empty(self::$week_day) ){
				$out = self::$placeholder;	
			}
			elseif(self::$subject == '48'){
				$out = self::$week_day . ' at 48 hours/week';
			}
			elseif(self::$subject == '3648'){
				$out = self::$week_day . ' at 36/48 hours/week';
			}
			elseif(self::$subject == '36or48'){
				$out = self::$week_day . ' at 36 or 48 hours/week';
			}
			else{
				$out = self::$week_day . ' at ' . self::$hours . ' hours/week';
			}

			return self::maybe_echo($echo, $out);
		}

		public static function filtered_beds($echo = true){
			if(!empty(self::$num_beds)){
				$out = '<h2 class="text9">No. of Beds Available</h2>
						<i class="fa fa-bed" aria-hidden="true" style="color: #008091;font-size: 100%;margin-bottom: 10%;"></i> '.self::$num_beds.' Beds';
			}
			else{
				$out = '';
			}

			return self::maybe_echo($echo, $out);
		}


	}
?>