<?php
/**
* Base plugin class
*/
class AppetitCore {

	public function initializeHandler() {

	}

	//menu event
	public function admin_menu() {
		require_once(__DIR__.'/admin/ui/core-ui.php');
	    add_menu_page( 
	        __( 'Appetit - Restaurant App', 'appetitdomain' ),
	        __( 'Appetit', 'appetitdomain' ),
	        'manage_options',
	        'custompage',
	        array('CoreUI' , 'render'),
	        plugins_url( 'myplugin/images/icon.png' )
	    ); 
		
	}

	//admin scripts
	public function adminEnqueueScriptsHandler() {
		$current_screen = get_current_screen();
		$screenID = $current_screen->id;
		echo $screenID;
	}


	//init listeners
	public function init($opts=NULL){
		add_action( 'init', array($this, 'initializeHandler' ));
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		add_action('admin_enqueue_scripts', array($this, 'adminEnqueueScriptsHandler'));

		return;
		add_action('after_setup_theme', array($this, 'after_theme_setup'));			
		add_action('admin_init', array($this, 'adminInitHandler'));
		add_action('save_post', array($this, 'savePostHandler'));								
		add_action('admin_menu', array($this, 'adminMenuHandler'));				
		add_filter("single_template", array($this, 'sk_plugin_single'));
		register_deactivation_hook($opts['PLUGIN_FILE'], array($this, 'plugin_deactivate'));
		add_action("wp_before_admin_bar_render", array($this, 'adminBarCustom'));
		add_action('wp_ajax_nopriv_wedxrsvp', array($this, 'ajaxRSVPHandler'));
		add_action('wp_ajax_wedxrsvp', array($this, 'ajaxRSVPHandler'));
		add_action('wp_ajax_wedxrsvpadmin', array($this, 'ajaxRSVPHandlerAdmin'));		
	}
}

?>