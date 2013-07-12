<?php
// Render the Plugin options form
function twiget_render_form() {
	?>
	<div class="wrap">
		<div class="icon32" id="icon-options-general"><br></div><!-- .icon32 -->
		<h2><?php _e( 'Twiget Twitter Plugin Settings', 'twiget' ); ?></h2>

		<form method="post" action="options.php">
			<?php settings_fields('twiget_plugin_options'); ?>
			<?php $options = get_option('twiget_options'); ?>

			<table class="form-table">

				<tr>
					<th scope="row"><?php _e( 'Twitter Username', 'twiget' ); ?></th>
					<td>
						<input type="text" size="50" name="twiget_options[user_name]" value="<?php if (isset($options['user_name'])) { echo $options['user_name']; } ?>" />
					</td>
				</tr>
				<tr>
					<th scope="row"><?php _e( 'Number of tweets to display', 'twiget' ); ?></th>
					<td>
						<input type="number" size="50" name="twiget_options[tweet_count]" value="<?php if (isset($options['tweet_count'])) { echo $options['tweet_count']; } ?>" />
					</td>
				</tr>
				<tr>
					<th scope="row"><?php _e( 'Consumer Key', 'twiget' ); ?></th>
					<td>
						<input type="text" size="50" name="twiget_options[consumer_key]" value="<?php if (isset($options['consumer_key'])) { echo $options['consumer_key']; } ?>" />
					</td>
				</tr>
				<tr>
					<th scope="row"><?php _e( 'Consumer Secret', 'twiget' ); ?></th>
					<td>
						<input type="text" size="50" name="twiget_options[consumer_secret]" value="<?php if (isset($options['consumer_secret'])) { echo $options['consumer_secret']; } ?>" />
					</td>
				</tr>
				<tr>
					<th scope="row"><?php _e( 'Access Token', 'twiget' ); ?></th>
					<td>
						<input type="text" size="50" name="twiget_options[access_token]" value="<?php if (isset($options['access_token'])) { echo $options['access_token']; } ?>" />
					</td>
				</tr>
				<tr>
					<th scope="row"><?php _e( 'Access Token Secret', 'twiget' ); ?></th>
					<td>
						<input type="text" size="50" name="twiget_options[access_token_secret]" value="<?php if (isset($options['access_token_secret'])) { echo $options['access_token_secret']; } ?>" />
					</td>
				</tr>
				
				<tr valign="top">
				<th scope="row"><?php _e( 'Display Options', 'twiget' ); ?></th>
					<td>
						<label><input name="twiget_options[link_target]" type="checkbox" value="1" <?php if (isset($options['link_target'])) { checked('1', $options['link_target']); } ?> /> <?php _e( 'Open links in new window', 'twiget' ); ?></label><br />

						<label><input name="twiget_options[followers_count]" type="checkbox" value="1" <?php if (isset($options['followers_count'])) { checked('1', $options['followers_count']); } ?> /> <?php _e( 'Show followers count', 'twiget' ); ?></label><br />

						<label><input name="twiget_options[profile_pic]" type="checkbox" value="1" <?php if (isset($options['profile_pic'])) { checked('1', $options['profile_pic']); } ?> /> <?php _e( 'Show profile picture', 'twiget' ); ?></label><br />

						<label><input name="twiget_options[show_username]" type="checkbox" value="1" <?php if (isset($options['show_username'])) { checked('1', $options['show_username']); } ?> /> <?php _e( 'Show twitter username with each tweet', 'twiget' ); ?></label><br />

						<label><input name="twiget_options[show_client]" type="checkbox" value="1" <?php if (isset($options['show_client'])) { checked('1', $options['show_client']); } ?> /> <?php _e( 'Show twitter client used. <em>(Example: via Twitter for Android)', 'twiget' ); ?></em> </label><br />
					</td>
				</tr>
				
				<tr><td colspan="2"><div style="margin-top:10px;"></div></td></tr>
				<tr valign="top" style="border-top:#dddddd 1px solid;">
					<th scope="row"><?php _e( 'Database Options', 'twiget' ); ?></th>
					<td>
						<label><input name="twiget_options[twiget_default_options_db]" type="checkbox" value="1" <?php if (isset($options['twiget_default_options_db'])) { checked('1', $options['twiget_default_options_db']); } ?> /> <?php _e( 'Restore defaults upon plugin deactivation/reactivation', 'twiget' ); ?></label>
						<br /><span style="color:#666666;margin-left:2px;"><?php _e( 'Only check this if you want to reset plugin settings upon Plugin reactivation', 'twiget' ); ?></span>
					</td>
				</tr>
			</table><!-- .form-table -->
			<p class="submit">
			<input type="submit" class="button-primary" value="<?php _e( 'Save Changes', 'twiget' ) ?>" />
			</p><!-- .submit -->
		</form>

		<p style="margin-top:15px;font-size:14px;"><?php printf( __( 'If you have found this plugin is useful, please consider making a %s. Thank you!', 'twiget' ), '<a href="http://www.prasannasp.net/donate/" target="_blank">' . __( 'donation', 'twiget' ) . '</a>' ); ?></p>
	</div><!-- .wrap -->

	<?php	
}
