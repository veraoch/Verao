<div class="post-author">
	<?php ct_cele_profile_image_output(); ?>
	<h3><?php echo get_the_author(); ?></h3>
	<?php ct_cele_social_icons_output('author') ?>
	<p><?php the_author_meta('description'); ?></p>
	<a href="<?php echo get_author_posts_url( get_the_author_meta('ID') ); ?>"><?php _e( 'View more posts', 'cele' ); ?></a>
</div>