<?php get_header(); ?>

<?php if ( have_posts() ) :
	$date_shown = false;
	?>

	<?php while ( have_posts() ) : the_post();

		if ( ! $date_shown || $date_shown != get_the_time( 'Y-m-d' ) ) : ?>

			<?php if ( $date_shown ) : ?>
				</div>
			<?php endif ;?>

			<div class="row">

			<h3><?php

				$date_format = 'l, F j';
				if ( get_the_time( 'Y' ) !== date( 'Y' ) ) {
					$date_format .= ', Y';
				}
				the_time( $date_format );

			?></h3>

		<?php 
			$date_shown = get_the_time( 'Y-m-d' );
		endif; ?>

		<div class="col-lg-2">
		<?php get_template_part( 'parts/attachment/square-thumbnail' ); ?>
		</div>

	<?php endwhile; ?>

		</div><!-- .row -->

	<?php else : ?>

	<p><?php _e( "No media has been uploaded yet.", 'media-library' ); ?></p>

<?php endif; ?>

<?php get_footer(); ?>