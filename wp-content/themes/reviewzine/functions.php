<?php
/**
 * Main functions file
 *
 * @package reviewzine
 */

/**
 * Enqueue fonts
 */
function reviewzine_fonts_url() {
	$fonts_url = '';

	/*
     Translators: If there are characters in your language that are not
    * supported by Lora, translate this to 'off'. Do not translate
    * into your own language.
    */
	$lato = _x( 'on', 'Lato font: on or off', 'reviewzine' );
	$hind = _x( 'on', 'Hind font: on or off', 'reviewzine' );

	if ( 'off' !== $lato || 'off' !== $hind ) {
		$font_families = array();
		if ( 'off' !== $lato ) {
			$font_families[] = 'Lato:400,500,600,700';
		}
		if ( 'off' !== $hind ) {
			$font_families[] = 'Hind:400,600,700';
		}
		$query_args = array(
			'family' => urlencode( implode( '|', $font_families ) ),
			'subset' => urlencode( 'latin,latin-ext' ),
		);
		$fonts_url = add_query_arg( $query_args, '//fonts.googleapis.com/css' );
	}

	return $fonts_url;
}

/**
 * Enqueue admin style
 */
function reviewzine_admin_add_editor_styles() {
	add_editor_style( 'css/editor_style.css' );
}
add_action( 'admin_init', 'reviewzine_admin_add_editor_styles' );

/**
 * Enqueue the fonts from the child theme
 */
function reviewzine_scripts_styles() {
	wp_dequeue_style( 'islemag-fonts' );
	wp_enqueue_style( 'reviewzine-fonts', reviewzine_fonts_url(), array(), null );
}
add_action( 'wp_enqueue_scripts', 'reviewzine_scripts_styles', 12 );

/**
 * Enqueue the scripts and styles
 */
function reviewzine_scripts() {
	wp_enqueue_style( 'reviewzine-islemag-style', get_template_directory_uri() . '/style.css' );

	wp_enqueue_style( 'reviewzine-style', get_stylesheet_uri() );

	if ( 'page' == get_option( 'show_on_front' ) && is_front_page() ) {
		wp_enqueue_script( 'reviewzine-script-index', get_stylesheet_directory_uri() . '/js/functions.js', array( 'jquery', 'islemag-script-index' ), '1.0.0', true );
	}
}

add_action( 'wp_enqueue_scripts', 'reviewzine_scripts', 20 );

/**
 * Filter the default color for titles
 */
function reviewzine_filter_the_default_title_color() {
	return '#1e3046';
}

add_filter( 'islemag_title_color_default_filter', 'reviewzine_filter_the_default_title_color' );

/**
 * Filter the default color for header text
 */
function reviewzine_filter_the_default_header_textcolor() {
	return '#1e3046';
}

add_filter( 'islemag_header_textcolor_default_filter', 'reviewzine_filter_the_default_header_textcolor' );

/**
 * Filter the default color for sections post titles
 */
function reviewzine_filter_the_default_sections_post_title_color() {
	return '#1e3046';
}

add_filter( 'islemag_sections_post_title_color_default_filter', 'reviewzine_filter_the_default_sections_post_title_color' );

/**
 * Filter the default color for sections post text
 */
function reviewzine_filter_the_default_sections_post_text_color() {
	return '#8d8d8d';
}

add_filter( 'islemag_sections_post_text_color_default_filter', 'reviewzine_filter_the_default_sections_post_text_color' );

require_once get_stylesheet_directory() . '/class-tgm-plugin-activation.php';

add_action( 'tgmpa_register', 'reviewzine_register_required_plugins' );

/**
 * Required plugins with TGMPA
 */
function reviewzine_register_required_plugins() {
	/*
     * Array of plugin arrays. Required keys are name and slug.
     * If the source is NOT from the .org repo, then source is also required.
     */
	$plugins = array(

		array(
			'name'      => __( 'WP Product Review','reviewzine' ),
			'slug'      => 'wp-product-review',
			'required'  => false,
		),

	);

	/*
     * Array of configuration settings. Amend each line as needed.
     *
     * TGMPA will start providing localized text strings soon. If you already have translations of our standard
     * strings available, please help us make TGMPA even better by giving us access to these translations or by
     * sending in a pull-request with .po file(s) with the translations.
     *
     * Only uncomment the strings in the config array if you want to customize the strings.
     */
	$config = array(
		'id'           => 'reviewzine-tgmpa',       // Unique ID for hashing notices for multiple instances of TGMPA.
		'default_path' => '',                       // Default absolute path to bundled plugins.
		'menu'         => 'tgmpa-install-plugins',  // Menu slug.
		'has_notices'  => true,                     // Show admin notices or not.
		'dismissable'  => true,                     // If false, a user cannot dismiss the nag message.
		'dismiss_msg'  => '',                       // If 'dismissable' is false, this message will be output at top of nag.
		'is_automatic' => false,                    // Automatically activate plugins after installation or not.
		'message'      => '',                       // Message to output right before the plugins table.
	);

	tgmpa( $plugins, $config );
}

