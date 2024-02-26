<?php
/**
 * Must use plugins.
 *
 * @package LearnDash
 */

/**
 * Copy our Multisite support file(s) to the /wp-content/mu-plugins directory.
 */
if ( is_multisite() ) {
	$wpmu_plugin_dir = ( defined( 'WPMU_PLUGIN_DIR' ) && defined( 'WPMU_PLUGIN_URL' ) ) ? WPMU_PLUGIN_DIR : trailingslashit( WP_CONTENT_DIR ) . 'mu-plugins'; // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
	if ( is_writable( $wpmu_plugin_dir ) ) {
		$dest_file = trailingslashit( $wpmu_plugin_dir ) . 'learndash-multisite.php'; // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
		if ( ! file_exists( $dest_file ) ) {
			$source_file = trailingslashit( LEARNDASH_LMS_PLUGIN_DIR ) . 'mu-plugins/learndash-multisite.php'; // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
			if ( file_exists( $source_file ) ) {
				copy( $source_file, $dest_file );
			}
		}
	}
}

/**
 * Install the License Manager.
 */
if ( file_exists( trailingslashit( LEARNDASH_LMS_PLUGIN_DIR ) . 'mu-plugins/learndash-hub.zip' ) ) {
	$learndash_hub_unzip_dir = trailingslashit( LEARNDASH_LMS_PLUGIN_DIR ) . 'mu-plugins/_tmp';

	if ( file_exists( $learndash_hub_unzip_dir ) ) {
		learndash_recursive_rmdir( $learndash_hub_unzip_dir );
	}

	WP_Filesystem();
	$learndash_unzip_ret = unzip_file( trailingslashit( LEARNDASH_LMS_PLUGIN_DIR ) . 'mu-plugins/learndash-hub.zip', $learndash_hub_unzip_dir );

	if ( is_wp_error( $learndash_unzip_ret ) ) {
		WP_DEBUG && error_log( 'Failed to unzip the learndash licensing and management plugin: ' . $learndash_unzip_ret->get_error_message() ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log
	}

	$learndash_hub_install_file = trailingslashit( $learndash_hub_unzip_dir ) . 'learndash-hub/install.php';

	if ( file_exists( $learndash_hub_install_file ) ) {
		include $learndash_hub_install_file;

		learndash_activate_learndash_hub();
	}

	learndash_recursive_rmdir( $learndash_hub_unzip_dir );
}
