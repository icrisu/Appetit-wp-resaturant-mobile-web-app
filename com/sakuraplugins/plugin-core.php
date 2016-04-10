<?php

require_once(dirname(__FILE__) . '/admin/ui/core-ui.php');
require_once(dirname(__FILE__) . '/options/AppetitOptions.php');
require_once(dirname(__FILE__) . '/utils/AppetitUtils.php');
/**
* Base plugin class
*/
class AppetitCore {

	private $_optionPageSlug = 'appetitoptspage';

	public function initializeHandler() {

	}

	//menu event
	public function admin_menu() {
		$CoreUI = new CoreUI();
	    add_menu_page( 
	        __( 'Appetit - Restaurant App', 'appetitdomain' ),
	        __( 'Appetit menu', 'appetitdomain' ),
	        'manage_options',
	        $this->_optionPageSlug,
	        array($CoreUI , 'renderMaster'),
	        APPETIT_ADMIN_URI . '/img/icon.png'
	    ); 
		
	}

	//admin scripts
	public function adminEnqueueScriptsHandler() {
		$current_screen = get_current_screen();
		$screenID = $current_screen->id;
		if (substr($screenID, -strlen($this->_optionPageSlug)) == $this->_optionPageSlug) {
			//load admin option page scripts
			AppetitUtils::enqueAdminFontsFrom(AppetitOptions::getAdminFonts());

			wp_register_style('appetit_admin_icons', APPETIT_ADMIN_URI . '/css/style.css');
			wp_enqueue_style('appetit_admin_icons');

			wp_register_style('appetit_admin_style', APPETIT_ADMIN_URI . '/css/appetit-admin.css');
			wp_enqueue_style('appetit_admin_style');

			wp_register_style('jquery_ui_style', APPETIT_ADMIN_URI . '/css/jquery-ui.min.css');
			wp_enqueue_style('jquery_ui_style');

			wp_enqueue_script('jquery');
			wp_enqueue_script('jquery-ui-tabs');
			wp_enqueue_script('jquery-ui-accordion');
			wp_enqueue_script('jquery-ui-sortable');						
			wp_enqueue_script('underscore');

			wp_enqueue_script('media-upload');
			wp_enqueue_media();						

			wp_register_script( 'sakura_utils', APPETIT_ADMIN_URI.'/js/sakura-utils.js', array('jquery'), FALSE, TRUE);
			wp_enqueue_script('sakura_utils');
			wp_register_script( 'appetit_views', APPETIT_ADMIN_URI.'/js/appetit-views.js', array('jquery'), FALSE, TRUE);
			wp_enqueue_script('appetit_views');
			wp_register_script( 'appetit_admin', APPETIT_ADMIN_URI.'/js/appetit-admin.js', array('appetit_views'), FALSE, TRUE);
			wp_enqueue_script('appetit_admin');										
		}

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