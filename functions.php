<?php

class Media_Library {

	private static $instance;

	public static function get_instance() {

		if ( ! isset( self::$instance ) ) {
			self::$instance = new Media_Library;
			self::$instance->require_files();
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
	 * Load required libraries
	 */
	private function require_files() {

		require_once dirname( __FILE__ ) . '/inc/class-gallery.php';	
		require_once dirname( __FILE__ ) . '/lib/wp-posts-to-posts/posts-to-posts.php';

	}

	/**
	 * Actions specific to the theme
	 */
	private function setup_actions() {

		add_action( 'init', array( $this, 'action_init' ) );

		add_action( 'p2p_init', array( $this, 'action_p2p_init' ) );

		add_action( 'pre_get_posts', array( $this, 'action_pre_get_posts' ) );

		add_action( 'add_attachment', array( $this, 'action_add_attachment' ) );

		add_action( 'wp_enqueue_scripts', array( $this, 'action_wp_enqueue_scripts' ) );

	}

	/**
	 * Filters specific to the theme
	 */
	private function setup_filters() {

		add_filter( 'pre_option_posts_per_page', array( $this, 'filter_posts_per_page' ) );

		add_filter( 'plugins_url', array( $this, 'filter_plugins_url' ) );

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
	 * Whatever needs to be done on init
	 */
	public function action_init() {

		$this->register_gallery_post_type();

	}

	/**
	 * Register our post to post relationships
	 */
	public function action_p2p_init() {

		p2p_register_connection_type( array(
			'name'        => 'gallery_to_attachment',
			'from'        => 'gallery',
			'to'          => 'attachment',
		) );

	}

	/**
	 * Register the Gallery post type
	 */
	private function register_gallery_post_type() {

		register_post_type( 'gallery', array(
			'hierarchical'      => false,
			'public'            => true,
			'show_in_nav_menus' => true,
			'show_ui'           => true,
			'supports'          => array( 'title', 'editor' ),
			'has_archive'       => true,
			'query_var'         => true,
			'rewrite'           => true,
			'labels'            => array(
				'name'                => __( 'Galleries', 'media-library' ),
				'singular_name'       => __( 'Gallery', 'media-library' ),
				'all_items'           => __( 'Galleries', 'media-library' ),
				'new_item'            => __( 'New Gallery', 'media-library' ),
				'add_new'             => __( 'Add New', 'media-library' ),
				'add_new_item'        => __( 'Add New Gallery', 'media-library' ),
				'edit_item'           => __( 'Edit Gallery', 'media-library' ),
				'view_item'           => __( 'View Gallery', 'media-library' ),
				'search_items'        => __( 'Search Galleries', 'media-library' ),
				'not_found'           => __( 'No Galleries found', 'media-library' ),
				'not_found_in_trash'  => __( 'No Galleries found in trash', 'media-library' ),
				'parent_item_colon'   => __( 'Parent Gallery', 'media-library' ),
				'menu_name'           => __( 'Galleries', 'media-library' ),
			),
		) );

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
		wp_enqueue_style( 'core-style', get_stylesheet_directory_uri() . '/assets/stylesheets/core.css' );

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
	 * Plugins loaded from the theme need to be appropriately filtered
	 */
	public function filter_plugins_url( $url ) {

		$theme_str = 'themes/media-library';
		if ( false !== stripos( $url, $theme_str ) ) {
			$parts = explode( $theme_str, $url );
			if ( ! empty( $parts[1] ) ) {
				return get_stylesheet_directory_uri() . $parts[1];
			}
		}

		return $url;
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
