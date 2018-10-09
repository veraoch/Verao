<?php get_header();

get_template_part( 'content/archive-header' ); ?>

    <div id="loop-container" class="loop-container">
        <?php
        if ( have_posts() ) :
            while ( have_posts() ) :
                the_post();
                ct_cele_get_content_template();
            endwhile;
        endif;
        ?>
    </div>

<?php

the_posts_pagination( array(
    'prev_text' => esc_html__( 'Previous', 'cele' ),
    'next_text' => esc_html__( 'Next', 'cele' )
) );

get_footer();