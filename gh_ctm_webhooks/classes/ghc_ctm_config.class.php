<?php
	class GHC_CTM_CONFIG {
		static function init(){
			add_submenu_page('options-general.php', 'CTM API Settings', 'CTM API', 'install_plugins', 'ghc_ctm_api_config', [__CLASS__, 'show_page'] );
		}

		static function show_page(){
			if(isset($_POST['config_save'])) self::save();

			include(plugin_dir_path(__DIR__)."templates/ctm_api_config.php");
		}

		static function save(){
			check_admin_referer( 'ghc_ctm_api_config_save' );
			
			update_option('ctm_api_domain', $_POST['domain']);
			update_option('ctm_api_instance', $_POST['instance']);
			update_option('ctm_api_token', addslashes(base64_encode($_POST['un'].':'.$_POST['pw'])));

			echo "<div class='updated'>CTM API Settings have been saved.</div>";
			return;
		}
	}
?>