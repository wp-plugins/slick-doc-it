<?php
/*
Plugin Name: Doc It
Plugin URI: http://slickremix.com/
Description: Create documentation in style for anything with this plugin!
Version: 1.0.3
Author: SlickRemix
Author URI: http://slickremix.com/
Requires at least: wordpress 3.5
Tested up to: wordpress 3.7.1
Stable tag: 1.0.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

 * @package    			Doc It
 * @category   			Core
 * @author     		    SlickRemix
 * @copyright  			Copyright (c) 2012-2013 SlickRemix

If you need support or want to tell us thanks please contact us at info@slickremix.com or use our support forum on slickremix.com

This is the main file for building the plugin into wordpress

*/
define( 'DOCIT_URL', plugins_url( '', __FILE__ ) );
define( 'DOCIT_DIR', plugin_dir_path( __FILE__ ) );


// Include core files and classes
include( 'includes/di-functions.php' );
include( 'includes/di-shortcode.php' );

// Include admin
include( 'admin/doc-it-system-info.php' );
include( 'admin/doc-it-settings-page.php' );

// Include Leave feedback, Get support and Plugin info links to plugin activation and update page.
add_filter("plugin_row_meta", "docit_add_leave_feedback_link", 10, 2);

	function docit_add_leave_feedback_link( $links, $file ) {
		if ( $file === plugin_basename( __FILE__ ) ) {
			$links['feedback'] = '<a href="http://wordpress.org/support/view/plugin-reviews/slick-doc-it" target="_blank">' . __( 'Leave feedback', 'gd_quicksetup' ) . '</a>';
			$links['support']  = '<a href="http://www.slickremix.com/support-forum/wordpress-plugins-group3/doc-it-forum10/" target="_blank">' . __( 'Get support', 'gd_quicksetup' ) . '</a>';
			$links['plugininfo']  = '<a href="plugin-install.php?tab=plugin-information&plugin=doc-it&section=changelog&TB_iframe=true&width=640&height=423" class="thickbox">' . __( 'Plugin info', 'gd_quicksetup' ) . '</a>';
		}
		return $links;
	}
// Include our own Settings link to plugin activation and update page.
add_filter("plugin_action_links_".plugin_basename(__FILE__), "docit_plugin_actions", 10, 4);

	function docit_plugin_actions( $actions, $plugin_file, $plugin_data, $context ) {
		array_unshift($actions, "<a href=\"".menu_page_url('doc-it-settings-page', false)."\">".__("Settings")."</a>");
		return $actions;
}
/**
 * Returns current plugin version. SRL added
 * 
 * @return string Plugin version
 */
function docitystem_version() {
	$plugin_data = get_plugin_data( __FILE__ );
	$plugin_version = $plugin_data['Version'];
	return $plugin_version;
}
?>