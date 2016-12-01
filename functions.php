<?php

require_once( trailingslashit( get_template_directory() ) . 'theme-options.php' );
foreach ( glob( trailingslashit( get_template_directory() ) . 'inc/*' ) as $filename ) {
	include $filename;
}

if ( ! function_exists( ( 'ct_cele_set_content_width' ) ) ) {
	function ct_cele_set_content_width() {
		if ( ! isset( $content_width ) ) {
			$content_width = 891;
		}
	}
}
add_action( 'after_setup_theme', 'ct_cele_set_content_width', 0 );

if ( ! function_exists( ( 'ct_cele_theme_setup' ) ) ) {
	function ct_cele_theme_setup() {

		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'automatic-feed-links' );
		add_theme_support( 'title-tag' );
		add_theme_support( 'html5', array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption'
		) );
		add_theme_support( 'infinite-scroll', array(
			'container' => 'loop-container',
			'footer'    => 'overflow-container',
			'render'    => 'ct_cele_infinite_scroll_render'
		) );

		register_nav_menus( array(
			'primary' => esc_html__( 'Primary', 'cele' )
		) );

		load_theme_textdomain( 'cele', get_template_directory() . '/languages' );
	}
}
add_action( 'after_setup_theme', 'ct_cele_theme_setup', 10 );

