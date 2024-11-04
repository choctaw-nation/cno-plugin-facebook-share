<?php
/**
 * View Controller
 * The View Layer Controller. Contains all the callback functions for the admin screen
 *
 * @package ChoctawNation
 * @subpackage FacebookShare
 * @since 1.0
 */

namespace ChoctawNation\FacebookShare;

/**
 * The View Layer Controller. Contains all the callback functions for the admin screen
 */
class View_Controller {

	/**
	 * The App ID
	 *
	 * @var int $app_id
	 */
	private int $app_id;


	/**
	 * Init the class
	 */
	public function __construct() {
	}


	/** Load the Admin Page HTML */
	public function admin_page() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( __( 'You do not have sufficient permissions to access this page.', 'cno' ) );
		}
		if ( isset( $_GET['settings-updated'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			add_settings_error( 'cno_facebook_share', 'cno_facebook_share_message', __( 'Settings Saved', 'cno' ), 'success' );
		}
		ob_start();
		require_once __DIR__ . '/admin-screen.php';
		ob_end_flush();
	}

	/** Displays below the section */
	public function section_callback() {
			ob_start();
			require_once __DIR__ . '/admin-screen-header.php';
			ob_end_flush();
	}

	/**
	 * The App ID Callback
	 *
	 * @param false|string $app_id The App ID
	 * @return void
	 */
	public function app_id_callback( false|string $app_id ): void {
		echo '<input type="text" autocomplete="off" name="cno_facebook_share_options[app-id]" id="fb-share-app-id" ' . ( $app_id ? 'value="' . absint( $app_id ) . '"' : '' ) . ' /><input type="hidden" name="action" value="cno_facebook_share_save_options" />';
	}
}