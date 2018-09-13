<?php
/**
 * Second template
 *
 * @package reviewzine
 */

$wp_query = new WP_Query(
	array(
		  'posts_per_page' => $islemag_section_max_posts,
		  'order' => 'DESC',
		  'ignore_sticky_posts' => true,
		  'no_found_rows'       => true,
		  'category_name' => ( $islemag_section_category != 'all' ? $islemag_section_category : '' ),
		)
);

if ( $wp_query->have_posts() ) : ?>
	<div class="post-section islemag-template2">
	<div class="row">

	<?php
	while ( $wp_query->have_posts() ) : $wp_query->the_post();
		$category = get_the_category();
		$postid = get_the_ID();
	?>

	<div class="col-sm-6">
	<article class="entry entry-overlay entry-block eb-small">
	  <div class="entry-media">
		<a href="<?php echo esc_url( get_category_link( $category[0]->cat_ID ) ); ?>" class="category-block" title="Category <?php echo esc_attr( $category[0]->cat_name ); ?>"><?php echo esc_attr( $category[0]->cat_name ); ?></a>
		  <figure>
			  <a href="<?php the_permalink(); ?>" title="<?php echo get_the_title(); ?>">
					<?php
					$thumb_id = get_post_thumbnail_id( $postid );
					$thumb_meta = wp_get_attachment_metadata( $thumb_id );
					if ( ! empty( $thumb_id ) ) {
						if ( $thumb_meta['width'] / $thumb_meta['height'] > 1 || $thumb_meta['height'] / $thumb_meta['width'] > 1 ) {
							$thumb = the_post_thumbnail( 'islemag_sections_small_thumbnail' );
						} else {
							$thumb = the_post_thumbnail( 'islemag_sections_small_thumbnail_no_crop' );
						}
					} else {
						  echo '<img src="' . get_template_directory_uri() . '/img/placeholder-image.png" />';
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
			</div> <!-- End .entry-meta -->
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
				<?php }
			} ?>
		  </article> <!-- End .entry-block -->
		</div> <!-- End .col-sm-6 -->
	<?php
	  endwhile;
		?>

	</div> <!-- End .row -->
	</div> <!-- End .post-section -->
<?php endif;
wp_reset_postdata(); ?>
