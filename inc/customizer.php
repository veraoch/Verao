<?php

/* Add customizer panels, sections, settings, and controls */
add_action( 'customize_register', 'ct_cele_add_customizer_content' );

function ct_cele_add_customizer_content( $wp_customize ) {

	/***** Reorder default sections *****/

	$wp_customize->get_section( 'title_tagline' )->priority = 1;

	// check if exists in case user has no pages
	if ( is_object( $wp_customize->get_section( 'static_front_page' ) ) ) {
		$wp_customize->get_section( 'static_front_page' )->priority = 5;
		$wp_customize->get_section( 'static_front_page' )->title    = __( 'Front Page', 'cele' );
	}

	/***** Add PostMessage Support *****/

	$wp_customize->get_setting( 'blogname' )->transport        = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';

	/***** Logo Upload *****/

	// section
	$wp_customize->add_section( 'ct_cele_logo_upload', array(
		'title'    => __( 'Logo', 'cele' ),
		'priority' => 20
	) );
	// Upload - setting
	$wp_customize->add_setting( 'logo_upload', array(
		'sanitize_callback' => 'esc_url_raw'
	) );
	// Upload - control
	$wp_customize->add_control( new WP_Customize_Image_Control(
		$wp_customize, 'logo_image', array(
			'label'    => __( 'Upload custom logo.', 'cele' ),
			'section'  => 'ct_cele_logo_upload',
			'settings' => 'logo_upload'
		)
	) );

	/***** Social Media Icons *****/

	// get the social sites array
	$social_sites = ct_cele_social_array();

	// set a priority used to order the social sites
	$priority = 5;

	// section
	$wp_customize->add_section( 'ct_cele_social_media_icons', array(
		'title'       => __( 'Social Media Icons', 'cele' ),
		'priority'    => 25,
		'description' => __( 'Add the URL for each of your social profiles.', 'cele' )
	) );

	// create a setting and control for each social site
	foreach ( $social_sites as $social_site => $value ) {
		// if email icon
		if ( $social_site == 'email' ) {
			// setting
			$wp_customize->add_setting( $social_site, array(
				'sanitize_callback' => 'ct_cele_sanitize_email'
			) );
			// control
			$wp_customize->add_control( $social_site, array(
				'label'    => __( 'Email Address', 'cele' ),
				'section'  => 'ct_cele_social_media_icons',
				'priority' => $priority
			) );
		} else {

			$label = ucfirst( $social_site );

			if ( $social_site == 'google-plus' ) {
				$label = 'Google Plus';
			} elseif ( $social_site == 'rss' ) {
				$label = 'RSS';
			} elseif ( $social_site == 'soundcloud' ) {
				$label = 'SoundCloud';
			} elseif ( $social_site == 'slideshare' ) {
				$label = 'SlideShare';
			} elseif ( $social_site == 'codepen' ) {
				$label = 'CodePen';
			} elseif ( $social_site == 'stumbleupon' ) {
				$label = 'StumbleUpon';
			} elseif ( $social_site == 'deviantart' ) {
				$label = 'DeviantArt';
			} elseif ( $social_site == 'hacker-news' ) {
				$label = 'Hacker News';
			} elseif ( $social_site == 'whatsapp' ) {
				$label = 'WhatsApp';
			} elseif ( $social_site == 'qq' ) {
				$label = 'QQ';
			} elseif ( $social_site == 'vk' ) {
				$label = 'VK';
			} elseif ( $social_site == 'wechat' ) {
				$label = 'WeChat';
			} elseif ( $social_site == 'tencent-weibo' ) {
				$label = 'Tencent Weibo';
			} elseif ( $social_site == 'paypal' ) {
				$label = 'PayPal';
			} elseif ( $social_site == 'email-form' ) {
				$label = 'Contact Form';
			}

			if ( $social_site == 'skype' ) {
				// setting
				$wp_customize->add_setting( $social_site, array(
					'sanitize_callback' => 'ct_cele_sanitize_skype'
				) );
			} else {
				// setting
				$wp_customize->add_setting( $social_site, array(
					'sanitize_callback' => 'esc_url_raw'
				) );
			}
			// control
			$wp_customize->add_control( $social_site, array(
				'type'     => 'url',
				'label'    => $label,
				'section'  => 'ct_cele_social_media_icons',
				'priority' => $priority
			) );
		}
		// increment the priority for next site
		$priority = $priority + 5;
	}

	/***** Blog *****/

	// section
	$wp_customize->add_section( 'cele_blog', array(
		'title'    => __( 'Blog', 'cele' ),
		'priority' => 45
	) );
	// setting
	$wp_customize->add_setting( 'full_post', array(
		'default'           => 'no',
		'sanitize_callback' => 'ct_cele_sanitize_yes_no_settings'
	) );
	// control
	$wp_customize->add_control( 'full_post', array(
		'label'    => __( 'Show full posts on blog?', 'cele' ),
		'section'  => 'cele_blog',
		'settings' => 'full_post',
		'type'     => 'radio',
		'choices'  => array(
			'yes' => __( 'Yes', 'cele' ),
			'no'  => __( 'No', 'cele' )
		)
	) );
	// setting
	$wp_customize->add_setting( 'excerpt_length', array(
		'default'           => '25',
		'sanitize_callback' => 'absint'
	) );
	// control
	$wp_customize->add_control( 'excerpt_length', array(
		'label'    => __( 'Excerpt word count', 'cele' ),
		'section'  => 'cele_blog',
		'settings' => 'excerpt_length',
		'type'     => 'number'
	) );
	// Read More text - setting
	$wp_customize->add_setting( 'read_more_text', array(
		'default'           => __( 'Continue Reading', 'cele' ),
		'sanitize_callback' => 'ct_cele_sanitize_text'
	) );
	// Read More text - control
	$wp_customize->add_control( 'read_more_text', array(
		'label'    => __( 'Read More button text', 'cele' ),
		'section'  => 'cele_blog',
		'settings' => 'read_more_text',
		'type'     => 'text'
	) );

	/***** Display Controls *****/

	// section
	$wp_customize->add_section( 'cele_display', array(
		'title'       => __( 'Display Controls', 'cele' ),
		'priority'    => 55,
		'description' => sprintf( __( 'Want more options like these? Check out the <a target="_blank" href="%s"> Cele Pro plugin</a>.', 'cele' ), 'https://www.competethemes.com/cele-pro/' )
	) );
	// setting - post author
	$wp_customize->add_setting( 'display_post_author', array(
		'default'           => 'show',
		'sanitize_callback' => 'ct_cele_sanitize_show_hide'
	) );
	// control - post author
	$wp_customize->add_control( 'display_post_author', array(
		'type'    => 'radio',
		'label'   => __( 'Post author name in byline', 'cele' ),
		'section' => 'cele_display',
		'setting' => 'display_post_author',
		'choices' => array(
			'show' => __( 'Show', 'cele' ),
			'hide' => __( 'Hide', 'cele' )
		)
	) );
	// setting - post date
	$wp_customize->add_setting( 'display_post_date', array(
		'default'           => 'show',
		'sanitize_callback' => 'ct_cele_sanitize_show_hide'
	) );
	// control - post author
	$wp_customize->add_control( 'display_post_date', array(
		'type'    => 'radio',
		'label'   => __( 'Post date in byline', 'cele' ),
		'section' => 'cele_display',
		'setting' => 'display_post_date',
		'choices' => array(
			'show' => __( 'Show', 'cele' ),
			'hide' => __( 'Hide', 'cele' )
		)
	) );

	/***** Custom CSS *****/

	// section
	$wp_customize->add_section( 'cele_custom_css', array(
		'title'    => __( 'Custom CSS', 'cele' ),
		'priority' => 75
	) );
	// setting
	$wp_customize->add_setting( 'custom_css', array(
		'sanitize_callback' => 'ct_cele_sanitize_css',
		'transport'         => 'postMessage'
	) );
	// control
	$wp_customize->add_control( 'custom_css', array(
		'type'     => 'textarea',
		'label'    => __( 'Add Custom CSS Here:', 'cele' ),
		'section'  => 'cele_custom_css',
		'settings' => 'custom_css'
	) );
}

