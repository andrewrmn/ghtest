<?php
	class GHC_CTM_LISTENER {
		static function listen(){
			$request = file_get_contents('php://input');
			$parsed = json_decode($request);
			
			# Make them all lowercase keys, so that there's no mismatch issues.
			$parsed = self::toLowercase($parsed);

			# Log the payload.
			self::logPayload($parsed, $request);

			# Traffic Cop time.
			if(!$parsed->eventname) return;

			# It's valid, so set the user.
			wp_set_current_user(11);

			if($parsed->eventname == 'Assignment.Create') return GHC_CTM_JOB::create($parsed);
			if($parsed->eventname == 'Assignment.Update') return GHC_CTM_JOB::update($parsed);
			if($parsed->eventname == 'Assignment.Void')   return GHC_CTM_JOB::void($parsed);
			if($parsed->eventname == 'Assignment.Fill')   return GHC_CTM_JOB::void($parsed);
			if($parsed->eventname == 'Assignment.Cancel') return GHC_CTM_JOB::void($parsed);

			var_dump("No Legal Action");
			die;
		}

		static function listen_with_id(){
			$request = file_get_contents('php://input');
			$parsed = json_decode($request);
	
			$log = fopen(plugin_dir_path(__DIR__)."event_logs/".@$parsed->assignmentid.'-'.@$parsed->eventname.'-'.date('Y-m-d H:i:s'),'w');
			fwrite($log, "SERVER global:\r\n");
			fwrite($log, var_export(getallheaders(), true));
			fwrite($log, "Payload:\r\n");
			fwrite($log, print_r(json_decode($request, true),true));
			fclose($log);
			echo json_encode("Listened.");
		}

		static function toLowercase($input){			
			$output = new stdClass();

			if(!is_array($input) && !is_object($input)){
				var_dump($input);
				debug_print_backtrace();
				die;
			}

			foreach($input as $key => $value){
				$key = strtolower($key);
				$output->{$key} = $value;
			}

			return $output;
		}

		static function logPayload($payload, $rawPayload){
			$log = fopen(plugin_dir_path(__DIR__)."event_logs/".@$payload->assignmentid.'-'.@$payload->eventname.'-'.date('Y-m-d H:i:s'),'w');
			fwrite($log, "SERVER global:\r\n");
			fwrite($log, var_export(getallheaders(), true));
			fwrite($log, "Payload:\r\n");
			fwrite($log, print_r(json_decode($rawPayload, true),true));
			fclose($log);
		}
	}
?>