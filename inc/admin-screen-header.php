<?php
/** Section Header
 * Displays below the Section Headline
 *
 * @package ChoctawNation
 * @subpackage FacebookShare
 * @since 1.0
 */

if ( isset( $_GET['status'] ) && 'success' === $_GET['status'] ) : // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	?>
<div class='notice notice-success is-dismissible'>
	<p>Settings Updated</p>
</div>
	<?php
endif;
