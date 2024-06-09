<?php
	/**
	 * Plugin Name: Gifted CTM Webhook Listener
	 * Description: Accepts job posting data from CTM
	 * Author:      Mind Development & Design for Gifted Healthcare
	 * Version:     1.0.0
	 */
//	if (!defined('ABSPATH')) {
//	    exit;
//	}	

	include_once(plugin_dir_path(__FILE__)."classes/ghc_ctm_api.class.php");
	include_once(plugin_dir_path(__FILE__)."classes/ghc_ctm_cert_spec.class.php");
	include_once(plugin_dir_path(__FILE__)."classes/ghc_ctm_job_post_admin.class.php");
	include_once(plugin_dir_path(__FILE__)."classes/ghc_ctm_listener.class.php");
	include_once(plugin_dir_path(__FILE__)."classes/ghc_ctm_job.class.php");
	include_once(plugin_dir_path(__FILE__)."classes/ghc_ctm_job_display.class.php");
	include_once(plugin_dir_path(__FILE__)."classes/ghc_ctm_config.class.php");
	include_once(plugin_dir_path(__FILE__)."classes/ghc_ctm_sync.class.php");
	include_once(plugin_dir_path(__FILE__)."classes/ghc_ctm_feed.class.php");

	# Classes for individual feeds.
	include_once(plugin_dir_path(__FILE__)."classes/feed_classes/ghc_ctm_feed_indeed.class.php");
	include_once(plugin_dir_path(__FILE__)."classes/feed_classes/ghc_ctm_feed_tns.class.php");
	include_once(plugin_dir_path(__FILE__)."classes/feed_classes/ghc_ctm_feed_acs.class.php");
	include_once(plugin_dir_path(__FILE__)."classes/feed_classes/ghc_ctm_feed_gypsy.class.php");
	include_once(plugin_dir_path(__FILE__)."classes/feed_classes/ghc_ctm_feed_wanderly.class.php");
	include_once(plugin_dir_path(__FILE__)."classes/feed_classes/ghc_ctm_feed_appcast.class.php");

	function ghc_ctm_webhooks_routes() {
	    register_rest_route(
	      'ghc_ctm/v1', '/job_posting/', array(
	        'methods' => WP_REST_Server::EDITABLE,
	        'callback'=> array('GHC_CTM_LISTENER', 'listen'),
	        'permission_callback' => function(){return ($_SERVER['REMOTE_ADDR'] == '216.58.240.204');}
	        )
	    );

	    # Outgoing Feeds
	    # TNS
	    register_rest_route(
	      'ghc_ctm/v1', '/job_feed/tns/', array(
	        'methods' => WP_REST_Server::READABLE,
	        'callback'=> array('GHC_CTM_FEED_TNS', 'build'),
	        'permission_callback' => function(){return true;}
	        )
	    );

	    # ACS
	    register_rest_route(
	      'ghc_ctm/v1', '/job_feed/acs/', array(
	        'methods' => WP_REST_Server::READABLE,
	        'callback'=> array('GHC_CTM_FEED_ACS', 'build'),
	        'permission_callback' => function(){return true;}
	        )
	    );

	    # Indeed
	    register_rest_route(
	      'ghc_ctm/v1', '/job_feed/indeed/', array(
	        'methods' => WP_REST_Server::READABLE,
	        'callback'=> array('GHC_CTM_FEED_INDEED', 'build'),
	        'permission_callback' => function(){return true;}
	        )
	    );

	    # Gypsy
	    register_rest_route(
	      'ghc_ctm/v1', '/job_feed/gypsy/', array(
	        'methods' => WP_REST_Server::READABLE,
	        'callback'=> array('GHC_CTM_FEED_GYPSY', 'build'),
	        'permission_callback' => function(){return true;}
	        )
	    );

	    # Wanderly
	    register_rest_route(
	      'ghc_ctm/v1', '/job_feed/wanderly/', array(
	        'methods' => WP_REST_Server::READABLE,
	        'callback'=> array('GHC_CTM_FEED_WANDERLY', 'build'),
	        'permission_callback' => function(){return true;}
	        )
	    );

	    # Appcast
	    register_rest_route(
	      'ghc_ctm/v1', '/job_feed/appcast/', array(
	        'methods' => WP_REST_Server::READABLE,
	        'callback'=> array('GHC_CTM_FEED_APPCAST', 'build'),
	        'permission_callback' => function(){return true;}
	        )
	    );
	}
	add_action('rest_api_init', 'ghc_ctm_webhooks_routes', 99);

	add_action( 'init', [ 'GHC_CTM_CERT_SPEC', 'init' ], 0 );
	add_action( 'init', [ 'GHC_CTM_JOB_POST_ADMIN', 'init' ], 0 );
	add_action( 'init', [ 'GHC_CTM_SYNC', 'init' ], 0 );
	add_action( 'admin_menu', ['GHC_CTM_CONFIG', 'init'], 0);
?>
