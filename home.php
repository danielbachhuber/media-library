<?php get_header(); ?>

<?php if ( have_posts() ) : ?>

	<?php while ( have_posts() ) : the_post(); ?>

		<?php get_template_part( 'parts/attachment/square-thumbnail' ); ?>

	<?php endwhile; ?>

	<?php else : ?>

	<p><?php _e( "No media has been uploaded yet.", 'media-library' ); ?></p>

<?php endif; ?>

<?php get_footer(); ?>