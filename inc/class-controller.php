<?php
/**
 * Controller
 * Handles the WordPress Dashboard side of things for the plugin
 *
 * @since 1.0
 * @package ChoctawNation
 * @subpackage FacebookShare
 */

namespace ChoctawNation\FacebookShare;

/**
 * Handles the WordPress Dashboard side of things for the plugin
 */
class Controller {

	/**
	 * The View Layer Controller.
	 *
	 * @var View_Controller $view
	 */
	public View_Controller $view;

	/**
	 * The Model Layer. Exposes the API class
	 *
	 * @var API $model;
	 */
	public API $model;

	/**
	 * If set, when the current token expires
	 *
	 * @var ?string $token_expiry
	 */
	public ?string $token_expiry;

	/** Constructor */
	public function __construct() {
		$this->wire_actions();
		$this->model        = new API();
		$this->view         = new View_Controller( $this->model->get_the_options() );
		$this->token_expiry = $this->model->expiration_date;

		if ( 'POST' === $_SERVER['REQUEST_METHOD'] ) {
			$can_get_posts = isset( $_POST['can_get_posts'] ) ? intval( $_POST['can_get_posts'] ) : null; // phpcs:ignore
			if ( $can_get_posts ) {
				$this->view->posts = $this->model->get_new_posts();
			}
		}
	}

	/** Wires the actions to the appropriate hooks */
	private function wire_actions() {
		$actions = array(
			'admin_menu'                          => array( $this, 'my_admin_menu' ),
			'admin_enqueue_scripts'               => array( $this, 'admin_assets' ),
			'admin_init'                          => array( $this, 'register_fields' ),
			'admin_post_cno_ig_feed_save_options' => array( $this, 'save_options' ),
		);

		foreach ( $actions as $hook => $callback ) {
			add_action( $hook, $callback );
		}
	}

	/** Register the Admin Menu */
	public function my_admin_menu() {
		add_menu_page(
			'CNO Instagram Feed',
			'CNO IG Feed',
			'manage_options',
			'cno-ig-feed',
			array( $this->view, 'admin_page' ),
			'dashicons-instagram',
			75
		);
	}

	/** Register Admin Page CSS */
	public function admin_assets() {
		$asset_file = require_once dirname( __DIR__, 2 ) . '/build/editor.asset.php';
		wp_register_style( 'cno-ig-feed', plugin_dir_url( dirname( __DIR__, 1 ) ) . '/build/editor.css', $asset_file['dependencies'], $asset_file['version'] );
	}

	/** Define Admin Setting Group & Fields */
	public function register_fields() {
		$this->register_field_1();
	}

	/** Registers the First Fields (App Secret & ID) */
	private function register_field_1() {
		register_setting(
			'cno_ig_feed_options_group',
			'cno_ig_feed_options',
		);

		add_settings_section(
			'cno-ig-feed-settings',
			'Configure the plugin',
			array( $this->view, 'section_callback' ),
			'cno-ig-feed'
		);

		add_settings_field(
			'ig-app-secret',
			'Instagram App Access Token',
			array( $this->view, 'app_secret_callback' ),
			'cno-ig-feed',
			'cno-ig-feed-settings',
			array( 'label_for' => 'ig-app-secret' )
		);
	}

	/**
	 * Retrieves the options from the model.
	 *
	 * @return array The options retrieved from the model.
	 */
	public function get_the_options() {
		return $this->model->get_the_options();
	}

	/**
	 * Deletes the options from the database.
	 */
	public function delete_options() {
		$options = array(
			'cno_ig_feed_options',
			'cno_ig_last_fetch',
			'cno_ig_posts',
		);

		foreach ( $options as $option ) {
			delete_option( $option );
		}
	}

	/** Saves the options to the database */
	public function save_options() {
		if ( ! current_user_can( 'edit_posts' ) ) {
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		}
		check_admin_referer( 'cno_ig_feed_options_verify', 'cno_ig_feed_options_nonce' );
		$options                 = $this->get_the_options();
		$access_token            = $_POST['cno_ig_feed_options']['app-secret'];
		$options['app-secret']   = $access_token;
		$options['token-expiry'] = time();
		$options                 = $this->model->set_the_options( $options );
		$this->model->refresh_access_token( $access_token );
		wp_safe_redirect( admin_url( 'admin.php?page=cno-ig-feed&status=success' ) );
	}

	/**
	 * Determines if the plugin can retrieve posts from Instagram.
	 *
	 * @return bool True if the plugin has a valid access token, false otherwise.
	 */
	public function can_get_posts(): bool {
		return ! empty( $this->model->secret );
	}

	/**
	 * Get the posts
	 */
	public function get_the_posts(): ?array {
		return $this->model->get_the_posts();
	}

	/**
	 * Retrieves the next scheduled run and returns it as a string.
	 *
	 * @param bool $with_class Whether to include the class name in the returned string.
	 * @return string The next scheduled run as a formatted string.
	 */
	public function get_next_scheduled_run( $with_class = true ): string {
		$timestamp = $this->model->scheduler->get_next_scheduled_run();
		return $this->view->get_next_scheduled_run_string( $timestamp, 'Token Refresh', $with_class );
	}

	/** Gets the timestamp for when Instagram posts were last retrieved
	 *
	 * @param string $format [Optional] How to format the time.
	 */
	public function get_last_fetch( string $format = 'F j, Y g:i:s a' ) {
		$date = $this->model->get_last_fetch( 'America/Chicago' );
		if ( ! is_bool( $date ) ) {
			return $date->format( $format );
		}
		return $date;
	}

	/** Gets the timestamp for the next time Instagram posts will be fetched */
	public function get_next_fetch(): string {
		$timestamp = $this->model->scheduler->get_next_post_fetch();

		return $this->view->get_next_scheduled_run_string( $timestamp, 'Instagram Post Next Fetch' );
	}
}