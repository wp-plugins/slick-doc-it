<?php
function doc_it_system_info_page(){
?>
		<div class="docit-help-admin-wrap">
		<h2>
		<?php _e( 'System Info', 'slick-doc-it' ); ?>
		</h2>
		<p>
		<?php _e( 'Please click the box below and copy the report. You will need to paste this information along with your question in our', 'slick-doc-it' ); ?>
		<a href="http://www.slickremix.com/support-forum/" target="_blank">
		<?php _e( 'Support Forum', 'slick-doc-it' ); ?>
		</a>.
		<?php _e( 'Ask your question then paste the copied text below it.  To copy the system info, click below then press Ctrl + C (PC) or Cmd + C (Mac).', 'slick-doc-it' ); ?>
		</p>
		<form action="<?php echo esc_url( admin_url( 'admin.php?page=docit-system-info-submenu-page' ) ); ?>" method="post" dir="ltr" >
		<textarea readonly="readonly" onclick="this.focus();this.select()" id="system-info-textarea" name="docit-sysinfo" title="<?php _e( 'To copy the system info, click here then press Ctrl + C (PC) or Cmd + C (Mac).', 'slick-doc-it' ); ?>">
### Begin System Info ###
		<?php
			$theme_data = wp_get_theme();
			$theme      = $theme_data->Name . ' ' . $theme_data->Version; ?>

SITE_URL:                 <?php echo site_url() . "\n"; ?>
Slick Doc It Version:     <?php echo docitsystem_version(). "\n"; ?>
	
-- Wordpress Configuration
	
WordPress Version:        <?php echo get_bloginfo( 'version' ) . "\n"; ?>
Multisite:                <?php echo is_multisite() ? 'Yes' . "\n" : 'No' . "\n" ?>
Permalink Structure:      <?php echo get_option( 'permalink_structure' ) . "\n"; ?>
Active Theme:             <?php echo $theme . "\n"; ?>
PHP Memory Limit:         <?php echo ini_get( 'memory_limit' ) . "\n"; ?>
WP_DEBUG:                 <?php echo defined( 'WP_DEBUG' ) ? WP_DEBUG ? 'Enabled' . "\n" : 'Disabled' . "\n" : 'Not set' . "\n" ?>
	
-- Webserver Configuration
	
PHP Version:              <?php echo PHP_VERSION . "\n"; ?>
Web Server Info:          <?php echo $_SERVER['SERVER_SOFTWARE'] . "\n"; ?>
	
-- PHP Configuration:
	
Safe Mode:                <?php echo ini_get( 'safe_mode' ) ? "Yes" : "No\n"; ?>
Upload Max Size:          <?php echo ini_get( 'upload_max_filesize' ) . "\n"; ?>
Post Max Size:            <?php echo ini_get( 'post_max_size' ) . "\n"; ?>
Upload Max Filesize:      <?php echo ini_get( 'upload_max_filesize' ) . "\n"; ?>
Time Limit:               <?php echo ini_get( 'max_execution_time' ) . "\n"; ?>
Max Input Vars:           <?php echo ini_get( 'max_input_vars' ) . "\n"; ?>
Display Erros:            <?php echo ( ini_get( 'display_errors' ) ) ? 'On (' . ini_get( 'display_errors' ) . ')' : 'N/A'; ?><?php echo "\n"; ?>

-- Active Plugins:

<?php $plugins = get_plugins();
$active_plugins = get_option( 'active_plugins', array() );
foreach ( $plugins as $plugin_path => $plugin ) {
// If the plugin isn't active, don't show it.
if ( ! in_array( $plugin_path, $active_plugins ) )
continue;
echo $plugin['Name'] . ': ' . $plugin['Version'] ."\n";
			}
if ( is_multisite() ) :
?>
	
-- Network Active Plugins:
	
		<?php
				$plugins = wp_get_active_network_plugins();
			$active_plugins = get_site_option( 'active_sitewide_plugins', array() );
	
			foreach ( $plugins as $plugin_path ) {
				$plugin_base = plugin_basename( $plugin_path );
	
				// If the plugin isn't active, don't show it.
				if ( ! array_key_exists( $plugin_base, $active_plugins ) )
					continue;
	
				$plugin = get_plugin_data( $plugin_path );
	
				echo $plugin['Name'] . ' :' . $plugin['Version'] ."\n";
			}
	
			endif;
	
	 if (is_plugin_active('doc-it-premium/doc-it-premium.php')) {
$feed_them_social_premium_license_key = get_option('doc_it_premium_license_key');  ?>
	
-- Premium License
	
Premium Active:           <?php echo isset($feed_them_social_premium_license_key) && $feed_them_social_premium_license_key !== '' ? 'Yes'. "\n" : 'No'. "\n"; } if (is_plugin_active('doc-it-premium/doc-it-premium.php')) { ?>
	<?php		} ?>

### End System Info ###</textarea>
		</form>
		<a class="docit-settings-admin-slick-logo" href="http://www.slickremix.com/support-forum/" target="_blank"></a> </div>
		<?php
}
?>