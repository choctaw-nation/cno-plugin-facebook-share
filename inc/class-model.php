<?php
/**
 * Model
 *
 * @package ChoctawNation
 * @subpackage FacebookShare
 * @since 1.0
 */

namespace ChoctawNation\FacebookShare;

/** Interacts with the WP Database */
class Model {
	/**
	 * The option key
	 *
	 * @var string $option_key
	 */
	private string $option_key = 'cno_facebook_share_app_id';

	/** Returns the value of $options */
	public function get_the_options(): false|string {
		return get_option( $this->option_key );
	}

	/**
	 * Updates the app_id in the database
	 *
	 * @param string $app_id the options array
	 */
	public function set_the_options( string $app_id ): bool {
		return update_option( $this->option_key, $app_id );
	}
}
