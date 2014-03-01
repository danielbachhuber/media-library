<?php

class Media_Library {

	private static $instance;

	public static function get_instance() {

		if ( ! isset( self::$instance ) ) {
			self::$instance = new Media_Library;
			self::$instance->setup_actions();
			self::$instance->setup_filters();
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

		add_action( 'add_attachment', array( $this, 'action_add_attachment' ) );

		add_action( 'wp_enqueue_scripts', array( $this, 'action_wp_enqueue_scripts' ) );

	}

	/**
	 * Filters specific to the theme
	 */
	private function setup_filters() {

		add_filter( 'pre_option_posts_per_page', array( $this, 'filter_posts_per_page' ) );

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
	 * When an attachment is uploaded, let's hook into this business
	 */
	public function action_add_attachment( $attachment_id ) {
		global $wpdb;

		add_filter( 'wp_generate_attachment_metadata', array( $this, 'filter_wp_generate_attachment_metadata' ), 10, 2 );

	}

	/**
	 * Load our theme's styles
	 */
	public function action_wp_enqueue_scripts() {

		wp_enqueue_style( 'bootstrap', get_stylesheet_directory_uri() . '/lib/bootstrap/dist/css/bootstrap.css' );

	}

	/**
	 * Redate the attachment to be the image's original creation date
	 * 
	 * @todo also relocate the image files
	 */
	public function filter_wp_generate_attachment_metadata( $metadata, $attachment_id ) {
		global $wpdb;

		if ( ! empty( $metadata['image_meta']['created_timestamp'] ) ) {
			$wpdb->update( $wpdb->posts, array( 'post_date' => date( 'Y-m-d H:i:s', (int)$metadata['image_meta']['created_timestamp'] ) ), array( 'ID' => $attachment_id ) );
		}

		remove_filter( 'wp_generate_attachment_metadata', array( $this, 'filter_wp_generate_attachment_metadata' ) );

		return $metadata;
	}

	/**
	 * Change the default posts per page
	 */
	public function filter_posts_per_page() {
		return 50;
	}

	/**
	 * Get the image sizes for the theme
	 */
	private function get_image_sizes() {

		return array(
			'square_medium' => array( 200, 200 ),
			'full'          => array( 1200, 1200 ),
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
