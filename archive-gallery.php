<?php get_header(); ?>

<?php if ( have_posts() ) : ?>

	<?php while ( have_posts() ) : the_post() ?>

	<div class="col-lg-2">
		<?php get_template_part( 'parts/gallery/square-thumbnail' ); ?>
	</div>

	<?php endwhile; ?>

<?php else : ?>

	<p><?php _e( "No galleries yet. Why don't you create one?", 'media-library' ); ?></p>

<?php endif; ?>

<?php get_footer(); ?>