<?php
/**
 * Plugin Loader
 *
 * @since 1.0
 * @package ChoctawNation
 * @subpackage FacebookShare
 */

namespace ChoctawNation\FacebookShare;

/** Inits the Plugin */
class Plugin_Loader {
	/**
	 * Constructor
	 */
	public function __construct() {
		$this->load_required_files();
	}

	/**
	 * Loads the required files
	 *
	 * @return void
	 */
	private function load_required_files(): void {
		$base_path = plugin_dir_path( __DIR__ );
		$files     = array(
			'class-view',
			'class-model',
			'class-controller',
			'class-cno-facebook-link-generator',
		);
		foreach ( $files as $file ) {
			require_once $base_path . "inc/{$file}.php";
		}

		new Controller();
	}



	/**
	 * Initializes the Plugin
	 *
	 * @return void
	 */
	public function activate(): void {
		flush_rewrite_rules();
	}

	/**
	 * Handles Plugin Deactivation
	 * (this is a callback function for the `register_deactivation_hook` function)
	 *
	 * @return void
	 */
	public function deactivate(): void {
		flush_rewrite_rules();
	}
}