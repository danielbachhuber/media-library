<?php $gallery = new Gallery( get_post() ); ?>
<div class="gallery gallery-square-thumbnail" id="gallery-<?php $gallery->get_id(); ?>">
	<h4><a href="<?php echo $gallery->get_permalink(); ?>"><?php echo esc_html( $gallery->get_title() ); ?></a></h4>
</div>