/**
 * Change container row
 */
function reviewzine_container_row() {
	?>
	<div class="container">
		<div class="row">
	<?php

}

/**
 * Change container row - end
 */
function reviewzine_container_row_close() {
	?>
		</div>
	</div>
	<?php

}

add_action( 'islemag_navbar_top_head', 'reviewzine_container_row' );

add_action( 'islemag_navbar_top_bottom', 'reviewzine_container_row_close' );

add_action( 'islemag_header_content_head', 'reviewzine_container_row' );

add_action( 'islemag_header_content_bottom', 'reviewzine_container_row_close' );

add_action( 'islemag_footer_container_head', 'reviewzine_container_row' );

add_action( 'islemag_footer_container_bottom', 'reviewzine_container_row_close' );

/**
 * Filter the navbar top classes
 *
 * @param string $classes The already existing classes.
 *
 * @return array|string
 */
function reviewzine_navbar_top_classes( $classes ) {
	if ( is_array( $classes ) ) {
		return array_diff( $classes, array( 'container-fluid' ) );
	}
	return '';
}

add_filter( 'islemag_navbar_top_classes', 'reviewzine_navbar_top_classes' );

/**
 * Remove classes
 */
function reviewzine_no_class_filter() {
	return '';
}

add_filter( 'islemag_wrapper_class', 'reviewzine_no_class_filter' );
add_filter( 'islemag_content_ids', 'reviewzine_no_class_filter' );
add_filter( 'islemag_line_color', 'reviewzine_no_class_filter' );

/**
 * Add container
 */
function reviewzine_container() {
	?>
	<div class="container">
	<?php

}

/**
 * Close container
 */
function reviewzine_container_close() {
	?>
	</div>
	<?php

}

add_action( 'islemag_main_nav_before', 'reviewzine_container' );

add_action( 'islemag_main_nav_after', 'reviewzine_container_close' );

/**
 * Reorganize the footer content
 */
function reviewzine_footer_content() {
	remove_action( 'islemag_footer_content', 'islemag_footer' ); ?>
	<div class="col-md-4">
		<?php printf(
			/* translators: 1 - Theme name , 2 - WordPress link */
			__( '%1$s powered by %2$s', 'reviewzine' ),
			sprintf( '<a href="https://themeisle.com/themes/islemag/" rel="nofollow">%s</a>', esc_html__( 'ReviewZine', 'reviewzine' ) ),
			sprintf( '<a href="http://wordpress.org/" rel="nofollow">%s</a>', esc_html__( 'WordPress', 'reviewzine' ) )
		); ?>
	</div><!-- End .col-md-6 -->
	<div class="col-md-8">
		<?php

		$defaults = array(
			'theme_location'  => 'islemag-footer',
			'fallback_cb'     => false,
			'items_wrap'      => '<ul class="footer-menu" id="%1$s" class="%2$s">%3$s</ul>',
			'depth'           => 1,
		);

		wp_nav_menu( $defaults ); ?>
	</div><!-- End .col-md-6 -->
	<?php

}

add_action( 'islemag_footer_content', 'reviewzine_footer_content', 9 );

/**
 * Redo the navigation
 */
function reviewzine_the_post_navigation() {
	?>
	<div class="reviewzine-pagination">
		<?php
		the_posts_pagination( array(
			'prev_next' => false,
		) );
		?>
	</div>
	<?php

}

add_filter( 'islemag_post_navigation_filter', 'reviewzine_the_post_navigation' );

/**
 * Filter the classes on archive page
 *
 * @param string $classes The already existing classes.
 */
function reviewzine_archive_content_classes( $classes ) {
	if ( is_array( $classes ) ) {
		$classes[] = 'col-md-8';
		return array_diff( $classes, array( 'col-md-9' ) );
	}
	return '';
}
add_filter( 'islemag_archive_content_classes', 'reviewzine_archive_content_classes', 9 );

