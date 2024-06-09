<?php
ini_set('display_errors',false);

	require_once( __DIR__."/../../../../wp-load.php");
	echo "Status: ".get_option('ghc_ctm_job_sync_status');
	GHC_CTM_SYNC::check_cron();
	die();
?>