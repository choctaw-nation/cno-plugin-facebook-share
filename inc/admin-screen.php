<?php
/**
 * The Admin Screen
 *
 * @package ChoctawNation
 * @subpackage FacebookShare
 * @since 1.0
 */

// show error/update messages
settings_errors( 'cno_facebook_share' );

?>
<div class="wrap">
	<h1>
		<?php echo esc_html( get_admin_page_title() ); ?>
	</h1>
	<form method="POST" action="admin-post.php" novalidate="novalidate" autocomplete="off" id="cno-facebook-share">
		<?php
		wp_nonce_field( 'cno_facebook_share_options_verify', 'cno_facebook_share_options_nonce' );
		settings_fields( 'cno_facebook_share_options_group' );
		do_settings_sections( 'cno-facebook-share' );
		submit_button();
		?>
	</form>
</div>