if ( ! function_exists( ( 'ct_cele_register_widget_areas' ) ) ) {
	function ct_cele_register_widget_areas() {

		register_sidebar( array(
			'name'          => esc_html__( 'Primary Sidebar', 'cele' ),
			'id'            => 'primary',
			'description'   => esc_html__( 'Widgets in this area will be shown in the sidebar next to the main post content', 'cele' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>'
		) );
	}
}
add_action( 'widgets_init', 'ct_cele_register_widget_areas' );

if ( ! function_exists( ( 'ct_cele_customize_comments' ) ) ) {
	function ct_cele_customize_comments( $comment, $args, $depth ) {
		$GLOBALS['comment'] = $comment;
		global $post;
		?>
		<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
		<article id="comment-<?php comment_ID(); ?>" class="comment">
			<div class="comment-author">
				<?php
				echo get_avatar( get_comment_author_email(), 48, '', get_comment_author() );
				?>
				<span class="author-name"><?php comment_author_link(); ?></span>
				<span class="comment-date"><?php comment_date(); ?></span>
			</div>
			<div class="comment-content">
				<?php if ( $comment->comment_approved == '0' ) : ?>
					<em><?php _e( 'Your comment is awaiting moderation.', 'cele' ) ?></em>
					<br/>
				<?php endif; ?>
				<?php comment_text(); ?>
			</div>
			<div class="comment-footer">
				<?php comment_reply_link( array_merge( $args, array(
					'reply_text' => __( 'Reply', 'cele' ),
					'depth'      => $depth,
					'max_depth'  => $args['max_depth'],
					'after'     => '<i class="fa fa-reply"></i>'
				) ) ); ?>
				<?php edit_comment_link( __( 'Edit', 'cele' ), '<div class="edit-comment-container">', '<i class="fa fa-pencil"></i></div>' ); ?>
			</div>
		</article>
		<?php
	}
}

if ( ! function_exists( 'ct_cele_update_fields' ) ) {
	function ct_cele_update_fields( $fields ) {

		$commenter = wp_get_current_commenter();
		$req       = get_option( 'require_name_email' );
		$label     = $req ? '*' : ' ' . __( '(optional)', 'cele' );
		$aria_req  = $req ? "aria-required='true'" : '';

		$fields['author'] =
			'<p class="comment-form-author">
	            <label for="author">' . __( "Name", "cele" ) . $label . '</label>
	            <input id="author" name="author" type="text" placeholder="' . esc_attr__( "Jane Doe", "cele" ) . '" value="' . esc_attr( $commenter['comment_author'] ) .
			'" size="30" ' . $aria_req . ' />
	        </p>';

		$fields['email'] =
			'<p class="comment-form-email">
	            <label for="email">' . __( "Email", "cele" ) . $label . '</label>
	            <input id="email" name="email" type="email" placeholder="' . esc_attr__( "name@email.com", "cele" ) . '" value="' . esc_attr( $commenter['comment_author_email'] ) .
			'" size="30" ' . $aria_req . ' />
	        </p>';

		$fields['url'] =
			'<p class="comment-form-url">
	            <label for="url">' . __( "Website", "cele" ) . '</label>
	            <input id="url" name="url" type="url"  placeholder="' . esc_attr__( "http://google.com", "cele" ) . '" value="' . esc_attr( $commenter['comment_author_url'] ) .
			'" size="30" />
	            </p>';

		return $fields;
	}
}
add_filter( 'comment_form_default_fields', 'ct_cele_update_fields' );

if ( ! function_exists( 'ct_cele_update_comment_field' ) ) {
	function ct_cele_update_comment_field( $comment_field ) {

		$comment_field =
			'<p class="comment-form-comment">
	            <label for="comment">' . __( "Comment", "cele" ) . '</label>
	            <textarea required id="comment" name="comment" cols="45" rows="8" aria-required="true"></textarea>
	        </p>';

		return $comment_field;
	}
}
add_filter( 'comment_form_field_comment', 'ct_cele_update_comment_field' );

if ( ! function_exists( 'ct_cele_remove_comments_notes_after' ) ) {
	function ct_cele_remove_comments_notes_after( $defaults ) {
		$defaults['comment_notes_after'] = '';
		return $defaults;
	}
}
add_action( 'comment_form_defaults', 'ct_cele_remove_comments_notes_after' );

if ( ! function_exists( 'ct_cele_excerpt' ) ) {
	function ct_cele_excerpt() {

		global $post;
		$show_full_post = get_theme_mod( 'full_post' );
		$read_more_text = get_theme_mod( 'read_more_text' );
		$ismore         = strpos( $post->post_content, '<!--more-->' );

		if ( ( $show_full_post == 'yes' ) && ! is_search() ) {
			if ( $ismore ) {
				// Has to be written this way because i18n text CANNOT be stored in a variable
				if ( ! empty( $read_more_text ) ) {
					the_content( esc_html($read_more_text) . " <span class='screen-reader-text'>" . esc_html( get_the_title() ) . "</span>" );
				} else {
					the_content( __( 'Continue reading', 'cele' ) . " <span class='screen-reader-text'>" . esc_html( get_the_title() ) . "</span>" );
				}
			} else {
				the_content();
			}
		} elseif ( $ismore ) {
			if ( ! empty( $read_more_text ) ) {
				the_content( esc_html($read_more_text) . " <span class='screen-reader-text'>" . esc_html( get_the_title() ) . "</span>" );
			} else {
				the_content( __( 'Continue reading', 'cele' ) . " <span class='screen-reader-text'>" . esc_html( get_the_title() ) . "</span>" );
			}
		} else {
			the_excerpt();
		}
	}
}

if ( ! function_exists( 'ct_cele_excerpt_read_more_link' ) ) {
	function ct_cele_excerpt_read_more_link( $output ) {

		$read_more_text = get_theme_mod( 'read_more_text' );

		if ( ! empty( $read_more_text ) ) {
			return $output . "<p><a class='more-link' href='" . esc_url( get_permalink() ) . "'>" . esc_html($read_more_text) . " <span class='screen-reader-text'>" . esc_html( get_the_title() ) . "</span></a></p>";
		} else {
			return $output . "<p><a class='more-link' href='" . esc_url( get_permalink() ) . "'>" . __( 'Continue reading', 'cele' ) . " <span class='screen-reader-text'>" . esc_html( get_the_title() ) . "</span></a></p>";
		}
	}
}
add_filter( 'the_excerpt', 'ct_cele_excerpt_read_more_link' );

if ( ! function_exists( 'ct_cele_custom_excerpt_length' ) ) {
	function ct_cele_custom_excerpt_length( $length ) {

		$new_excerpt_length = get_theme_mod( 'excerpt_length' );

		if ( ! empty( $new_excerpt_length ) && $new_excerpt_length != 25 ) {
			return $new_excerpt_length;
		} elseif ( $new_excerpt_length === 0 ) {
			return 0;
		} else {
			return 25;
		}
	}
}
add_filter( 'excerpt_length', 'ct_cele_custom_excerpt_length', 99 );

if ( ! function_exists( 'ct_cele_new_excerpt_more' ) ) {
	function ct_cele_new_excerpt_more( $more ) {

		$new_excerpt_length = get_theme_mod( 'excerpt_length' );
		$excerpt_more       = ( $new_excerpt_length === 0 ) ? '' : '&#8230;';

		return $excerpt_more;
	}
}
add_filter( 'excerpt_more', 'ct_cele_new_excerpt_more' );

if ( ! function_exists( 'ct_cele_remove_more_link_scroll' ) ) {
	function ct_cele_remove_more_link_scroll( $link ) {
		$link = preg_replace( '|#more-[0-9]+|', '', $link );
		return $link;
	}
}
add_filter( 'the_content_more_link', 'ct_cele_remove_more_link_scroll' );

if ( ! function_exists( 'ct_cele_featured_image' ) ) {
	function ct_cele_featured_image() {

		global $post;
		$featured_image = '';

		if ( has_post_thumbnail( $post->ID ) ) {

			if ( is_singular() ) {
				$featured_image = '<div class="featured-image">' . get_the_post_thumbnail( $post->ID, 'full' ) . '</div>';
			} else {
				$featured_image = '<div class="featured-image"><a href="' . esc_url( get_permalink() ) . '">' . esc_html(get_the_title()) . get_the_post_thumbnail( $post->ID, 'full' ) . '</a></div>';
			}
		}

		$featured_image = apply_filters( 'ct_cele_featured_image', $featured_image );

		if ( $featured_image ) {
			echo $featured_image;
		}
	}
}

if ( ! function_exists( 'ct_cele_social_array' ) ) {
	function ct_cele_social_array() {

		$social_sites = array(
			'twitter'       => 'cele_twitter_profile',
			'facebook'      => 'cele_facebook_profile',
			'google-plus'   => 'cele_googleplus_profile',
			'pinterest'     => 'cele_pinterest_profile',
			'linkedin'      => 'cele_linkedin_profile',
			'youtube'       => 'cele_youtube_profile',
			'vimeo'         => 'cele_vimeo_profile',
			'tumblr'        => 'cele_tumblr_profile',
			'instagram'     => 'cele_instagram_profile',
			'flickr'        => 'cele_flickr_profile',
			'dribbble'      => 'cele_dribbble_profile',
			'rss'           => 'cele_rss_profile',
			'reddit'        => 'cele_reddit_profile',
			'soundcloud'    => 'cele_soundcloud_profile',
			'spotify'       => 'cele_spotify_profile',
			'vine'          => 'cele_vine_profile',
			'yahoo'         => 'cele_yahoo_profile',
			'behance'       => 'cele_behance_profile',
			'codepen'       => 'cele_codepen_profile',
			'delicious'     => 'cele_delicious_profile',
			'stumbleupon'   => 'cele_stumbleupon_profile',
			'deviantart'    => 'cele_deviantart_profile',
			'digg'          => 'cele_digg_profile',
			'github'        => 'cele_github_profile',
			'hacker-news'   => 'cele_hacker-news_profile',
			'steam'         => 'cele_steam_profile',
			'vk'            => 'cele_vk_profile',
			'snapchat'      => 'cele_snapchat_profile',
			'bandcamp'      => 'cele_bandcamp_profile',
			'etsy'          => 'cele_etsy_profile',
			'quora'         => 'cele_quora_profile',
			'ravelry'       => 'cele_ravelry_profile',
			'meetup'        => 'cele_meetup_profile',
			'telegram'      => 'cele_telegram_profile',
			'podcast'       => 'cele_podcast_profile',
			'weibo'         => 'cele_weibo_profile',
			'tencent-weibo' => 'cele_tencent_weibo_profile',
			'500px'         => 'cele_500px_profile',
			'foursquare'    => 'cele_foursquare_profile',
			'slack'         => 'cele_slack_profile',
			'slideshare'    => 'cele_slideshare_profile',
			'qq'            => 'cele_qq_profile',
			'whatsapp'      => 'cele_whatsapp_profile',
			'skype'         => 'cele_skype_profile',
			'wechat'        => 'cele_wechat_profile',
			'xing'          => 'cele_xing_profile',
			'paypal'        => 'cele_paypal_profile',
			'email'         => 'cele_email_profile',
			'email-form'    => 'cele_email_form_profile'
		);

		return apply_filters( 'ct_cele_social_array_filter', $social_sites );
	}
}

if ( ! function_exists( 'ct_cele_social_icons_output' ) ) {
	function ct_cele_social_icons_output() {

		$social_sites = ct_cele_social_array();

		foreach ( $social_sites as $social_site => $profile ) {

			if ( strlen( get_theme_mod( $social_site ) ) > 0 ) {
				$active_sites[ $social_site ] = $social_site;
			}
		}

		if ( ! empty( $active_sites ) ) {

			echo "<ul class='social-media-icons'>";

			foreach ( $active_sites as $key => $active_site ) {

				if ( $active_site == 'email-form' ) {
					$class = 'fa fa-envelope-o';
				} else {
					$class = 'fa fa-' . $active_site;
				}

				echo '<li>';
				if ( $active_site == 'email' ) { ?>
					<a class="email" target="_blank"
					   href="mailto:<?php echo antispambot( is_email( get_theme_mod( $key ) ) ); ?>">
						<i class="fa fa-envelope" title="<?php esc_attr_e( 'email', 'cele' ); ?>"></i>
						<span class="screen-reader-text"><?php esc_attr_e('email', 'cele'); ?></span>
					</a>
				<?php } elseif ( $active_site == 'skype' ) { ?>
					<a class="<?php echo esc_attr( $active_site ); ?>" target="_blank"
					   href="<?php echo esc_url( get_theme_mod( $key ), array( 'http', 'https', 'skype' ) ); ?>">
						<i class="<?php echo esc_attr( $class ); ?>"
						   title="<?php echo esc_attr( $active_site ); ?>"></i>
						<span class="screen-reader-text"><?php echo esc_html( $active_site );  ?></span>
					</a>
				<?php } else { ?>
					<a class="<?php echo esc_attr( $active_site ); ?>" target="_blank"
					   href="<?php echo esc_url( get_theme_mod( $key ) ); ?>">
						<i class="<?php echo esc_attr( $class ); ?>"
						   title="<?php echo esc_attr( $active_site ); ?>"></i>
						<span class="screen-reader-text"><?php echo esc_html( $active_site );  ?></span>
					</a>
					<?php
				}
				echo '</li>';
			}
			echo "</ul>";
		}
	}
}

/*
 * WP will apply the ".menu-primary-items" class & id to the containing <div> instead of <ul>
 * making styling difficult and confusing. Using this wrapper to add a unique class to make styling easier.
 */
if ( ! function_exists( ( 'ct_cele_wp_page_menu' ) ) ) {
	function ct_cele_wp_page_menu() {
		wp_page_menu( array(
				"menu_class" => "menu-unset",
				"depth"      => - 1
			)
		);
	}
}

if ( ! function_exists( ( 'ct_cele_nav_dropdown_buttons' ) ) ) {
	function ct_cele_nav_dropdown_buttons( $item_output, $item, $depth, $args ) {

		if ( $args->theme_location == 'primary' ) {

			if ( in_array( 'menu-item-has-children', $item->classes ) || in_array( 'page_item_has_children', $item->classes ) ) {
				$item_output = str_replace( $args->link_after . '</a>', $args->link_after . '</a><button class="toggle-dropdown" aria-expanded="false" name="toggle-dropdown"><span class="screen-reader-text">' . __( "open menu", "cele" ) . '</span></button>', $item_output );
			}
		}

		return $item_output;
	}
}
add_filter( 'walker_nav_menu_start_el', 'ct_cele_nav_dropdown_buttons', 10, 4 );

if ( ! function_exists( ( 'ct_cele_sticky_post_marker' ) ) ) {
	function ct_cele_sticky_post_marker() {

		if ( is_sticky() && ! is_archive() ) {
			echo '<div class="sticky-status"><span>' . __( "Featured", "cele" ) . '</span></div>';
		}
	}
}
add_action( 'ct_cele_sticky_post_status', 'ct_cele_sticky_post_marker' );

if ( ! function_exists( ( 'ct_cele_reset_customizer_options' ) ) ) {
	function ct_cele_reset_customizer_options() {

		if ( empty( $_POST['cele_reset_customizer'] ) || 'cele_reset_customizer_settings' !== $_POST['cele_reset_customizer'] ) {
			return;
		}

		if ( ! wp_verify_nonce( $_POST['cele_reset_customizer_nonce'], 'cele_reset_customizer_nonce' ) ) {
			return;
		}

		if ( ! current_user_can( 'edit_theme_options' ) ) {
			return;
		}

		$mods_array = array(
			'logo_upload',
			'full_post',
			'excerpt_length',
			'read_more_text',
			'display_post_author',
			'display_post_date',
			'custom_css'
		);

		$social_sites = ct_cele_social_array();

		// add social site settings to mods array
		foreach ( $social_sites as $social_site => $value ) {
			$mods_array[] = $social_site;
		}

		$mods_array = apply_filters( 'ct_cele_mods_to_remove', $mods_array );

		foreach ( $mods_array as $theme_mod ) {
			remove_theme_mod( $theme_mod );
		}

		$redirect = admin_url( 'themes.php?page=cele-options' );
		$redirect = add_query_arg( 'cele_status', 'deleted', $redirect );

		// safely redirect
		wp_safe_redirect( $redirect );
		exit;
	}
}
add_action( 'admin_init', 'ct_cele_reset_customizer_options' );

if ( ! function_exists( ( 'ct_cele_delete_settings_notice' ) ) ) {
	function ct_cele_delete_settings_notice() {

		if ( isset( $_GET['cele_status'] ) ) {
			?>
			<div class="updated">
				<p><?php _e( 'Customizer settings deleted', 'cele' ); ?>.</p>
			</div>
			<?php
		}
	}
}
add_action( 'admin_notices', 'ct_cele_delete_settings_notice' );

if ( ! function_exists( ( 'ct_cele_body_class' ) ) ) {
	function ct_cele_body_class( $classes ) {

		global $post;
		$full_post = get_theme_mod( 'full_post' );

		if ( $full_post == 'yes' ) {
			$classes[] = 'full-post';
		}

		return $classes;
	}
}
add_filter( 'body_class', 'ct_cele_body_class' );

if ( ! function_exists( ( 'ct_cele_post_class' ) ) ) {
	function ct_cele_post_class( $classes ) {
		$classes[] = 'entry';

		return $classes;
	}
}
add_filter( 'post_class', 'ct_cele_post_class' );

if ( ! function_exists( ( 'ct_cele_custom_css_output' ) ) ) {
	function ct_cele_custom_css_output() {

		if ( function_exists( 'wp_get_custom_css' ) ) {
			$custom_css = wp_get_custom_css();
		} else {
			$custom_css = get_theme_mod( 'custom_css' );
		}

		if ( ! empty( $custom_css ) ) {
			$custom_css = ct_cele_sanitize_css( $custom_css );

			wp_add_inline_style( 'ct-cele-style', $custom_css );
			wp_add_inline_style( 'ct-cele-style-rtl', $custom_css );
		}
	}
}
add_action( 'wp_enqueue_scripts', 'ct_cele_custom_css_output', 20 );

if ( ! function_exists( ( 'ct_cele_svg_output' ) ) ) {
	function ct_cele_svg_output( $type ) {

		$svg = '';

		if ( $type == 'toggle-navigation' ) {

			$svg = '<svg width="24px" height="18px" viewBox="0 0 24 18" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
				    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
				        <g transform="translate(-148.000000, -36.000000)" fill="#6B6B6B">
				            <g transform="translate(123.000000, 25.000000)">
				                <g transform="translate(25.000000, 11.000000)">
				                    <rect x="0" y="16" width="24" height="2"></rect>
				                    <rect x="0" y="8" width="24" height="2"></rect>
				                    <rect x="0" y="0" width="24" height="2"></rect>
				                </g>
				            </g>
				        </g>
				    </g>
				</svg>';
		}

		return $svg;
	}
}

if ( ! function_exists( ( 'ct_cele_add_meta_elements' ) ) ) {
	function ct_cele_add_meta_elements() {

		$meta_elements = '';

		$meta_elements .= sprintf( '<meta charset="%s" />' . "\n", esc_html( get_bloginfo( 'charset' ) ) );
		$meta_elements .= '<meta name="viewport" content="width=device-width, initial-scale=1" />' . "\n";

		echo $meta_elements;
	}
}
add_action( 'wp_head', 'ct_cele_add_meta_elements', 1 );

if ( ! function_exists( ( 'ct_cele_infinite_scroll_render' ) ) ) {
	function ct_cele_infinite_scroll_render() {
		while ( have_posts() ) {
			the_post();
			get_template_part( 'content', 'archive' );
		}
	}
}

if ( ! function_exists( 'ct_cele_get_content_template' ) ) {
	function ct_cele_get_content_template() {

		/* Blog */
		if ( is_home() ) {
			get_template_part( 'content', 'archive' );
		} /* Post */
		elseif ( is_singular( 'post' ) ) {
			get_template_part( 'content' );
		} /* Page */
		elseif ( is_page() ) {
			get_template_part( 'content', 'page' );
		} /* Attachment */
		elseif ( is_attachment() ) {
			get_template_part( 'content', 'attachment' );
		} /* Archive */
		elseif ( is_archive() ) {
			get_template_part( 'content', 'archive' );
		} /* Custom Post Type */
		else {
			get_template_part( 'content' );
		}
	}
}

// allow skype URIs to be used
if ( ! function_exists( 'ct_cele_allow_skype_protocol' ) ) {
	function ct_cele_allow_skype_protocol( $protocols ) {
		$protocols[] = 'skype';

		return $protocols;
	}
}
add_filter( 'kses_allowed_protocols' , 'ct_cele_allow_skype_protocol' );

// trigger theme switch on link click and send to Appearance menu
function ct_cele_welcome_redirect() {

	$welcome_url = add_query_arg(
		array(
			'page' => 'cele-options'
		),
		admin_url( 'themes.php' )
	);
	wp_redirect( esc_url( $welcome_url ) );
}
add_action( 'after_switch_theme', 'ct_cele_welcome_redirect' );