<?php do_action( 'main_bottom' ); ?>
</section> <!-- .main -->

<?php do_action( 'after_main' ); ?>

<footer id="site-footer" class="site-footer" role="contentinfo">
    <?php do_action( 'footer_top' ); ?>
    <div class="design-credit">
        <span>
            <?php
            $footer_text = sprintf( __( '<a href="%s">Cele WordPress Theme</a> by Compete Themes.', 'cele' ), 'https://www.competethemes.com/cele/' );
            $footer_text = apply_filters( 'ct_cele_footer_text', $footer_text );
            echo wp_kses_post( $footer_text );
            ?>
        </span>
    </div>
</footer>
</div>
</div><!-- .overflow-container -->

<?php do_action( 'body_bottom' ); ?>

<?php wp_footer(); ?>

</body>
</html>