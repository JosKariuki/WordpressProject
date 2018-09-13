<?php
 if ( ! defined( 'ABSPATH' ) ) exit;
class progressbar_wp {
	private static $instance;
    public static function forge() {
        if (!isset(self::$instance)) {
            $className = __CLASS__;
            self::$instance = new $className;
        }
        return self::$instance;
    }
	
	private function __construct() {
		add_action('admin_enqueue_scripts', array(&$this, 'progressbar_wp_admin_scripts'));
       // add_action('wp_enqueue_scripts', array(&$this, 'wpsm_progressbar_front_script'));
      
		if (is_admin()) {
			add_action('init', array(&$this, 'progressbar_wp_register_cpt'), 1);
			add_action('add_meta_boxes', array(&$this, 'progressbar_wp_meta_boxes_group'));
			add_action('admin_init', array(&$this, 'progressbar_wp_meta_boxes_group'), 1);
			add_action('save_post', array(&$this, 'add_progressbar_wp_meta_box_save'), 9, 1);
		    add_action('save_post', array(&$this, 'progressbar_wp_settings_meta_box_save'), 9, 1);
		
		}
    }
	
	// admin scripts
	public function progressbar_wp_admin_scripts(){
		if(get_post_type()=="progressbar_wp_r"){
			require_once('admin-scripts.php');
		}
	}
	// front end script
/**	function wpsm_progressbar_front_script() {
		wp_enqueue_style('wpsm_progress_br_bootstrap', wpshopmart_progress_bar_pro_directory_url.'assets/css/bootstrap.css');
	    wp_enqueue_style('wpsm_progr_b-font-awesome', wpshopmart_progress_bar_pro_directory_url.'assets/css/font-awesome/css/font-awesome.min.css');
		wp_enqueue_script('wpsm_progress_circular-progress-js',wpshopmart_progress_bar_pro_directory_url.'assets/js/circular-progress.js','','',true);		
	} **/
       
	public function progressbar_wp_register_cpt(){
		require_once('probar-reg.php');
		add_filter( 'manage_edit-wpsm_progressbar_r_columns', array(&$this, 'progressbar_wp_panels_columns' )) ;
		add_action( 'manage_wpsm_progressbar_r_posts_custom_column', array(&$this, 'progressbar_wp_manage_columns' ), 10, 2 );
	}
	
	function progressbar_wp_panels_columns( $columns ){
        $columns = array(
            'cb' => '<input type="checkbox" />',
            'title' => __( 'Progressbar' ),
            'shortcode' => __( 'Progressbar Shortcode' ),
            'date' => __( 'Date' )
        );
        return $columns;
    }

    function progressbar_wp_manage_columns( $column, $post_id ){
        global $post;
        switch( $column ) {
          case 'shortcode' :
            echo '<input style="width:225px" type="text" value="[PROGRESSBAR_WP id='.$post_id.']" readonly="readonly" />';
            break;
          default :
            break;
        }
    }
	
	// metaboxes
	public function progressbar_wp_meta_boxes_group(){
	add_meta_box('progressbar_wp_templates', __('Select Progressbar Design', progress_bar_wp_text_domain), array(&$this, 'progressbar_wp_design_select_function'), 'progressbar_wp_r', 'normal', 'low' );	
	add_meta_box('add_wp_progressbar', __('Add Progressbar', progress_bar_wp_text_domain), array(&$this, 'add_progressbar_wp_meta_box_function'), 'progressbar_wp_r', 'normal', 'low' );
	add_meta_box ('progressbar_wp_shortcode', __('Progress Bar Shortcode', progress_bar_wp_text_domain), array(&$this, 'progressbar_wp_shortcode'), 'progressbar_wp_r', 'normal', 'low');
    add_meta_box('progressbar_wp_setting', __('ProgressBar Settings', progress_bar_wp_text_domain), array(&$this, 'add_progressbar_wp_setting_meta_box_function'), 'progressbar_wp_r', 'side', 'low');
	}
    
	public function progressbar_wp_design_select_function(){
		require_once('designs.php');
	}
	
	public function add_progressbar_wp_meta_box_function($post){
		require_once('add-progress-bar.php');
	}
	
	public function add_progressbar_wp_setting_meta_box_function($post){
		require_once('settings.php');
	}
	
    public function add_progressbar_wp_meta_box_save($PostID) {
	    require('data-post/progressbar-save-data.php');
    }
    
	public function progressbar_wp_settings_meta_box_save($PostID){
		require('data-post/progressbar-settings-save-data.php');
	}
	
	public function progressbar_wp_shortcode(){
		require_once('custom-css.php');
	
	}
	
}
global $progressbar_wp;
$progressbar_wp = progressbar_wp::forge();
?>