<?php 
 if ( ! defined( 'ABSPATH' ) ) exit;

 function progressbar_wp_front_script() {
		wp_enqueue_style('progress_wp_br_bootstrap', progress_bar_wp_directory_url.'assets/css/bootstrap.css');
	    wp_enqueue_style('progr_wp_b-font-awesome', progress_bar_wp_directory_url.'assets/css/font-awesome/css/font-awesome.min.css');
		wp_enqueue_style('progr_wp_jq-ae', progress_bar_wp_directory_url.'assets/css/meanmenu.min.css');
		wp_enqueue_style('progr_wp_animate-ae', progress_bar_wp_directory_url.'assets/css/animate.min.css');
		
		wp_enqueue_script('jquery');
	    wp_enqueue_script( 'progress_wp_br-home-js', progress_bar_wp_directory_url.'assets/js/jquery.meanmenu.js',array('jquery'), false, true);
	    wp_enqueue_script('pb_wp_wow-min-js',progress_bar_wp_directory_url.'assets/js/wow.min.js',array('jquery'), false, true);
        wp_enqueue_script( 'pbwp_scroll-js', progress_bar_wp_directory_url.'assets/js/jquery.scrollUp.min.js',array('jquery'), false, true);
		wp_enqueue_script('pbwp_way-min-js',progress_bar_wp_directory_url.'assets/js/waypoints.min.js',array('jquery'), false, true);		
	    wp_enqueue_script('pbwp_main-min-js',progress_bar_wp_directory_url.'assets/js/main.js',array('jquery'), false, true);
     }

 add_action('wp_enqueue_scripts', 'progressbar_wp_front_script');
 
add_action( 'admin_notices', 'dazz_pb_b_review' );
function dazz_pb_b_review() {

	// Verify that we can do a check for reviews.
	$review = get_option( 'dazz_pb_b_review' );
	$time	= time();
	$load	= false;
	if ( ! $review ) {
		$review = array(
			'time' 		=> $time,
			'dismissed' => false
		);
		add_option('dazz_pb_b_review', $review);
		//$load = true;
	} else {
		// Check if it has been dismissed or not.
		if ( (isset( $review['dismissed'] ) && ! $review['dismissed']) && (isset( $review['time'] ) && (($review['time'] + (DAY_IN_SECONDS * 2)) <= $time)) ) {
			$load = true;
		}
	}
	// If we cannot load, return early.
	if ( ! $load ) {
		return;
	}

	// We have a candidate! Output a review message.
	?>
	<div class="notice notice-info is-dismissible dazz-pb-b-review-notice">
		<div style="float:left;margin-right:10px;margin-bottom:5px;">
			<img style="width:100%;width: 150px;height: auto;" src="<?php echo progress_bar_wp_directory_url.'assets/images/pb.jpg'; ?>" />
		</div>
		<p style="font-size:18px;">'Hi! We saw you have been using <strong>Progress Bar plugin</strong> for a few days and wanted to ask for your help to <strong>make the plugin better</strong>.We just need a minute of your time to rate the plugin. Thank you!</p>
		<p style="font-size:18px;"><strong><?php _e( '~ Dazzlersoft', '' ); ?></strong></p>
		<p style="font-size:19px;"> 
			<a style="color: #fff;background: #ef4238;padding: 5px 7px 4px 6px;border-radius: 4px;" href="https://wordpress.org/support/plugin/progress-bar-wp/reviews/?filter=5#new-post" class="dazz-pb-b-dismiss-review-notice dazz-pb-b-review-out" target="_blank" rel="noopener">Rate the plugin</a>&nbsp; &nbsp;
			<a style="color: #fff;background: #27d63c;padding: 5px 7px 4px 6px;border-radius: 4px;" href="#"  class="dazz-pb-b-dismiss-review-notice dazz-rate-later" target="_self" rel="noopener"><?php _e( 'Nope, maybe later', '' ); ?></a>&nbsp; &nbsp;
			<a style="color: #fff;background: #31a3dd;padding: 5px 7px 4px 6px;border-radius: 4px;" href="#" class="dazz-pb-b-dismiss-review-notice dazz-rated" target="_self" rel="noopener"><?php _e( 'I already did', '' ); ?></a>
		</p>
	</div>
	<script type="text/javascript">
		jQuery(document).ready( function($) {
			$(document).on('click', '.dazz-pb-b-dismiss-review-notice, .dazz-pb-b-dismiss-notice .notice-dismiss', function( event ) {
				if ( $(this).hasClass('dazz-pb-b-review-out') ) {
					var dazz_rate_data_val = "1";
				}
				if ( $(this).hasClass('dazz-rate-later') ) {
					var dazz_rate_data_val =  "2";
					event.preventDefault();
				}
				if ( $(this).hasClass('dazz-rated') ) {
					var dazz_rate_data_val =  "3";
					event.preventDefault();
				}

				$.post( ajaxurl, {
					action: 'dazz_pb_b_dismiss_review',
					dazz_rate_data_pb_b : dazz_rate_data_val
				});
				
				$('.dazz-pb-b-review-notice').hide();
				//location.reload();
			});
		});
	</script>
	<?php
}

add_action( 'wp_ajax_dazz_pb_b_dismiss_review', 'dazz_pb_b_dismiss_review' );
function dazz_pb_b_dismiss_review() {
	if ( ! $review ) {
		$review = array();
	}
	
	if($_POST['dazz_rate_data_pb_b']=="1"){
		
	}
	if($_POST['dazz_rate_data_pb_b']=="2"){
		$review['time'] 	 = time();
		$review['dismissed'] = false;
		update_option( 'dazz_pb_b_review', $review );
	}
	if($_POST['dazz_rate_data_pb_b']=="3"){
		$review['time'] 	 = time();
		$review['dismissed'] = true;
		update_option( 'dazz_pb_b_review', $review );
	}
	die;
}
?>