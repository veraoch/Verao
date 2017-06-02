<?php

function ct_cele_register_theme_page() {
	add_theme_page( sprintf( __( '%s Dashboard', 'cele' ), wp_get_theme( get_template() ) ), sprintf( __( '%s Dashboard', 'cele' ), wp_get_theme( get_template() ) ), 'edit_theme_options', 'cele-options', 'ct_cele_options_content', 'ct_cele_options_content' );
}
add_action( 'admin_menu', 'ct_cele_register_theme_page' );

function ct_cele_options_content() {

	$customizer_url = add_query_arg(
		array(
			'url'    => get_home_url(),
			'return' => add_query_arg( 'page', 'cele-options', admin_url( 'themes.php' ) )
		),
		admin_url( 'customize.php' )
	);
	$support_url = 'https://www.competethemes.com/documentation/cele-support-center/';
	?>
	<div id="cele-dashboard-wrap" class="wrap">
		<h2><?php printf( __( '%s Dashboard', 'cele' ), wp_get_theme( get_template() ) ); ?></h2>
		<?php do_action( 'ct_cele_theme_options_before' ); ?>
		<div class="content-boxes">
			<div class="content content-support">
				<h3><?php _e( 'Get Started', 'cele' ); ?></h3>
				<p><?php printf( __( 'Not sure where to start? The %1$s Support Center is filled with tutorials that will take you step-by-step through every feature in %1$s.', 'cele' ), wp_get_theme( get_template() ) ); ?></p>
				<p>
					<a target="_blank" class="button-primary"
					   href="https://www.competethemes.com/documentation/cele-support-center/"><?php _e( 'Visit Support Center', 'cele' ); ?></a>
				</p>
			</div>
			<?php if ( !function_exists( 'ct_cele_pro_init' ) ) : ?>
				<div class="content content-premium-upgrade">
					<h3><?php printf( __( 'Cele Pro', 'cele' ), wp_get_theme( get_template() ) ); ?></h3>
					<p><?php printf( __( 'Download the %s Pro plugin and unlock custom colors, new fonts, sliders, and more', 'cele' ), wp_get_theme( get_template() ) ); ?>...</p>
					<p>
						<a target="_blank" class="button-primary"
						   href="https://www.competethemes.com/cele-pro/"><?php _e( 'See Full Feature List', 'cele' ); ?></a>
					</p>
				</div>
			<?php endif; ?>
			<div class="content content-review">
				<h3><?php _e( 'Leave a Review', 'cele' ); ?></h3>
				<p><?php printf( __( 'Help others find %s by leaving a review on wordpress.org.', 'cele' ), wp_get_theme( get_template() ) ); ?></p>
				<a target="_blank" class="button-primary" href="https://wordpress.org/support/theme/cele/reviews/"><?php _e( 'Leave a Review', 'cele' ); ?></a>
			</div>
			<div class="content content-presspad">
				<h3><?php _e( 'Turn Cele into a Mobile App', 'cele' ); ?></h3>
				<p><?php printf( __( '%s can be converted into a mobile app and listed on the App Store with the help of PressPad News. Read our tutorial to learn more.', 'cele' ), wp_get_theme( get_template() ) ); ?></p>
				<a target="_blank" class="button-primary" href="https://www.competethemes.com/help/convert-mobile-app-cele/"><?php _e( 'Read Tutorial', 'cele' ); ?></a>
			</div>
			<div class="content content-delete-settings">
				<h3><?php _e( 'Reset Customizer Settings', 'cele' ); ?></h3>
				<p>
					<?php printf( __( '<strong>Warning:</strong> Clicking this button will erase the %2$s theme\'s current settings in the <a href="%1$s">Customizer</a>.', 'cele' ), esc_url( $customizer_url ), wp_get_theme( get_template() ) ); ?>
				</p>
				<form method="post">
					<input type="hidden" name="cele_reset_customizer" value="cele_reset_customizer_settings"/>
					<p>
						<?php wp_nonce_field( 'cele_reset_customizer_nonce', 'cele_reset_customizer_nonce' ); ?>
						<?php submit_button( __( 'Reset Customizer Settings', 'cele' ), 'delete', 'delete', false ); ?>
					</p>
				</form>
			</div>
		</div>
		<?php do_action( 'ct_cele_theme_options_after' ); ?>
	</div>
<?php }