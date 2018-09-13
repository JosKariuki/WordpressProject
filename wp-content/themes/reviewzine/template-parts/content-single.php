<?php
/**
 * Template part for displaying single posts.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package islemag
 */

	$post_id = get_the_ID();
?>

		  <div class="row">
			<div class="col-md-12">

				<article id="post-<?php echo $post_id; ?>" <?php post_class( 'entry single' ); ?>>
					<?php
					$islemag_single_post_hide_thumbnail = get_theme_mod( 'islemag_single_post_hide_thumbnail','1' );
					if ( ! isset( $islemag_single_post_hide_thumbnail ) || $islemag_single_post_hide_thumbnail != '1' ) {
						if ( has_post_thumbnail() ) { ?>
							<div class="entry-media">
							  <figure>
								<?php the_post_thumbnail(); ?>
							  </figure>
							</div><!-- End .entry-media -->
						<?php
						}
					} else {
						if ( is_customize_preview() ) {
							if ( has_post_thumbnail() ) { ?>
								<div class="entry-media islemag_only_customizer">
								  <figure>
									<?php the_post_thumbnail(); ?>
								  </figure>
								</div><!-- End .entry-media -->
							<?php
							}
						}
					} ?>
				  
				  <div class="entry-date"><div><?php echo get_the_date( 'd' ); ?><span><?php echo get_the_date( 'F' ); ?></span></div></div>
					<?php
					$id = get_the_ID();
					$format = get_post_format( $id );
					switch ( $format ) {
						case 'aside':
							$icon_class = 'fa-file-text';
						break;
						case 'chat':
							$icon_class = 'fa-comment';
						break;
						case 'gallery':
							$icon_class = 'fa-file-image-o';
						break;
						case 'link':
							$icon_class = 'fa-link';
						break;
						case 'image':
							$icon_class = 'fa-picture-o';
						break;
						case 'quote':
							$icon_class = 'fa-quote-right';
						break;
						case 'status':
							$icon_class = 'fa-line-chart';
						break;
						case 'video':
							$icon_class = 'fa-video-camera';
						break;
						case 'audio':
							$icon_class = 'fa-headphones';
						break;
					}
					if ( ! empty( $icon_class ) ) {  ?>
					  <span class="entry-format"><i class="fa <?php echo esc_attr( $icon_class ); ?>"></i></span>
					<?php
					} ?>
					<?php the_title( '<h2 class="entry-title">', '</h2>' ); ?>

				  <div class="entry-content">
						<?php the_content( __( 'Continue Reading','reviewzine' ) ); ?>
						<?php
				  			wp_link_pages( array(
				  				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'reviewzine' ),
				  				'after'  => '</div>',
				  			) );
				  		?>
				  </div><!-- End .entry-content -->

				  <footer class="entry-footer clearfix">
					<?php
					  $category = get_the_category(); ?>
					  <span class="entry-cats">
						  <span class="entry-label">
							  <i class="fa fa-tag"></i> <?php esc_html_e( 'Categories:','reviewzine' ); ?>
						  </span>
							<?php
							if ( ! empty( $category ) ) {
								$i = 0;
								$len = count( $category );
								foreach ( $category as $cat ) {
									echo '<a href="' . esc_url( get_category_link( $cat->cat_ID ) ) . '">' . esc_attr( $cat->cat_name ) . '</a>';
									if ( $i != $len - 1 ) {
										echo ', ';
									}
									$i++;
								}
							} ?>
					  </span><!-- End .entry-tags -->
					  <span class="entry-separator">|</span>
					  <a href="#" class="entry-comments"><i class="fa fa-comment-o"></i> <?php comments_number( '0', '1', '%' ); ?></a>
					  <span class="entry-separator">|</span>
					  <a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" class="entry-author"><i class="fa fa-user"></i> <?php the_author(); ?></a>
					</footer>

					<?php $islemag_single_post_hide_author = get_theme_mod( 'islemag_single_post_hide_author' ); ?>
				  <div class="about-author clearfix <?php if ( $islemag_single_post_hide_author == true ) { echo 'islemag_hide';} ?>">
						<?php
						  $author_id = get_the_author_meta( 'ID' );
						  $profile_pic = get_avatar( $author_id, 'islemag_sections_small_thumbnail' );
						if ( ! empty( $profile_pic ) ) {  ?>
							<figure class="pull-left">
								<?php echo $profile_pic; ?>
							</figure>
						<?php } ?>
					  <h3 class="title-underblock custom">
						<a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>"><?php the_author(); ?></a>
						<span><?php _e( 'Author', 'reviewzine' ); ?></span>
					  </h3>
					  <div class="author-content">
							<?php echo get_the_author_meta( 'description', $author_id ); ?>
					  </div><!-- End .athor-content -->
				  </div><!-- End .about-author -->
				</article>

				<?php $islemag_single_post_hide_related_posts = get_theme_mod( 'islemag_single_post_hide_related_posts' ); ?>
				<h3 class="mb30 title-underblock custom blog-related-carousel-title <?php if ( $islemag_single_post_hide_related_posts == true ) { echo 'islemag_hide';} ?> "><span><?php esc_html_e( 'Related Posts', 'reviewzine' ); ?></span></h3>
				<div class="blog-related-carousel owl-carousel small-nav <?php if ( $islemag_single_post_hide_related_posts == true ) { echo 'islemag_hide';} ?> ">
				<?php
				  $related = get_posts( array(
					  'category__in' => wp_get_post_categories( $post_id ),
					  'numberposts' => 5,
					  'post__not_in' => array( $post_id ),
				  ) );
				  if ( $related ) {
					  foreach ( $related as $post ) {
						  setup_postdata( $post ); ?>
						<article class="entry entry-overlay entry-block">
						  <div class="entry-media">
							<figure>
							  <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
									<?php
									$thumb_id = get_post_thumbnail_id( $wp_query->ID );
									$thumb_meta = wp_get_attachment_metadata( $thumb_id );
									if ( ! empty( $thumb_id ) ) {
										if ( $thumb_meta['width'] / $thumb_meta['height'] > 1 ) {
											$thumb = wp_get_attachment_image_src( $thumb_id, 'islemag_section4_big_thumbnail' );
											$url = $thumb['0'];
										} else {
											$thumb = wp_get_attachment_image_src( $thumb_id, 'islemag_section4_big_thumbnail_no_crop' );
											$url = $thumb['0'];
										}
										echo '<img class="owl-lazy" data-src="' . esc_url( $url ) . '" />';
									} else {
										echo '<img class="owl-lazy" data-src="' . get_template_directory_uri() . '/img/placeholder-image.png" />';
									}
									?>
							  </a>
							</figure> <!-- End figure -->
						  </div> <!-- End .entry-media -->

						  <h3 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
						  <div class="entry-meta">
							  <span class="entry-overlay-date"><i class="fa fa-calendar-o"></i><?php echo get_the_date( 'j F Y' ); ?></span>
							  <span class="entry-separator">|</span>
							  <a href="<?php the_permalink(); ?>" class="entry-comments"><i class="fa fa-comment-o"></i><?php comments_number( '0', '1', '%' ); ?></a>
						  </div>
							<?php
							if ( function_exists( 'cwppos_calc_overall_rating' ) ) {
								$rating = cwppos_calc_overall_rating( get_the_ID() );
								if ( ! empty( $rating['option1'] ) ) {  ?>
									<div class="star-ratings-css">
									  <div class="star-ratings-css-top" style="width: <?php echo esc_attr( $rating['overall'] ); ?>%">
										<span><i class="fa fa-star"></i></span>
										<span><i class="fa fa-star"></i></span>
										<span><i class="fa fa-star"></i></span>
										<span><i class="fa fa-star"></i></span>
										<span><i class="fa fa-star"></i></span>
									  </div>
									  <div class="star-ratings-css-bottom">
										<span><i class="fa fa-star-o"></i></span>
										<span><i class="fa fa-star-o"></i></span>
										<span><i class="fa fa-star-o"></i></span>
										<span><i class="fa fa-star-o"></i></span>
										<span><i class="fa fa-star-o"></i></span>
									  </div>
									</div>
								<?php }
							} ?>
						</article> <!-- End .entry-overlay -->
						<?php }// End foreach().
					}// End if().
					wp_reset_postdata(); ?>
				</div><!-- End .blog-related-carousel -->
			</div><!-- End .col-md-12 -->
		  </div><!-- End .row -->
		  <div class="mb20"></div><!-- space -->
