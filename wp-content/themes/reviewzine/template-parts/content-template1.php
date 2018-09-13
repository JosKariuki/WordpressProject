<?php
/**
 * First template
 *
 * @package reviewzine
 */

$wp_query = new WP_Query(
	array(
		  'posts_per_page'        => $islemag_section_max_posts,
		  'order'                 => 'DESC',
		  'post_status'           => 'publish',
		  'ignore_sticky_posts'   => true,
		  'no_found_rows'       => true,
		  'category_name'         => ( ! empty( $islemag_section_category ) && $islemag_section_category != 'all' ? $islemag_section_category : ''),
	  )
);

if ( $wp_query->have_posts() ) : ?>
	<div class="post-section islemag-template1">

	<div class="owl-carousel islemag-template1-posts smaller-nav no-radius">
		<?php

		while ( $wp_query->have_posts() ) : $wp_query->the_post();
			$category = get_the_category();
			$postid = get_the_ID();
		?>

		  <article class="entry entry-overlay entry-block">
			<a href="<?php echo esc_url( get_category_link( $category[0]->cat_ID ) );?>" class="category-block" title="Category <?php echo esc_attr( $category[0]->cat_name ); ?>"><?php echo esc_attr( $category[0]->cat_name ); ?></a>
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
				$rating = cwppos_calc_overall_rating( $postid );
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
				<?php
				}
			} ?>
		  </article> <!-- End .entry-overlay -->
		<?php
		  endwhile;
		?>
	</div> <!-- End .islemag-template1-posts -->
	</div> <!-- End .islemag-template1 -->
<?php
	endif;
	wp_reset_postdata();
?>
