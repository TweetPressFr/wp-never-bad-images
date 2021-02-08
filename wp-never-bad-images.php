<?php
/**
 * Plugin name: WP Never Bad Images
 * Author: Julien Maury
 * Author URI: https://www.julien-maury.dev
 * Version: 0.111
 * Description: I don't encourage the use of this plugin in production. It's quite experimental.
 */

defined( 'DB_USER' )
	or die;

define( 'WP_NBI_DIR', plugin_dir_path( __FILE__ ) );
define( 'WP_NBI_DEFAULT_IMG', 'http://placehold.it/300?text=OOPS+IMAGE+MISSING' );

add_filter( 'the_content', function( $content ) {

	if ( ! class_exists( 'domDocument' ) ) {
		return $content;
	}

	$dom  = new domDocument;
	$dom->loadHTML($content);
	$dom->preserveWhiteSpace = false;
	$images = $dom->getElementsByTagName('img');

	$default = esc_url( apply_filters( 'wp_nbi_default_img_url', WP_NBI_DEFAULT_IMG, $content ) );

	foreach ( $images as $image ) {

		$src = $image->getAttribute('src');
		if ( ! @getimagesize( $src ) ) { // we all love @, don't we ?
			$content = str_replace( $src, $default, $content );
		}
	}

	return $content;
});
