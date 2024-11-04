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
	private View_Controller $view;

	/**
	 * The Model Layer Controller.
	 *
	 * @var Model $model
	 */
	private Model $model;


	/**
	 * If set, when the current token expires
	 *
	 * @var ?string $token_expiry
	 */
	public ?string $token_expiry;

	/** Constructor */
	public function __construct() {
		$this->wire_actions();
		$this->model = new Model();
		$this->view  = new View_Controller();
	}

	/** Wires the actions to the appropriate hooks */
	private function wire_actions() {
		$actions = array(
			'admin_menu'                                 => array( $this, 'my_admin_menu' ),
			'admin_init'                                 => array( $this, 'register_fields' ),
			'admin_post_cno_facebook_share_save_options' => array( $this, 'save_options' ),
		);

		foreach ( $actions as $hook => $callback ) {
			add_action( $hook, $callback );
		}
	}

	/** Register the Admin Menu */
	public function my_admin_menu() {
		add_menu_page(
			'CNO Facebook Share',
			'CNO Facebook Share',
			'manage_options',
			'cno-facebook-share',
			array( $this->view, 'admin_page' ),
			'dashicons-facebook',
			75
		);
	}



	/** Define Admin Setting Group & Fields */
	public function register_fields() {
		register_setting(
			'cno_facebook_share_options_group',
			'cno_facebook_share_options',
		);

		add_settings_section(
			'cno-facebook-share-settings',
			'Configure the plugin',
			array( $this->view, 'section_callback' ),
			'cno-facebook-share'
		);

		add_settings_field(
			'fb-share-app-secret',
			'Choctaw Nation Sharing App ID',
			function () {
				$this->view->app_id_callback( $this->model->get_the_options() );
			},
			'cno-facebook-share',
			'cno-facebook-share-settings',
			array( 'label_for' => 'fb-share-app-id' )
		);
	}


	/** Saves the options to the database */
	public function save_options() {
		if ( ! current_user_can( 'edit_posts' ) ) {
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		}
		check_admin_referer( 'cno_facebook_share_options_verify', 'cno_facebook_share_options_nonce' );
		$this->model->set_the_options( $_POST['cno_facebook_share_options']['app-id'] );
		wp_safe_redirect( admin_url( 'admin.php?page=cno-facebook-share&status=success' ) );
	}
}