/***** Custom Sanitization Functions *****/

/*
 * Sanitize settings with show/hide as options
 * Used in: search bar
 */
function ct_cele_sanitize_show_hide( $input ) {

	$valid = array(
		'show' => __( 'Show', 'cele' ),
		'hide' => __( 'Hide', 'cele' )
	);

	return array_key_exists( $input, $valid ) ? $input : '';
}

/*
 * sanitize email address
 * Used in: Social Media Icons
 */
function ct_cele_sanitize_email( $input ) {
	return sanitize_email( $input );
}

// sanitize yes/no settings
function ct_cele_sanitize_yes_no_settings( $input ) {

	$valid = array(
		'yes' => __( 'Yes', 'cele' ),
		'no'  => __( 'No', 'cele' )
	);

	return array_key_exists( $input, $valid ) ? $input : '';
}

function ct_cele_sanitize_text( $input ) {
	return wp_kses_post( force_balance_tags( $input ) );
}

function ct_cele_sanitize_skype( $input ) {
	return esc_url_raw( $input, array( 'http', 'https', 'skype' ) );
}

function ct_cele_sanitize_css( $css ) {
	$css = wp_kses( $css, array( '\'', '\"' ) );
	$css = str_replace( '&gt;', '>', $css );

	return $css;
}

/***** Helper Functions *****/

function ct_cele_customize_preview_js() {

	$content = "<script>jQuery('#customize-info').prepend('<div class=\"upgrades-ad\"><a href=\"https://www.competethemes.com/cele-pro/\" target=\"_blank\">" . __( 'View the Cele Pro Plugin', 'cele' ) . " <span>&rarr;</span></a></div>')</script>";
	echo apply_filters( 'ct_cele_customizer_ad', $content );
}

add_action( 'customize_controls_print_footer_scripts', 'ct_cele_customize_preview_js' );