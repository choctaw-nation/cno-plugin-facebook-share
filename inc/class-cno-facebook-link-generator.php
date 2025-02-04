<?php
/**
 * Link Generator
 *
 * @since 1.0
 * @package ChoctawNation
 * @subpackage FacebookShare
 */

/**
 * Facebook Link Generator
 */
class CNO_Facebook_Link_Generator {
	/**
	 * The Facebook App ID
	 *
	 * @var string $facebook_app_id
	 */
	public string $facebook_app_id;

	/**
	 * The shareable URL
	 *
	 * @var string $shareable_url
	 */
	public string $shareable_url;

	/**
	 * The redirect URL
	 *
	 * @var string $redirect_url
	 */
	public string $redirect_url;

	/** Constructor
	 *
	 * @param string      $shareable_url The URL to share
	 * @param string|null $redirect_url The redirect URL to use. If null, will use the shareable URL
	 */
	public function __construct( string $shareable_url, ?string $redirect_url = null ) {
		$this->facebook_app_id = get_option( 'cno_facebook_share_app_id' );
		$this->shareable_url   = $shareable_url;
		$this->redirect_url    = $redirect_url ?? $shareable_url;
	}

	/**
	 * Gets the href
	 *
	 * @return ?string
	 */
	public function get_the_href(): ?string {
		if ( ! $this->facebook_app_id || ! $this->shareable_url || ! $this->redirect_url ) {
			return null;
		}
		return "https://www.facebook.com/dialog/share?app_id={$this->facebook_app_id}&display=popup&href={$this->shareable_url}&redirect_uri={$this->redirect_url}";
	}

	/**
	 * Echos the href
	 *
	 * @return void
	 */
	public function the_href(): void {
		echo $this->get_the_href();
	}


	/**
	 * Gets the link
	 *
	 * @param array $args {
	 *     Optional. Array of arguments.
	 *
	 *     @type string  $text The link text. Default: 'Share on Facebook'
	 *     @type bool    $with_icon Whether to include the fontawesome icon. Default: true
	 *     @type null|string $fa_icon_classes The fontawesome icon classes. Default: 'fa-brands fa-facebook'
	 *     @type null|string $link_title The link title. Default: null
	 *     @type null|string $link_target The link target. Default: null
	 *     @type null|string $link_classes The link classes. Default: null
	 * }
	 *
	 * @return string
	 */
	public function get_the_link( ?array $args = array() ): string {
		if ( ! empty( $args['text'] ) ) {
			$text = is_null( $args['text'] ) ? null : esc_textarea( $args['text'] );
		} else {
			$text = 'Share on Facebook';
		}
		$with_icon = $args['with_icon'] ?? true;
		if ( isset( $args['link_title'] ) ) {
			$link_title = $args['link_title'];
		} elseif ( is_null( $text ) ) {
			$link_title = 'Share to Facebook';
		} else {
			$link_title = $text;
		}
		$link_target  = $args['link_target'] ?? '_blank';
		$link_classes = $args['link_classes'] ?? null;
		$link_title   = esc_attr( $link_title );
		$markup       = "<a href='{$this->get_the_href()}' title='{$link_title}' target='{$link_target}'" . ( $link_classes ? " class='{$link_classes}'" : '' ) . '>';
		if ( $with_icon ) {
			$fa_icon_classes = $args['fa_icon_classes'] ?? 'fa-brands fa-facebook';
			$markup         .= "<i class='{$fa_icon_classes}'></i> ";
		}
		$markup .= "{$text}</a>";
		return $markup;
	}

	/**
	 * Echos the link
	 *
	 * @param array $args {
	 *     Optional. Array of arguments.
	 *
	 *     @type string  $text The link text. Default: 'Share on Facebook'
	 *     @type bool    $with_icon Whether to include the fontawesome icon. Default: true
	 *     @type null|string $fa_icon_classes The fontawesome icon classes. Default: 'fa-brands fa-facebook'
	 *     @type null|string $link_title The link title. Default: null
	 *     @type null|string $link_target The link target. Default: null
	 *     @type null|string $link_classes The link classes. Default: null
	 * }
	 * @return void
	 */
	public function the_link( ?array $args = array() ): void {
		echo $this->get_the_link( $args );
	}
}