<?php
	$domain = get_option('ctm_api_domain', '');
	$instance = get_option('ctm_api_instance', '');
	$token = get_option('ctm_api_token');

	$token = base64_decode($token);
	if(strpos($token, ':') !== false){
		$token = explode(':',$token);

		$un = $token[0];
		$pw = $token[1];
	}
	else{
		$un = '';
		$pw = '';
	}
?>
<div class="wrap">
	<h1>CTM API Settings</h1>
	<form method="POST" action="options-general.php?page=ghc_ctm_api_config">
		<input type="hidden" name="config_save" value="1">
		<?php wp_nonce_field( 'ghc_ctm_api_config_save' ); ?>

		<table class="form-table" role="presentation">
			<tbody>
				<tr>
					<th scope="row"><label for="domain">CTM Instance Domain</label></th>
					<td>
						https://<input type="text" name="domain" id="domain" class="postform" value="<?php echo $domain; ?>" />/[instance]/clearConnect/2_0/index.cfm
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="domain">CTM Instance Name</label></th>
					<td>
						https://[domain].com/<input type="text" name="instance" id="instance" class="postform" value="<?php echo $instance; ?>" />/clearConnect/2_0/index.cfm
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="un">Username</label></th>
					<td>
						<input type="text" name="un" id="un" class="postform" value="<?php echo $un; ?>" />
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="pw">Password</label></th>
					<td>
						<input type="password" name="pw" id="pw" class="postform" value="<?php echo $pw; ?>" />
					</td>
				</tr>
			</tbody>
		</table>
		<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes"></p>	
	</form>
</div>