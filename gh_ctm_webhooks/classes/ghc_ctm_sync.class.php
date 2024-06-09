<?php
	/*
	*	Handles "Nuke the job board, re-seed with data from the listed CTM instance.
	*/

	class GHC_CTM_SYNC {
		static function init(){
			//add_submenu_page('options-general.php', 'CTM Jobs Sync', 'CTM Jobs Sync', 'install_plugins', 'ghc_ctm_job_sync', [__CLASS__, 'show_page'] );

			# Makes sure the sync flag is there.
			if(get_option('ghc_ctm_job_sync_status') === false){
				update_option('ghc_ctm_job_sync_status', 'inactive');	
			}
		}

		static function show_page(){
			if(isset($_POST['begin_sync'])) self::start_sync();

			include(plugin_dir_path(__DIR__)."templates/ctm_api_config.php");
		}

		static function start_sync(){
			$sync_status = get_option('ghc_ctm_job_sync_status', 'inactive');

			if($sync_status == 'inactive'){
				update_option('ghc_ctm_job_sync_status','pending');
			}

			echo "<div class='updated'>CTM Job Sync has been initiated.  Sync status can be checked at any time by visiting this page.</div>";
		}

		static function check_cron(){
			$sync_status = get_option('ghc_ctm_job_sync_status', 'inactive');

			# If it's already running, no duplicates.
			if($sync_status != 'pending') return;

			update_option('ghc_ctm_job_sync_status', 'running');
			self::do_cron();
			update_option('ghc_ctm_job_sync_status', 'inactive');


		}

		static function do_cron(){
			global $wpdb;

			echo "Nuking Table syncdata\r\n";
			# The Process:
			#  1) Nuke related tables.
			$query = "drop table if exists {$wpdb->prefix}ghc_ctm_syncdata";
			$wpdb->query($query);


			echo "Creating Table syncdata\r\n";
			#  2) Recreate related tables.
			$query = "create table if not exists {$wpdb->prefix}ghc_ctm_syncdata (ctm_id varchar(64) not null, ctm_data longtext, primary key(ctm_id))";
			$wpdb->query($query);

			echo "Seeding Table syncdata\r\n";
			#  3) Add all local jobs to tables: ID, and 'needs to pull' flag only.
			$query = "insert into {$wpdb->prefix}ghc_ctm_syncdata (ctm_id) select meta_value from {$wpdb->prefix}postmeta where meta_key='job_id' and meta_value REGEXP '^[0-9]+$' on duplicate key update ctm_id=ctm_id";
			$wpdb->query($query);

			#  4) Pull remote data for local jobs.  Batches of 100 [based on CTM playing nice]
			#  5) Store the payloads, clear 'needs to pull' flag.
			# Does a pull outsode the Do since having some isn't guaranteed, but a do-while is still the best structure.

			$batchIDs = self::getIDsForBatch();
			//var_dump($batchIDs);
			//die;

			while(is_countable($batchIDs) && !empty($batchIDs)){
				foreach($batchIDs as $id){

					$payload = GHC_CTM_API::getLTOrders( array('ltOrderId' => $id) );
					$payload = GHC_CTM_LISTENER::toLowercase($payload[0]);

					if(isset($payload->lt_orderid)){
						$jobID = $payload->lt_orderid;

						$query = $wpdb->prepare("update {$wpdb->prefix}ghc_ctm_syncdata set ctm_data=%s where ctm_id=%s", array(serialize($payload), $id));
						$wpdb->query($query);					
					}
					else{
						$query = $wpdb->prepare("update {$wpdb->prefix}ghc_ctm_syncdata set ctm_data='ERROR' where ctm_id=%s", array($id));
						$wpdb->query($query);					
					}
					time_nanosleep(0, 100000000);
				}
				

				# Delay 250ms, for API-friendly purposes.
				$batchIDs = self::getIDsForBatch();
			}

			#  6) When no more 'need to pull' flags, move to remote.
			#  7) Pull only open jobs from remote in batches of 3000.  If it already exists from local pulls, skip.
			$jobData = GHC_CTM_API::getLTOrders( array('status' => 'open', 'offset' => 0));
			$offset = 1;

			while(is_countable($jobData) && !empty($jobData)){
				$values = array();
				foreach($jobData as $payload){
					$payload = GHC_CTM_LISTENER::toLowercase($payload);
					
					$query = $wpdb->prepare("insert into {$wpdb->prefix}ghc_ctm_syncdata (ctm_id, ctm_data) values (%s,%s) on duplicate key update ctm_id=ctm_id", array($payload->lt_orderid, serialize($payload)));
					$wpdb->query($query);
				}

				$jobData = GHC_CTM_API::getLTOrders( array('status' => 'open', 'offset' => $offset * 3000));
				$offset++;

				# Delay 250ms, for API-friendly purposes.
				time_nanosleep(0, 100000000);
			}

			#  8) When no more remote to pull, move to ingest.
			#  9) Loop through data in table.  Delay between to avoid spamming APIs?  [Say, 500ms?]
			$query = "select * from {$wpdb->prefix}ghc_ctm_syncdata";

			$toProcess = $wpdb->query($query);
			
			if($toProcess > 0) wp_set_current_user(11);

			for($i = 0; $i < $toProcess; $i++){
				$job = $wpdb->get_row($query, 'OBJECT', $i);

				if($job->ctm_data == 'ERROR' || empty($job->ctm_data)) continue;

				$job = unserialize($job->ctm_data);
				$job = GHC_CTM_LISTENER::toLowercase($job);

			# 10) Based on 'Status', format as per a Webhook.
				$job = self::convertToWebhookPayload($job);

			# 11) Call functions as if a webhook had sent it.
				if($job->status == 'open') GHC_CTM_JOB::create($job);
				else GHC_CTM_JOB::void($job);
				time_nanosleep(0, 250000000);
			}			

			# Facet isn't automatically picking up on the taxonomy stuff.  So make it.
			if ( function_exists( 'FWP' ) ) {
  				FWP()->indexer->index();
  			}
  		}
	
		static function convertToWebhookPayload($job){
			// Currently need:
			// Assignment ID
			// Status

			$out = array(
			    'assignmentid' => $job->lt_orderid,
			    'status' => $job->status,
			    'cert' => $job->ordercertification,
			    'spec' => $job->orderspecialty,
			    'facilityid' => $job->clientid,
			    'shiftdatestart' => $job->datestart,
			    'shiftdateend' => $job->dateend,	
			    'facilityname' => $job->clientname,
			);

			return (object)$out;

		}

		static function getIDsForBatch(){
			global $wpdb;
			$query = "select * from {$wpdb->prefix}ghc_ctm_syncdata where ctm_data is null limit 100";
			$results = $wpdb->get_results($query);

			if(is_countable($results) && !empty($results)){
				$out = array();

				foreach($results as $result){
					$out[] = $result->ctm_id;
				}

				return $out;
			}
			else return false;
		}
	}
?>