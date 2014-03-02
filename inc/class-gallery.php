<?php

class Gallery {

	private $_post;

	public function __construct( WP_Post $post ) {

		$this->_post = $post;

	}

	/**
	 * Get the ID of the gallery
	 */
	public function get_id() {
		return $this->get_field( 'ID' );
	}

	/**
	 * Get the title of the gallery
	 */
	public function get_title() {
		return $this->get_field( 'post_title' );
	}

	/**
	 * Get the permalink for the gallery
	 * 
	 * @return string
	 */
	public function get_permalink() {
		return apply_filters( 'the_permalink', get_permalink( $this->get_id() ) );
	}

	/**
	 * Get the IDs of all the attachments associated with this gallery
	 * 
	 * @return array
	 */
	public function get_attachment_ids() {
		$query = new WP_Query( array(
			'connected_type'     => 'gallery_to_attachment',
			'connected_items'    => $this->get_id(),
			'fields'             => 'ids',
			'nopaging'           => true,
		) );
		return array_map( 'intval', $query->posts );
	}

	/**
	 * Get a field of the $_post object
	 *
	 * @param string $key
	 * @return mixed
	 */
	private function get_field( $key ) {
		return $this->_post->$key;
	}

}
