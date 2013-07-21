<?php
/*
** Thanks David Gwyer for Plugin Options Starter Kit plugin!
*/
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

		<div id="twiget-instructions" style="padding:10px;border:1px dashed #000000;"><h2><?php _e( 'How to get Twitter API credentials', 'twiget' ); ?></h2>
		<?php printf( __( '<strong>Step 1:</strong> Go to %s page on twitter and login with your twitter username and password.', 'twiget' ), '<a href="https://dev.twitter.com/apps" target="_blank">' . __( 'My applications', 'twiget' ) . '</a>' ); ?>
		<br /><br />
		<?php printf( __( '<strong>Step 2:</strong> Click %s button.', 'twiget' ), '<a href="https://dev.twitter.com/apps/new" target="_blank">' . __( 'Create a new application', 'twiget' ) . '</a>' ); ?>
		<br /><br />
		<?php _e( '<strong>Step 3:</strong> Enter your application details', 'twiget' ); ?>
		<br /><br />
			<table class="instruction-table" border="1">
				<tr>
					<td style="padding:5px;"><?php _e( 'Name: *', 'twiget' ); ?></td>
					<td style="padding:5px;"><?php _e( 'Enter the name of your application. It may be the title of your website.', 'twiget' ); ?></td>
				</tr>
				
				<tr>
					<td style="padding:5px;"><?php _e( 'Description: *', 'twiget' ); ?></td>
					<td style="padding:5px;"><?php _e( 'Enter some description. Eg: Tweets in my website.', 'twiget' ); ?></td>
				</tr>
				
				<tr>
					<td style="padding:5px;"><?php _e( 'Website: *', 'twiget' ); ?></td>
					<td style="padding:5px;"><?php _e( 'Enter the URL of your website.', 'twiget' ); ?></td>
				</tr>
				
				<tr>
					<td style="padding:5px;"><?php _e( 'Callback URL: ', 'twiget' ); ?></td>
					<td style="padding:5px;"><?php _e( 'Leave this empty.', 'twiget' ); ?></td>
				</tr>
			</table>
		<br />
		<?php _e( 'Select <strong>Yes, I agree</strong> and complete the Captcha. Then click <strong>Create your Twitter application</strong> button.', 'twiget' ); ?>
		<br /><br />	
		<?php _e( '<strong>Step 4:</strong> In the next page, click <strong>Create my access token</strong> button. It may take a moment to display access token. So refresh page.', 'twiget' ); ?>
		<br /><br />
		<?php _e( '<strong>Step 5:</strong> Copy <em>Consumer key, Consumer secret, Access token, Access token secret</em> and enter them in the respective fields above.', 'twiget' ); ?>
		</div>
		<p style="margin-top:15px;font-size:14px;"><?php printf( __( 'If you have found this plugin is useful, please consider making a %s. Thank you!', 'twiget' ), '<a href="http://www.prasannasp.net/donate/" target="_blank">' . __( 'donation', 'twiget' ) . '</a>' ); ?></p>
	</div><!-- .wrap -->

	<?php	
}