/**
 * Filter the classes on main content
 *
 * @param string $classes The already existing classes.
 */
function reviewzine_content_classes( $classes ) {
	if ( is_array( $classes ) ) {
		$classes[] = 'container';
		return $classes;
	}
	return '';
}
add_filter( 'islemag_content_classes', 'reviewzine_content_classes' );

/**
 * Change the title of the comments section
 */
function reviewzine_comments_title() {
	remove_action( 'islemag_comments_title', 'islemag_comments_heading' ); ?>
	<span><?php esc_html_e( 'Comments', 'reviewzine' ); ?></span>
	<?php

}
add_action( 'islemag_comments_title', 'reviewzine_comments_title', 9 );

/**
 * Change the content of the comments section
 *
 * @param array   $args The arguments.
 * @param string  $comment The comments.
 * @param integer $depth The depth of the comments.
 * @param string  $add_below are for the JavaScript addComment.moveForm() method parameters.
 */
function reviewzine_comment_content( $args, $comment, $depth, $add_below ) {
	remove_action( 'islemag_comment_content', 'islemag_comment_action' ); ?>
	<div class="media">
		<div class="media-left">
			<figure class="author-avatar">
				<?php
				if ( $args['avatar_size'] != 0 ) {
					echo get_avatar( $comment, 52, '', '', array(
						'class' => 'media-object',
					) );
				} ?>
			</figure>
		</div>
		<div class="media-body">
			<div class="comment-author vcard">
				<?php
				/* translators: %s - the comment authors link */
				printf( __( '<h4 class="media-heading">%s</h4>', 'reviewzine' ), get_comment_author_link() ); ?>
				<div class="reply pull-right reply-link"> <?php comment_reply_link( array_merge( $args, array(
					'add_below' => $add_below,
					'depth' => $depth,
					'max_depth' => $args['max_depth'],
				) ) ); ?> </div>
				<div class="comment-extra-info">
					<?php
					/* translators: 1 - the comment date, 2 - the comment title */
					printf( __( '<span class="comment-date">(%1$s - %2$s)</span>', 'reviewzine' ), get_comment_date(), get_comment_time() ); ?>
					<?php edit_comment_link( __( '(Edit)', 'reviewzine' ), '  ', '' ); ?>
				</div>
			</div>
			<?php if ( $comment->comment_approved == '0' ) : ?>
				<em class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'reviewzine' ); ?></em>
				<br />
			<?php endif; ?>
			<div class="media-body">
				<?php comment_text(); ?>
			</div>
		</div>
	</div>
	<?php

}
add_action( 'islemag_comment_content', 'reviewzine_comment_content', 9, 5 );

/**
 * Filter the comments args title_reply_before and title_reply_after.
 *
 * @param array $args The arguments.
 */
function reviewzine_comments_args( $args ) {
	if ( is_array( $args ) ) {
		$args['title_reply_before'] = '<h3 id="reply-title" class="comment-reply-title"><span>';
		$args['title_reply_after'] = '</span></h3>';
		return $args;
	}
	return '';
}
add_filter( 'islemag_comments_args', 'reviewzine_comments_args' );

/**
 * Filter sidebar classes
 *
 * @param string $classes The already existing classses.
 */
function islemag_sidebar_classes( $classes ) {
	if ( is_array( $classes ) ) {
		$classes[] = 'col-md-4';
		return array_diff( $classes, array( 'col-md-3' ) );
	}
	return '';
}
add_filter( 'islemag_sidebar_classes', 'islemag_sidebar_classes' );

/**
 * Remove meta information for the categories, tags and comments from the parent theme
 */
function reviewzine_entry_footer() {
	remove_action( 'islemag_entry_footer', 'islemag_entry_footer' );
}
add_action( 'islemag_entry_footer', 'reviewzine_entry_footer', 9 );

/**
 * Filter the date format
 */
function reviewzine_date_format() {
	return _x( 'F','month date format','reviewzine' );
}
add_filter( 'islemag_date_format', 'reviewzine_date_format' );

/**
 * Change the date entry
 */
function reviewzine_entry_date() {
	remove_action( 'islemag_entry_date', 'islemag_post_entry_date' );
	$date_format = apply_filters( 'islemag_date_format', 'F' ); ?>
	<div class="entry-date"><div><?php echo get_the_date( 'd' ); ?><span><?php echo strtoupper( get_the_date( $date_format ) ); ?></span></div></div>
	<?php

}
add_action( 'islemag_entry_date', 'reviewzine_entry_date', 9 );

