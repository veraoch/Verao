<span class="comments-link">
	<i class="fa fa-comment" title="<?php _e( 'comment icon', 'cele' ); ?>"></i>
	<?php
	if ( ! comments_open() && get_comments_number() < 1 ) :
		comments_number( __( 'Comments closed', 'cele' ), __( '1 Comment', 'cele' ), _x( '% Comments', 'noun: 5 comments', 'cele' ) );
	else :
		echo '<a href="' . esc_url( get_comments_link() ) . '">';
		comments_number( __( 'Leave a Comment', 'cele' ), __( '1 Comment', 'cele' ), _x( '% Comments', 'noun: 5 comments', 'cele' ) );
		echo '</a>';
	endif;
	?>
</span>