<?php

class Media_Library {

	private static $instance;

	public static function get_instance() {

		if ( ! isset( self::$instance ) ) {
			self::$instance = new Media_Library;
			self::$instance->setup_actions();
			self::$instance->setup_theme();
		}

		return self::$instance;
	}

	private function __construct() {
		/** Do nothing **/
	}

	/**
	 * Actions specific to the theme
	 */
	private function setup_actions() {

		add_action( 'pre_get_posts', array( $this, 'action_pre_get_posts' ) );

	}

	/**
	 * Set up the theme environment
	 */
	private function setup_theme() {

		foreach( $this->get_image_sizes() as $image_size => $values ) {
			add_image_size( $image_size, $values[0], $values[1] );
		}

	}

	/**
	 * Modify the main query on some views
	 */
	public function action_pre_get_posts( &$query ) {

		if ( $query->is_main_query() && $query->is_home() ) {
			$query->query['post_type'] = 'attachment';
			$query->query_vars['post_type'] = 'attachment';
			$query->query_vars['post_status'] = 'inherit';
			$query->query_vars['post_status'] = 'inherit';
		}

	}

	/**
	 * Get the image sizes for the theme
	 */
	private function get_image_sizes() {

		return array(
			'square_medium' => array( 200, 200 )
		);

	}
}

/**
 * Load the theme
 */
function Media_Library() {
	return Media_Library::get_instance();
}
add_action( 'after_setup_theme', 'Media_Library' );
