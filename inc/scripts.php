<?php

// Front-end scripts
function ct_cele_load_scripts_styles() {

	wp_enqueue_style( 'ct-cele-google-fonts', '//fonts.googleapis.com/css?family=Playfair+Display:400|Raleway:400,700,400italic' );

	wp_enqueue_script( 'ct-cele-js', get_template_directory_uri() . '/js/build/production.min.js', array( 'jquery' ), '', true );
	wp_localize_script( 'ct-cele-js', 'objectL10n', array(
		'openMenu'       => __( 'open menu', 'cele' ),
		'closeMenu'      => __( 'close menu', 'cele' ),
		'openChildMenu'  => __( 'open dropdown menu', 'cele' ),
		'closeChildMenu' => __( 'close dropdown menu', 'cele' )
	) );

	wp_enqueue_style( 'font-awesome', get_template_directory_uri() . '/assets/font-awesome/css/font-awesome.min.css' );

	wp_enqueue_style( 'ct-cele-style', get_stylesheet_uri() );

	// enqueue comment-reply script only on posts & pages with comments open ( included in WP core )
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	/* Load Polyfills */

	wp_enqueue_script( 'ct-cele-html5-shiv', get_template_directory_uri() . '/js/build/html5shiv.min.js' );

	wp_enqueue_script( 'ct-cele-respond', get_template_directory_uri() . '/js/build/respond.min.js', '', '', true );

	// prevent fatal error on < WP 4.2 (load files unconditionally instead)
	if ( function_exists( 'wp_script_add_data' ) ) {
		wp_script_add_data( 'ct-cele-html5-shiv', 'conditional', 'IE 8' );
		wp_script_add_data( 'ct-cele-respond', 'conditional', 'IE 8' );
	}
}
add_action( 'wp_enqueue_scripts', 'ct_cele_load_scripts_styles' );

// Back-end scripts
function ct_cele_enqueue_admin_styles( $hook ) {

	if ( $hook == 'appearance_page_cele-options' ) {
		wp_enqueue_style( 'ct-cele-admin-styles', get_template_directory_uri() . '/styles/admin.min.css' );
	}
}
add_action( 'admin_enqueue_scripts', 'ct_cele_enqueue_admin_styles' );

// Customizer scripts
function ct_cele_enqueue_customizer_scripts() {
	wp_enqueue_script( 'ct-cele-customizer-js', get_template_directory_uri() . '/js/build/customizer.min.js', array( 'jquery' ), '', true );
	wp_enqueue_style( 'ct-cele-customizer-styles', get_template_directory_uri() . '/styles/customizer.min.css' );
}
add_action( 'customize_controls_enqueue_scripts', 'ct_cele_enqueue_customizer_scripts' );

/*
 * Script for live updating with customizer options. Has to be loaded separately on customize_preview_init hook
 * transport => postMessage
 */
function ct_cele_enqueue_customizer_post_message_scripts() {
	wp_enqueue_script( 'ct-cele-customizer-post-message-js', get_template_directory_uri() . '/js/build/postMessage.min.js', array( 'jquery' ), '', true );

}
add_action( 'customize_preview_init', 'ct_cele_enqueue_customizer_post_message_scripts' );