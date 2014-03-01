<?php

class Media_Library {

	private static $instance;

	public static function get_instance() {

		if ( ! isset( self::$instance ) ) {
			self::$instance = new Media_Library;
		}

		return self::$instance;
	}

	private function __construct() {
		/** Do nothing **/
	}

}

/**
 * Load the theme
 */
function Media_Library() {
	return Media_Library::get_instance();
}
add_action( 'after_setup_theme', 'Media_Library' );
