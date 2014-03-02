<?php get_header();
	$gallery = new Gallery( get_queried_object() );
?>

	<header id="gallery-header">

		<h2><?php echo esc_html( $gallery->get_title() ); ?></h2>

	</header>

<?php
	$args = array(
		'post__in'       => $gallery->get_attachment_ids(),
		'orderby'        => 'post__in',
		'post_type'      => 'attachment',
		'post_status'    => 'inherit',
		);
	$attachments_query = new WP_Query( $args );
	
if ( $attachments_query->have_posts() ) : ?>

	<?php while ( $attachments_query->have_posts() ) : $attachments_query->the_post(); ?>

		<div class="col-lg-2">
			<?php get_template_part( 'parts/attachment/square-thumbnail' ); ?>
		</div>

	<?php endwhile; ?>

	<?php else : ?>

	<p><?php _e( "No media has been added to gallery yet.", 'media-library' ); ?></p>

<?php endif; ?>

<?php get_footer(); ?>