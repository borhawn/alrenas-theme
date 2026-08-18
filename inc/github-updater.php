<?php
/**
 * Native theme updates from GitHub Releases — no plugin required.
 *
 * To ship an update: bump `Version` in style.css, commit, then publish a
 * GitHub Release tagged `vX.Y.Z` (or `X.Y.Z`) matching that version. Sites
 * running this theme will see "Update available" in Appearance > Themes
 * within ALRENAS_GITHUB_UPDATE_CACHE and can update with the normal
 * one-click WP updater.
 *
 * @package Alrenas
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'ALRENAS_GITHUB_REPO', 'borhawn/alrenas-theme' );
define( 'ALRENAS_GITHUB_UPDATE_CACHE', 12 * HOUR_IN_SECONDS );

/**
 * Fetch the latest release from GitHub, cached in a transient.
 *
 * @return object|false Decoded release object, or false on failure.
 */
function alrenas_get_latest_github_release() {
	$cache_key = 'alrenas_github_latest_release';
	$cached    = get_transient( $cache_key );

	if ( false !== $cached ) {
		return $cached;
	}

	$response = wp_remote_get(
		'https://api.github.com/repos/' . ALRENAS_GITHUB_REPO . '/releases/latest',
		array(
			'headers' => array(
				'Accept'     => 'application/vnd.github+json',
				'User-Agent' => 'Alrenas-Theme-Updater',
			),
			'timeout' => 10,
		)
	);

	if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {
		// Cache the failure briefly too, so a broken/rate-limited check doesn't fire on every page load.
		set_transient( $cache_key, false, 15 * MINUTE_IN_SECONDS );
		return false;
	}

	$release = json_decode( wp_remote_retrieve_body( $response ) );

	if ( empty( $release->tag_name ) || empty( $release->zipball_url ) ) {
		set_transient( $cache_key, false, 15 * MINUTE_IN_SECONDS );
		return false;
	}

	set_transient( $cache_key, $release, ALRENAS_GITHUB_UPDATE_CACHE );

	return $release;
}

/**
 * Inject an update into the theme-update transient if GitHub has a newer version.
 *
 * @param object $transient Site transient value.
 * @return object
 */
function alrenas_check_for_theme_update( $transient ) {
	if ( empty( $transient->checked ) ) {
		return $transient;
	}

	$stylesheet = get_stylesheet();
	$release    = alrenas_get_latest_github_release();

	if ( ! $release ) {
		return $transient;
	}

	$remote_version  = ltrim( $release->tag_name, 'v' );
	$current_version = wp_get_theme( $stylesheet )->get( 'Version' );

	if ( ! version_compare( $remote_version, $current_version, '>' ) ) {
		return $transient;
	}

	$transient->response[ $stylesheet ] = array(
		'theme'       => $stylesheet,
		'new_version' => $remote_version,
		'url'         => $release->html_url,
		'package'     => $release->zipball_url,
	);

	return $transient;
}
add_filter( 'pre_set_site_transient_update_themes', 'alrenas_check_for_theme_update' );

/**
 * GitHub's release zip extracts as "<owner>-<repo>-<sha>/". Rename it to the
 * theme's actual folder name so WP installs over the existing theme instead
 * of creating a new, separately-named one.
 *
 * @param string      $source        Path to the extracted package.
 * @param string      $remote_source Path to the parent temp directory.
 * @param WP_Upgrader $upgrader      Upgrader instance.
 * @param array       $hook_extra    Extra arguments, includes 'theme' on theme updates.
 * @return string
 */
function alrenas_rename_github_source( $source, $remote_source, $upgrader, $hook_extra ) {
	global $wp_filesystem;

	if ( empty( $hook_extra['theme'] ) || get_stylesheet() !== $hook_extra['theme'] ) {
		return $source;
	}

	$desired = trailingslashit( $remote_source ) . get_stylesheet();

	if ( trailingslashit( $source ) === trailingslashit( $desired ) ) {
		return $source;
	}

	if ( $wp_filesystem->move( $source, $desired, true ) ) {
		return trailingslashit( $desired );
	}

	return $source;
}
add_filter( 'upgrader_source_selection', 'alrenas_rename_github_source', 10, 4 );

/**
 * Clear the cached release info once an update finishes, so the next check
 * doesn't report the version we just installed as "available".
 *
 * @param WP_Upgrader $upgrader   Upgrader instance.
 * @param array       $hook_extra Extra arguments.
 */
function alrenas_clear_update_cache( $upgrader, $hook_extra ) {
	if ( ! empty( $hook_extra['theme'] ) && get_stylesheet() === $hook_extra['theme'] ) {
		delete_transient( 'alrenas_github_latest_release' );
	}
}
add_action( 'upgrader_process_complete', 'alrenas_clear_update_cache', 10, 2 );
