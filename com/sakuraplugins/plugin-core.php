<?php

require_once(dirname(__FILE__) . '/admin/ui/core-ui.php');
require_once(dirname(__FILE__) . '/options/AppetitOptions.php');
require_once(dirname(__FILE__) . '/utils/AppetitUtils.php');
require_once(dirname(__FILE__) . '/utils/cpt/cpt-helper.php');
require_once(dirname(__FILE__) . '/utils/cpt/appetit-cpt.php');

/**
* Base plugin class
*/
class AppetitCore {

	private $_optionPageSlug = 'appetitoptspage';
	const APPETIT_MENU_DATA = 'APPETIT_MENU_DATAS';
	const APPETIT_CPT_META = 'appetit_cpt_meta';
	const APPETIT_CPT_TYPE = 'appetit_cpt';
	private $_appetitCPT;

	public function initializeHandler() {
		$this->_registerCPT();
	}

	//menu event
	public function admin_menu() {
		$CoreUI = new CoreUI();

		add_submenu_page('edit.php?post_type=' . self::APPETIT_CPT_TYPE, 'Appetit menu', 'Appetit menu', 'manage_options', $this->_optionPageSlug, array($CoreUI, 'renderMaster'));		    
		
	}

	//admin scripts
	public function adminEnqueueScriptsHandler() {
		$current_screen = get_current_screen();
		$screenID = $current_screen->id;
	
		//post type page OR options page
		if ($current_screen->post_type === $this->_appetitCPT->getPostSlug() || (substr($screenID, -strlen($this->_optionPageSlug)) === $this->_optionPageSlug)) {
			
			AppetitUtils::enqueAdminFontsFrom(AppetitOptions::getAdminFonts());

			wp_register_style('appetit_admin_icons', APPETIT_ADMIN_URI . '/css/style.css');
			wp_enqueue_style('appetit_admin_icons');

			wp_register_style('appetit_admin_style', APPETIT_ADMIN_URI . '/css/appetit-admin.css');
			wp_enqueue_style('appetit_admin_style');			
			
			wp_enqueue_script('jquery');
			wp_enqueue_script('underscore');
			wp_register_script( 'sakura_utils', APPETIT_ADMIN_URI.'/js/sakura-utils.js', array('jquery'), FALSE, TRUE);
			wp_enqueue_script('sakura_utils');			

			wp_enqueue_script('media-upload');
			wp_enqueue_media();			
		}	

		//post type page
		if ($current_screen->post_type === $this->_appetitCPT->getPostSlug()) {
			//echo "----------------lllllllll";
			//TBD - enque scripts
		}

		//options page
		if (substr($screenID, -strlen($this->_optionPageSlug)) === $this->_optionPageSlug) {			
			wp_register_style('jquery_ui_style', APPETIT_ADMIN_URI . '/css/jquery-ui.min.css');
			wp_enqueue_style('jquery_ui_style');
			
			wp_enqueue_script('jquery-ui-tabs');
			wp_enqueue_script('jquery-ui-accordion');
			wp_enqueue_script('jquery-ui-sortable');															

			wp_register_script( 'appetit_views', APPETIT_ADMIN_URI.'/js/appetit-views.js', array('jquery'), FALSE, TRUE);
			wp_enqueue_script('appetit_views');
			wp_register_script( 'appetit_admin', APPETIT_ADMIN_URI.'/js/appetit-admin.js', array('appetit_views'), FALSE, TRUE);
			wp_localize_script( 'appetit_admin', 'AppetitHelper', array('APPETIT_ADMIN_URI' => APPETIT_ADMIN_URI) );
			wp_enqueue_script('appetit_admin');													
		}

	}

	public function appetit_admin_api() {
		if (!is_admin()) {
			echo json_encode(array('status' => 'FAIL', 'msg' => 'Something went wrong, only admin can save this data!'));
			die();
		}
		$sectionsData = isset($_POST['sectionsData']) ? $_POST['sectionsData'] : [];
		$welcomeData = isset($_POST['welcomeData']) ? $_POST['welcomeData'] : [];
		$optionsData = isset($_POST['optionsData']) ? $_POST['optionsData'] : [];
		
		update_option(self::APPETIT_MENU_DATA, array(
			'sectionsData' => $sectionsData,
			'welcomeData' => $welcomeData,
			'optionsData' => $optionsData
		));
		echo json_encode(array('status' => 'OK'));
		die();
	}

	//register custom post type
	private function _registerCPT() {
		$settings = array('post_custom_meta_data' => self::APPETIT_CPT_META, 'post_type' => self::APPETIT_CPT_TYPE, 'name' => 'Appetit', 'menu_icon' => APPETIT_ADMIN_URI . '/img/icon.png',
		'singular_name' => 'Appetit', 'rewrite' => 'appetit-pages', 'add_new' => 'New page / app',
		'edit_item' => 'Edit', 'new_item' => 'New page / app', 'view_item' => 'View page', 'search_items' => 'Search pages',
		'not_found' => 'No page found', 'not_found_in_trash' => 'Page not found in trash', 
		'supports' => array('title'));

		$cptHelper = new AppetitCPTHelper($settings);
		$this->_appetitCPT = new AppetitCPT();
		$this->_appetitCPT->create($cptHelper);
	}

	//admin bar custom
	public function adminBarCustom(){
		if(function_exists('get_current_screen')){
			$current_screen = get_current_screen();		
			if($current_screen->post_type == self::APPETIT_CPT_TYPE){			
				require_once(dirname(__FILE__) . '/admin/ui/appetit-admin-header.php');
				AppetitHeader::render();
			}
		}
	}		

	//init listeners
	public function init($opts=NULL){
		add_action( 'init', array($this, 'initializeHandler' ) );
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		add_action( 'admin_enqueue_scripts', array($this, 'adminEnqueueScriptsHandler' ) );
		add_action( 'wp_ajax_appetit_admin_api', array( $this, 'appetit_admin_api' ) );
		add_action( 'wp_before_admin_bar_render', array($this, 'adminBarCustom' ) );

		/*
		add_action('after_setup_theme', array($this, 'after_theme_setup'));			
		add_action('admin_init', array($this, 'adminInitHandler'));
		add_action('save_post', array($this, 'savePostHandler'));								
		add_action('admin_menu', array($this, 'adminMenuHandler'));				
		add_filter("single_template", array($this, 'sk_plugin_single'));
		register_deactivation_hook($opts['PLUGIN_FILE'], array($this, 'plugin_deactivate'));
		add_action('wp_ajax_nopriv_wedxrsvp', array($this, 'ajaxRSVPHandler'));
		add_action('wp_ajax_wedxrsvp', array($this, 'ajaxRSVPHandler'));
		add_action('wp_ajax_wedxrsvpadmin', array($this, 'ajaxRSVPHandlerAdmin'));
		*/	
	}
}

?>