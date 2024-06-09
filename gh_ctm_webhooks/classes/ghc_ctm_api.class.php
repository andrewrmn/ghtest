<?php
	class GHC_CTM_API {

		static function getLTOrders($extra_conditions){
			$payload = array(
				'action' 		=> 'getLTOrders',
				'resultType' 	=> 'json',
			);

			$payload = array_merge($payload, $extra_conditions);

			$json = self::getJSON($payload);
			
			if( !is_object($json) ) return false;
			return (array)$json;
		}

		static function getMetaForJob($job_id){
			$payload = array(
				'action' 		=> 'getLTOrders',
				'resultType' 	=> 'json',
				'ltOrderId' 	=> $job_id
			);

			$json = self::getJSON($payload);
			
			if( !is_object($json) ) return false;
			$json = (array)$json;

			if( !isset($json[0]) ) return false;
			$json = GHC_CTM_LISTENER::toLowercase($json[0]);

			return !empty($json->lt_orderid) ? $json : false;
		}

		static function getMetaForFacility($facility_id){
			$payload = array(
				'action' 		=> 'getClients',
				'resultType' 	=> 'json',
				'clientIdIn' 	=> $facility_id
			);

			$json = self::getJSON($payload);
			
			if( !is_object($json) ) return false;
			$json = (array)$json;

			if( !isset($json[0]) ) return false;
			$json = GHC_CTM_LISTENER::toLowercase($json[0]);

			return !empty($json->clientid) ? $json : false;
		}

		static function getPayForJob($job_id, $jobType){
			$payload = array(
				'action' 		=> 'getPayPackageForAssignment',
				'resultType' 	=> 'json',
				'assignmentId' 	=> $job_id
			);		

			switch(strtolower($jobType)){
				case 'local':
				case 'prn':
					$payload['drivingMiles'] = 1;
					break;

				case 'travel':
				case 'interim':
					$payload['drivingMiles'] = 200;
					break;
			}

			$json = self::getJSON($payload);
			
			if( !is_object($json) ) return false;
			$json = (array)$json;


			if( !isset($json[0]) ) return false;
			$json = GHC_CTM_LISTENER::toLowercase($json[0]);

			return !empty($json->totalweeklypaypackage) ? $json : false;
		}

		private static function getJSON($payload){
			$domain = 'https://' . get_option('ctm_api_domain') . '/';
			$instance = get_option('ctm_api_instance');
			$endpoint = '/clearConnect/2_0/index.cfm';
			$token = 'Basic '.get_option('ctm_api_token');

			$ch = curl_init($domain.$instance.$endpoint.'?'.http_build_query($payload));
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: '. $token));
            curl_setopt ($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch,CURLOPT_USERAGENT,'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:49.0) Gecko/20100101 Firefox/49.0');
            $result = curl_exec ($ch);
            curl_close ($ch);

            return GHC_CTM_LISTENER::toLowercase(json_decode(preg_replace('/[[:cntrl:]]/', '', $result)));			
		}
	}
?>