<?php

function ct_cele_register_theme_page() {
	add_theme_page( __( 'Cele Dashboard', 'cele' ), __( 'Cele Dashboard', 'cele' ), 'edit_theme_options', 'cele-options', 'ct_cele_options_content', 'ct_cele_options_content' );
}
add_action( 'admin_menu', 'ct_cele_register_theme_page' );

function ct_cele_options_content() {

	$customizer_url = add_query_arg(
		array(
			'url'    => site_url(),
			'return' => add_query_arg( 'page', 'cele-options', admin_url( 'themes.php' ) )
		),
		admin_url( 'customize.php' )
	);
	?>
	<div id="cele-dashboard-wrap" class="wrap">
		<h2><?php _e( 'Cele Dashboard', 'cele' ); ?></h2>
		<?php do_action( 'ct_cele_theme_options_before' ); ?>
		<div class="content content-customization">
			<h3><?php _e( 'Customization', 'cele' ); ?></h3>
			<p><?php _e( 'Click the "Customize" link in your menu, or use the button below to get started customizing Cele', 'cele' ); ?>.</p>
			<p>
				<a class="button-primary"
				   href="<?php echo esc_url( $customizer_url ); ?>"><?php _e( 'Use Customizer', 'cele' ) ?></a>
			</p>
		</div>
		<div class="content content-support">
			<h3><?php _e( 'Support', 'cele' ); ?></h3>
			<p><?php _e( "You can find the knowledgebase, changelog, support forum, and more in the Cele Support Center", "cele" ); ?>.</p>
			<p>
				<a target="_blank" class="button-primary"
				   href="https://www.competethemes.com/documentation/cele-support-center/"><?php _e( 'Visit Support Center', 'cele' ); ?></a>
			</p>
		</div>
		<div class="content content-premium-upgrade">
			<h3><?php _e( 'Cele Pro', 'cele' ); ?></h3>
			<p><?php _e( 'Download the Cele Pro plugin and unlock custom colors, new fonts, sliders, and more', 'cele' ); ?>...</p>
			<p>
				<a target="_blank" class="button-primary"
				   href="https://www.competethemes.com/cele-pro/"><?php _e( 'See Full Feature List', 'cele' ); ?></a>
			</p>
		</div>
		<div class="content content-resources">
			<h3><?php _e( 'WordPress Resources', 'cele' ); ?></h3>
			<p><?php _e( 'Save time and money searching for WordPress products by following our recommendations', 'cele' ); ?>.</p>
			<p>
				<a target="_blank" class="button-primary"
				   href="https://www.competethemes.com/wordpress-resources/"><?php _e( 'View Resources', 'cele' ); ?></a>
			</p>
		</div>
		<div class="content content-review">
			<h3><?php _e( 'Leave a Review', 'cele' ); ?></h3>
			<p><?php _e( 'Help others find Cele by leaving a review on wordpress.org.', 'cele' ); ?></p>
			<a target="_blank" class="button-primary" href="https://wordpress.org/support/view/theme-reviews/cele"><?php _e( 'Leave a Review', 'cele' ); ?></a>
		</div>
		<div class="content content-delete-settings">
			<h3><?php _e( 'Reset Customizer Settings', 'cele' ); ?></h3>
			<p>
				<?php printf( __( "<strong>Warning:</strong> Clicking this button will erase the Cele theme's current settings in the <a href='%s'>Customizer</a>.", 'cele' ), esc_url( $customizer_url ) ); ?>
			</p>
			<form method="post">
				<input type="hidden" name="cele_reset_customizer" value="cele_reset_customizer_settings"/>
				<p>
					<?php wp_nonce_field( 'cele_reset_customizer_nonce', 'cele_reset_customizer_nonce' ); ?>
					<?php submit_button( __( 'Reset Customizer Settings', 'cele' ), 'delete', 'delete', false ); ?>
				</p>
			</form>
		</div>
		<?php do_action( 'ct_cele_theme_options_after' ); ?>
	</div>
<?php }