/**
 * Remove the colors from the slider posts
 */
function reviewzine_remove_colors_from_slider_posts() {
	return '';
}
add_filter( 'islemag_slider_posts_colors', 'reviewzine_remove_colors_from_slider_posts' );

/**
 * Wrap a div at the top of the slider posts - close
 */
function reviewzine_add_content_at_the_bottom_of_slider_posts() {
	?>
	</div>
	<div class="extra-info">
		<?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
		<?php
		if ( function_exists( 'cwppos_calc_overall_rating' ) ) {
			$rating = cwppos_calc_overall_rating( get_the_ID() );
			if ( ! empty( $rating['option1'] ) ) {
				?>
				<div class="star-ratings-css">
					<div class="star-ratings-css-top" style="width: <?php echo esc_attr( $rating['overall'] ); ?>%"><span><i class="fa fa-star"></i></span><span><i class="fa fa-star"></i></span><span><i class="fa fa-star"></i></span><span><i class="fa fa-star"></i></span><span><i class="fa fa-star"></i></span></div>
					<div class="star-ratings-css-bottom"><span><i class="fa fa-star-o"></i></span><span><i class="fa fa-star-o"></i></span><span><i class="fa fa-star-o"></i></span><span><i class="fa fa-star-o"></i></span><span><i class="fa fa-star-o"></i></span></div>
				</div>
			<?php
			}
		} ?>
	</div>
	<?php

}
add_action( 'islemag_bottom_slider_posts', 'reviewzine_add_content_at_the_bottom_of_slider_posts' );

/**
 * Wrap a div at the top of the slider posts
 */
function reviewzine_add_content_at_the_top_of_slider_posts() {
	?>
	<div class="entry-holder">
	<?php

}
add_action( 'islemag_top_slider_posts', 'reviewzine_add_content_at_the_top_of_slider_posts' );

/**
 * Hide the default title on the slider posts from the parent theme ( to move it in other place in the child theme )
 */
function reviewzine_hide_default_title_on_slider_posts() {
	return false;
}
add_filter( 'islemag_filter_article_title_on_slider_posts', 'reviewzine_hide_default_title_on_slider_posts' );

add_filter( 'get_the_archive_title', 'reviewzine_archive_title' );

/**
 * Filter the archive title
 */
function reviewzine_archive_title( $title ) {

	if ( is_category() ) {
		$title = single_cat_title( '', false );
		return __( 'Category', 'reviewzine' ) . '<span class="category-name">' . esc_html( $title ) . '</span>';
	}

	return $title;
};

/**
 * Change path to child theme for preview images
 */
function islemag_prevdem_home_filter_callback() {
	return get_stylesheet_directory() . '/ti-prevdem/img/';
}

/**
 * Change theme uri to child theme for preview images
 */
function islemag_prevdem_home_uri_filter_callback() {
	return get_stylesheet_directory_uri() . '/ti-prevdem/img/';
}

/**
 * Change default top banner
 */
function islemag_default_top_banner_callback() {
	return get_stylesheet_directory_uri() . '/images/728x90-reviewzine.jpg';
}

/**
 * Change default sidebar banners
 */
function islemag_default_sidebar_banner_callback() {
	return get_stylesheet_directory_uri() . '/images/125x125-reviewzine.jpg';
}

/**
 * After setup theme function
 */
function reviewzine_starter_content() {

	/* preview demo */
	add_filter( 'islemag_prevdem_home_filter', 'islemag_prevdem_home_filter_callback' );
	add_filter( 'islemag_prevdem_home_uri_filter', 'islemag_prevdem_home_uri_filter_callback' );

	/* change default banners */
	add_filter( 'islemag_default_top_banner_filter', 'islemag_default_top_banner_callback' );
	add_filter( 'islemag_default_sidebar_banner_filter', 'islemag_default_sidebar_banner_callback' );

	/*
	 * Starter Content Support
	 */
	add_theme_support( 'starter-content', array(

		'posts' => array(
			'home',
			'blog',
		),
		'nav_menus' => array(
			'primary'      => array(
				'name'  => __( 'Primary Menu', 'reviewzine' ),
				'items' => array(
					'page_home',
					'page_blog',
				),
			),
		),
		'options' => array(
			'show_on_front'  => 'page',
			'page_on_front'  => '{{home}}',
			'page_for_posts' => '{{blog}}',
		),
	) );
}
add_action( 'after_setup_theme', 'reviewzine_starter_content' );
