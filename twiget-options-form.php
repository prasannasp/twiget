<?php
// Render the Plugin options form
function twiget_render_form() {
	?>
	<div class="wrap">
		<div class="icon32" id="icon-options-general"><br></div><!-- .icon32 -->
		<h2>Twiget Twitter Plugin Settings</h2>

		<form method="post" action="options.php">
			<?php settings_fields('twiget_plugin_options'); ?>
			<?php $options = get_option('twiget_options'); ?>

			<table class="form-table">

				<tr>
					<th scope="row">Twitter Username</th>
					<td>
						<input type="text" size="50" name="twiget_options[user_name]" value="<?php if (isset($options['user_name'])) { echo $options['user_name']; } ?>" />
					</td>
				</tr>
				<tr>
					<th scope="row">Number of tweets to display</th>
					<td>
						<input type="number" size="50" name="twiget_options[tweet_count]" value="<?php if (isset($options['tweet_count'])) { echo $options['tweet_count']; } ?>" />
					</td>
				</tr>
				<tr>
					<th scope="row">Consumer Key</th>
					<td>
						<input type="text" size="50" name="twiget_options[consumer_key]" value="<?php if (isset($options['consumer_key'])) { echo $options['consumer_key']; } ?>" />
					</td>
				</tr>
				<tr>
					<th scope="row">Consumer Secret</th>
					<td>
						<input type="text" size="50" name="twiget_options[consumer_secret]" value="<?php if (isset($options['consumer_secret'])) { echo $options['consumer_secret']; } ?>" />
					</td>
				</tr>
				<tr>
					<th scope="row">Access Token</th>
					<td>
						<input type="text" size="50" name="twiget_options[access_token]" value="<?php if (isset($options['access_token'])) { echo $options['access_token']; } ?>" />
					</td>
				</tr>
				<tr>
					<th scope="row">Access Token Secret</th>
					<td>
						<input type="text" size="50" name="twiget_options[access_token_secret]" value="<?php if (isset($options['access_token_secret'])) { echo $options['access_token_secret']; } ?>" />
					</td>
				</tr>
				
				<tr valign="top">
				<th scope="row">Display Options</th>
					<td>
						<label><input name="twiget_options[link_target]" type="checkbox" value="1" <?php if (isset($options['link_target'])) { checked('1', $options['link_target']); } ?> /> Open links in new window</label><br />

						<label><input name="twiget_options[followers_count]" type="checkbox" value="1" <?php if (isset($options['followers_count'])) { checked('1', $options['followers_count']); } ?> /> Show followers count</label><br />

						<label><input name="twiget_options[profile_pic]" type="checkbox" value="1" <?php if (isset($options['profile_pic'])) { checked('1', $options['profile_pic']); } ?> /> Show profile picture</label><br />

						<label><input name="twiget_options[show_username]" type="checkbox" value="1" <?php if (isset($options['show_username'])) { checked('1', $options['show_username']); } ?> /> Show twitter username with each tweet </label><br />

						<label><input name="twiget_options[show_client]" type="checkbox" value="1" <?php if (isset($options['show_client'])) { checked('1', $options['show_client']); } ?> /> Show twitter client used. <em>(Example: via Twitter for Android)</em> </label><br />
						
					</td>
				</tr>
				
				<tr><td colspan="2"><div style="margin-top:10px;"></div></td></tr>
				<tr valign="top" style="border-top:#dddddd 1px solid;">
					<th scope="row">Database Options</th>
					<td>
						<label><input name="twiget_options[twiget_default_options_db]" type="checkbox" value="1" <?php if (isset($options['twiget_default_options_db'])) { checked('1', $options['twiget_default_options_db']); } ?> /> Restore defaults upon plugin deactivation/reactivation</label>
						<br /><span style="color:#666666;margin-left:2px;">Only check this if you want to reset plugin settings upon Plugin reactivation</span>
					</td>
				</tr>
			</table><!-- .form-table -->
			<p class="submit">
			<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
			</p><!-- .submit -->
		</form>

		<p style="margin-top:15px;font-size:14px;">If you have found this plugin is useful, please consider making a <a href="http://www.prasannasp.net/donate/" target="_blank">donation</a>. Thank you!</p>
	</div><!-- .wrap -->

	<?php	
}
