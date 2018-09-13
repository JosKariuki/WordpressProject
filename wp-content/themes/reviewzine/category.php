<?php
/**
 * The template for displaying archive pages.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package islemag
 */

get_header(); ?>
<div class="container">
	<div class="row">
		<div class="islemag-content-left col-md-8">
			<?php if ( have_posts() ) : ?>
						<header class="page-header">
							<h1><span>
							<?php the_archive_title(); ?>
							</span></h1>
							<?php
								the_archive_description( '<div class="taxonomy-description">', '</div>' );
							?>
						</header><!-- .page-header -->

						<?php

						while ( have_posts() ) : the_post();

							get_template_part( 'template-parts/content', get_post_format() );

						endwhile;

						echo '<div class="reviewzine-pagination">';
							the_posts_pagination( array(
								'prev_next' => false,
							) );
						echo '</div>';

					else :

						get_template_part( 'template-parts/content', 'none' );

					endif;
			?>
		</div><!-- End .islemag-content-left -->
		<?php get_sidebar(); ?>
	
	</div><!-- End .row -->
</div><!-- End .container -->



<?php get_footer(); ?>
