<?php

/**
 * @package wp.plugin-communio
 */
/*

Plugin Name: wp.plugin-communio
Plugin URI: communio.co
Description: -
Version: -
Author: -
Author URI: -
License: GPLv2 or later
Text Domain: wp.plugin-communio
*/

?>

<?php

include( plugin_dir_path( __FILE__ ) . 'hooks/wp-head.php');
include( plugin_dir_path( __FILE__ ) . 'hooks/body_classes.php');
include( plugin_dir_path( __FILE__ ) . 'acf/options.php');

function communio_load_plugin_css() {
    $plugin_url = plugin_dir_url( __FILE__ );
    wp_enqueue_style( 'communio-native-style', $plugin_url . 'css/native.css?13s1Ws1' );
	wp_enqueue_style( 'communio-chat-style', $plugin_url . 'css/chat.css?2W2ss32' );
	wp_enqueue_style( 'communio-frontend-style', $plugin_url . 'css/frontend.css' );
}
add_action( 'wp_enqueue_scripts', 'communio_load_plugin_css' );

 
function native_classes() {
	
	
	
	
	
    echo "<script>
	jQuery(function($){
      		
			
			
			
			var standalone = window.navigator.standalone;
    var userAgent = window.navigator.userAgent.toLowerCase();
    var safari = /safari/.test( userAgent );
	var ios = /iphone|ipod|ipad/.test( userAgent );
	var newClass;

	if( ios ) {
		//alert('ios');
		if ( !standalone && safari ) {
			//alert('ios browser');
			newClass = 'ios-browser';
		} else if ( standalone && !safari ) {
			//alert('ios standalone');
			newClass = 'ios-standalone';
		} else if ( !standalone && !safari ) {
			//alert('ios uiwebview');
			newClass = 'ios-uiwebview native';
		}
	} else if( navigator.userAgent.includes ('wv')){
		 //alert('android webview');
		 newClass = 'android-webview native';
	  }else{
		 //alert('apple / pc / android browser');
		 newClass = 'apple-pc-android-browser show_all_bp';
	  };

$('body').addClass(newClass);
			
			
			/*
			
				$(window).load(function() {
				  //$('body').removeClass('preload');
				  alert('fade out');
				});

			*/
			
    });
	
	
	
	


</script>";
}
add_action('wp_footer', 'native_classes');




// PAGE TEMPLATE AS A PLUGIN CODE = BELOW - needed to allow accessing bp pages for a user from the app

class PageTemplaterBP {

	/**
	 * A reference to an instance of this class.
	 */
	private static $instance;

	/**
	 * The array of templates that this plugin tracks.
	 */
	protected $templates;

	/**
	 * Returns an instance of this class.
	 */
	public static function get_instance() {

		if ( null == self::$instance ) {
			self::$instance = new PageTemplaterBP();
		}

		return self::$instance;

	}

	/**
	 * Initializes the plugin by setting filters and administration functions.
	 */
	private function __construct() {

		$this->templates = array();


		// Add a filter to the attributes metabox to inject template into the cache.
		if ( version_compare( floatval( get_bloginfo( 'version' ) ), '4.7', '<' ) ) {

			// 4.6 and older
			add_filter(
				'page_attributes_dropdown_pages_args',
				array( $this, 'register_project_templates' )
			);

		} else {

			// Add a filter to the wp 4.7 version attributes metabox
			add_filter(
				'theme_page_templates', array( $this, 'add_new_template' )
			);

		}

		// Add a filter to the save post to inject out template into the page cache
		add_filter(
			'wp_insert_post_data',
			array( $this, 'register_project_templates' )
		);


		// Add a filter to the template include to determine if the page has our
		// template assigned and return it's path
		add_filter(
			'template_include',
			array( $this, 'view_project_template')
		);


		// Add your templates to this array.
		$this->templates = array(
			'get-my-bp-pages.php' => 'GET MY BP PAGES',
		);

	}

	/**
	 * Adds our template to the page dropdown for v4.7+
	 *
	 */
	public function add_new_template( $posts_templates ) {
		$posts_templates = array_merge( $posts_templates, $this->templates );
		return $posts_templates;
	}

	/**
	 * Adds our template to the pages cache in order to trick WordPress
	 * into thinking the template file exists where it doens't really exist.
	 */
	public function register_project_templates( $atts ) {

		// Create the key used for the themes cache
		$cache_key = 'page_templates-' . md5( get_theme_root() . '/' . get_stylesheet() );

		// Retrieve the cache list.
		// If it doesn't exist, or it's empty prepare an array
		$templates = wp_get_theme()->get_page_templates();
		if ( empty( $templates ) ) {
			$templates = array();
		}

		// New cache, therefore remove the old one
		wp_cache_delete( $cache_key , 'themes');

		// Now add our template to the list of templates by merging our templates
		// with the existing templates array from the cache.
		$templates = array_merge( $templates, $this->templates );

		// Add the modified cache to allow WordPress to pick it up for listing
		// available templates
		wp_cache_add( $cache_key, $templates, 'themes', 1800 );

		return $atts;

	}

	/**
	 * Checks if the template is assigned to the page
	 */
	public function view_project_template( $template ) {
		// Return the search template if we're searching (instead of the template for the first result)
		if ( is_search() ) {
			return $template;
		}

		// Get global post
		global $post;

		// Return template if post is empty
		if ( ! $post ) {
			return $template;
		}

		// Return default template if we don't have a custom one defined
		if ( ! isset( $this->templates[get_post_meta(
			$post->ID, '_wp_page_template', true
		)] ) ) {
			return $template;
		}

		// Allows filtering of file path
		$filepath = apply_filters( 'page_templater_plugin_dir_path', plugin_dir_path( __FILE__ ) );

		$file =  $filepath . get_post_meta(
			$post->ID, '_wp_page_template', true
		);

		// Just to be safe, we check if the file exist first
		if ( file_exists( $file ) ) {
			return $file;
		} else {
			echo $file;
		}

		// Return template
		return $template;

	}

}
add_action( 'plugins_loaded', array( 'PageTemplaterBP', 'get_instance' ) );

/* hide admin bar for all ------- bar admin =======*/
add_action('after_setup_theme', 'remove_admin_bar');
function remove_admin_bar() {
if ( !is_admin()) {
//if (!current_user_can('administrator') && !is_admin()) {
  show_admin_bar(false);
}
}

?>