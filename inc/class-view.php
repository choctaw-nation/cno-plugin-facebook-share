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
	 * The App Secret
	 *
	 * @var string $secret
	 */
	private string $secret;

	/**
	 * The Redirect URL
	 *
	 * @var string $redirect
	 */
	private string $redirect;

	/**
	 * The Access Code
	 *
	 * @var string $access_token
	 */
	public string $access_token;

	/**
	 * The array of posts.
	 *
	 * @var array $posts
	 */
	public $posts;

	/**
	 * Init the class
	 *
	 * @param bool|array $options the options data from the Model layer
	 */
	public function __construct( bool|array $options ) {
		if ( is_array( $options ) ) {
			$this->secret       = isset( $options['app-secret'] ) ? $options['app-secret'] : '';
			$this->access_token = empty( $options['access-token'] ) ? '' : $options['access-token'];
		} else {
			$this->secret       = '';
			$this->access_token = '';
		}
	}


	/** Load the Admin Page HTML */
	public function admin_page() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( __( 'You do not have sufficient permissions to access this page.', 'cno' ) );
		}
		if ( isset( $_GET['settings-updated'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			add_settings_error( 'cno_ig_feed', 'cno_ig_feed_message', __( 'Settings Saved', 'cno' ), 'success' );
		}
		ob_start();
		require_once __DIR__ . '/admin-screen.php';
		ob_end_flush();
	}

	/** Displays below the section */
	public function section_callback() {
		if ( empty( $this->redirect ) ) {
			ob_start();
			require_once __DIR__ . '/admin-screen-header.php';
			ob_end_flush();

		}
	}

	/**
	 * Pulls the value (if any) from the database.
	 * Echoes the input field
	 */
	public function app_secret_callback() {
		echo "<input type='password' autocomplete='off' name='cno_ig_feed_options[app-secret]' id='ig-app-secret' value='{$this->secret}' /><input type='hidden' name='action' value='cno_ig_feed_save_options' />";
	}

	/**
	 * Gets the next scheduled run string
	 *
	 * @param int|false $timestamp the Unix timestamp of when the event is scheduled to run
	 * @param string    $event_name the name of the event
	 * @param bool      $with_class [Optional] whether to return `<p>` with class attributes or not.
	 */
	public function get_next_scheduled_run_string( int|false $timestamp, string $event_name, bool $with_class = true ): string {
		$notice_type = $timestamp ? 'info' : 'warning';
		$attributes  = $with_class ? "class='notice notice-{$notice_type}' style='padding:10px'" : '';
		if ( $timestamp ) {
			$date = new \DateTime( '@' . $timestamp, new \DateTimeZone( 'UTC' ) );
			$date->setTimezone( new \DateTimeZone( 'America/Chicago' ) );
			$date_string = $date->format( 'F j, Y g:i:s a' );
			return "<p {$attributes}>{$event_name}: {$date_string}</p>";
		} else {
			return "<p {$attributes}>{$event_name} not scheduled!</p>";
		}
	}
}