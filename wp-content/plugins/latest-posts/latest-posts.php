<?php
/*
Plugin Name: Latest Posts
Plugin URI: https://shapedplugin.com/plugin/latest-posts-pro/
Description: Latest Posts widget to display recent posts.
Author: ShapedPlugin
Author URI: http://shapedplugin.com
Version: 1.4
License: GPL2
*/

// Don't call the file directly
if ( !defined( 'ABSPATH' ) ) exit;

/* Define */
define( 'SP_LATEST_POSTS_URL', plugins_url('/') . plugin_basename( dirname( __FILE__ ) ) . '/' );
define( 'SP_LATEST_POSTS_PATH', plugin_dir_path( __FILE__ ) );

//Thumb size
function st_thumb_setup() {
	// image size
	add_image_size('xs-thumb', 64, 64, TRUE);
}
add_action('after_setup_theme', 'st_thumb_setup');

// Cat list for backend
if ( ! function_exists( 'latest_posts_cat_list' ) ) {
	function latest_posts_cat_list(){
		$cat_lists = get_categories();
		$all_cat_list = array(''=>'All Category');
		foreach($cat_lists as $cat_list){
			$all_cat_list[$cat_list->cat_name] = $cat_list->slug;
		}
		return $all_cat_list;
	}
}


class ST_Latest_Posts_Widget extends WP_Widget{

	public function __construct() {
		parent::__construct( 'st_latest_posts_widget','Latest Posts Widget',
			array('description' => 'Latest posts widget to display recent posts'));

		add_action( 'wp_enqueue_scripts', array( $this, 'st_register_widget_styles' ) );
	}


	public function st_register_widget_styles() {
		wp_enqueue_style( 'latest-posts-style', SP_LATEST_POSTS_URL . 'assets/css/style.css' );
	}


	public function widget($args, $instance) {
		extract($args);

		$title 			= apply_filters('widget_title', $instance['title'] );
		$count 			= $instance['count'];
		$category 		= $instance['category'];
		
		echo $before_widget;

		$output = '';

		if ( $title )
			echo $before_title . $title . $after_title;

		global $post;

		if ( isset( $category ) && $category != '' ) {
			$args = array(
				'category_name'  => $category,
				'posts_per_page' => $count,
			);

		} else {
			$args = array(
				'posts_per_page' => $count,
			);
		}


		$posts = get_posts( $args );

		if(count($posts)>0){
			$output .='<div class="sp-latest-posts-widget latest-posts">';

			foreach ($posts as $post): setup_postdata($post);
				$output .='<div class="media">';

					if(has_post_thumbnail()):
						$output .='<div class="pull-left">';
						$output .='<a href="'.get_permalink().'">'.get_the_post_thumbnail($post->ID, 'xs-thumb', array('class' => 'img-responsive')).'</a>';
						$output .='</div>';
					endif;

					$output .='<div class="media-body">';
					$output .= '<h3 class="entry-title"><a href="'.get_permalink().'">'. get_the_title() .'</a></h3>';
					$output .= '<div class="entry-meta small"><span class="st-lp-time">'. get_the_time() . '</span> <span clss="st-lp-date">' . get_the_date('d M Y') . '</span></div>';
					$output .='</div>';

				$output .='</div>';
			endforeach;

			wp_reset_query();

			$output .='</div>';
		}


		echo $output;

		echo $after_widget;
	}


	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] 			= strip_tags( $new_instance['title'] );
		$instance['category'] 		= strip_tags( $new_instance['category'] );
		$instance['count'] 			= strip_tags( $new_instance['count'] );

		return $instance;
	}


	public function form($instance) {
		$defaults = array( 
			'title' 	=> 'Latest Posts',
			'count' 	=> 5
		);
		$instance = wp_parse_args( (array) $instance, $defaults );
	?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>">Widget Title:</label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:100%;" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'category' ); ?>"><?php _e( 'Category:', 'shaped_plugin' ); ?></label>
			<?php $options =
				latest_posts_cat_list()
			;
			if ( isset( $instance['category'] ) ) {
				$category = $instance['category'];
			}
			?>
			<select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'category' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'category' ) ); ?>">
				<?php $op = '<option value="%s"%s>%s</option>';

				foreach ( $options as $key => $value ) {

					if ( $category === $key ) {
						printf( $op, $key, ' selected="selected"', $value );
					} else {
						printf( $op, $key, '', $value );
					}
				} ?>
			</select>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'count' ); ?>">Count</label>
			<input id="<?php echo $this->get_field_id( 'count' ); ?>" name="<?php echo $this->get_field_name( 'count' ); ?>" value="<?php echo $instance['count']; ?>" style="width:100%;" />
		</p>

	<?php
	}
}


//Register Widget
if (!function_exists('register_shapedtheme_latest_posts_widget')) {
	function register_shapedtheme_latest_posts_widget() {
		register_widget( 'ST_Latest_Posts_Widget' );
	}

	add_action( 'widgets_init', 'register_shapedtheme_latest_posts_widget' );
}

/* Plugin Action Links */
function sp_latest_posts_action_links( $links ) {
	$links[] = '<a target="_blank" href="https://shapedplugin.com/plugin/latest-posts-pro/" style="color: red; font-weight: 600;">Go
Pro!</a>';

	return $links;
}
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'sp_latest_posts_action_links' );