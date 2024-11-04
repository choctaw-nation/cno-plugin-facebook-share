<?php
/**
 * The Admin Screen
 *
 * @package ChoctawNation
 * @subpackage FacebookShare
 * @since 1.0
 */

// show error/update messages
settings_errors( 'cno_ig_feed' );

?>
<div class="wrap" id="cno-facebook-share">
	<h1>
		<?php echo esc_html( get_admin_page_title() ); ?>
	</h1>
	<form method="POST" action="admin-post.php" novalidate="novalidate" autocomplete="off" id="cno-ig-feed">
		<?php
		wp_nonce_field( 'cno_ig_feed_options_verify', 'cno_ig_feed_options_nonce' );
		settings_fields( 'cno_ig_feed_options_group' );
		do_settings_sections( 'cno-ig-feed' );
		submit_button();
		?>
	</form>
</div>