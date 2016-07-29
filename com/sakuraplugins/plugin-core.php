<?php

require_once(dirname(__FILE__) . '/admin/ui/core-ui.php');
require_once(dirname(__FILE__) . '/options/AppetitOptions.php');
require_once(dirname(__FILE__) . '/utils/AppetitUtils.php');
require_once(dirname(__FILE__) . '/utils/cpt/cpt-helper.php');
require_once(dirname(__FILE__) . '/utils/cpt/appetit-cpt.php');
require_once(dirname(__FILE__) . '/shortcodes/shortcodes-manager.php');

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
		$scm = new ShortcodesManager();
		$scm->registerShortcodes();
	}

	//admin init handler
	public function adminInitHandler() {
		if (isset($this->_appetitCPT)) {
			$this->_appetitCPT->addMetaBox(__('Appetit page', WX_PLUGIN_TEXTDOMAIN), 'meta_box_page_8172398', 'meta_box_appetit');
		}		
	}

	//menu event
	public function admin_menu() {
		$CoreUI = new CoreUI();

		add_submenu_page('edit.php?post_type=' . self::APPETIT_CPT_TYPE, 'Appetit menu', 'Appetit menu', 'manage_options', $this->_optionPageSlug, array($CoreUI, 'renderMaster'));		    
		
	}

	//Enqueue scripts frontend
	public function wpEnqueueScriptsHandler() {
		wp_enqueue_script('jquery');
		
		wp_register_style('appetit-front-icon-fonts', APPETIT_FRONT_URI . '/fonts/front/style.css');
		wp_enqueue_style('appetit-front-icon-fonts');

		wp_register_style('luna-cafe', APPETIT_FRONT_URI . '/css/luna-cafe.css');
		wp_enqueue_style('luna-cafe');

		wp_register_style('appetit-mixto', APPETIT_FRONT_URI . '/css/appetit-mixto.css');
		wp_enqueue_style('appetit-mixto');

		wp_register_script('resize_sensor', APPETIT_FRONT_URI.'/libs/element-queries/ResizeSensor.js', array('jquery'), FALSE, TRUE);
		wp_enqueue_script('resize_sensor');

		wp_register_script('element_queries', APPETIT_FRONT_URI.'/libs/element-queries/ElementQueries.js', array('jquery'), FALSE, TRUE);
		wp_enqueue_script('element_queries');

		AppetitUtils::enqueFontsFrom(AppetitOptions::getFrontendFonts());		
	}

	//admin scripts
	public function adminEnqueueScriptsHandler() {
		$current_screen = get_current_screen();
		$screenID = $current_screen->id;
	
		//options page
		if ((substr($screenID, -strlen($this->_optionPageSlug)) === $this->_optionPageSlug)) {
			
			AppetitUtils::enqueAdminFontsFrom(AppetitOptions::getAdminFonts());

			wp_register_style('appetit_admin_icons', APPETIT_ADMIN_URI . '/css/style.css');
			wp_enqueue_style('appetit_admin_icons');

			wp_register_style('appetit_tooltipster_style', APPETIT_ADMIN_URI . '/css/tooltipster.bundle.min.css');
			wp_enqueue_style('appetit_tooltipster_style');		

			wp_register_style('appetit_admin_style', APPETIT_ADMIN_URI . '/css/appetit-admin.css');
			wp_enqueue_style('appetit_admin_style');			
			
			wp_enqueue_script('jquery');
			wp_register_script( 'sakura_tooltipster_js', APPETIT_ADMIN_URI.'/js/tooltipster.bundle.min.js', array('jquery'), FALSE, TRUE);
			wp_enqueue_script('sakura_tooltipster_js');

			wp_enqueue_script('underscore');
			wp_register_script( 'sakura_utils', APPETIT_ADMIN_URI.'/js/sakura-utils.js', array('jquery'), FALSE, TRUE);
			wp_enqueue_script('sakura_utils');

			wp_enqueue_script('media-upload');
			wp_enqueue_media();			
		}	

		//post type page		
		if ($current_screen->post_type === $this->_appetitCPT->getPostSlug()) {

			AppetitUtils::enqueAdminFontsFrom(AppetitOptions::getAdminFonts());

			wp_register_style('appetit_admin_style', APPETIT_ADMIN_URI . '/css/appetit-admin.css');
			wp_enqueue_style('appetit_admin_style');
			
			wp_enqueue_script('jquery');

			wp_register_script( 'appetit_admin_cpt', APPETIT_ADMIN_URI.'/js/appetit-admin-cpt.js', array('jquery'), FALSE, TRUE);
			wp_enqueue_script('appetit_admin_cpt');
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
		$labelsData = isset($_POST['labelsData']) ? $_POST['labelsData'] : [];
		
		update_option(self::APPETIT_MENU_DATA, array(
			'sectionsData' => $sectionsData,
			'welcomeData' => $welcomeData,
			'optionsData' => $optionsData,
			'labelsData' => $labelsData
		));
		echo json_encode(array('status' => 'OK'));
		die();
	}

	//register custom post type
	private function _registerCPT() {
		$data = get_option(self::APPETIT_MENU_DATA, array());
		$optionsData = isset($data['optionsData']) ? $data['optionsData'] : array(); 
		$appetitSlug = ( isset($optionsData['appetitSlug'])) ? $optionsData['appetitSlug'] : 'appetit';		

		$settings = array('post_custom_meta_data' => self::APPETIT_CPT_META, 'post_type' => self::APPETIT_CPT_TYPE, 'name' => 'Appetit', 'menu_icon' => APPETIT_ADMIN_URI . '/img/icon.png',
		'singular_name' => 'Appetit', 'rewrite' => $appetitSlug, 'add_new' => 'New Appetit Page',
		'edit_item' => 'Edit', 'new_item' => 'New Appetit Page', 'view_item' => 'View page', 'search_items' => 'Search pages',
		'not_found' => 'No page found', 'not_found_in_trash' => 'Page not found in trash', 
		'supports' => array('title'));

		$cptHelper = new AppetitCPTHelper($settings);
		$this->_appetitCPT = new AppetitCPT();
		$this->_appetitCPT->create($cptHelper);
	}

	//admin bar custom
	public function adminBarCustom() {
		if (function_exists('get_current_screen')) {
			$current_screen = get_current_screen();		
			if($current_screen->post_type == self::APPETIT_CPT_TYPE){			
				require_once(dirname(__FILE__) . '/admin/ui/appetit-admin-header.php');
				AppetitHeader::render();
			}
		}
	}

	/**
	 * SAVE POST EXTRA DATA
	 */
	 public function savePostHandler() {
		global $post;
		if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ){
			return $post_id;
		}
		if (!current_user_can('edit_posts') || !current_user_can('publish_posts')) {
			return;
		}
			//save portfolio data
		if (isset($this->_appetitCPT) && isset($_POST['post_type'])) {
			if($this->_appetitCPT->getPostSlug() == $_POST['post_type']) {	
				if(current_user_can( 'edit_posts', $post->ID ) && isset($_POST[$this->_appetitCPT->getPostCustomMeta()])){							
					update_post_meta($post->ID, $this->_appetitCPT->getPostCustomMeta(), $_POST[$this->_appetitCPT->getPostCustomMeta()]);
				}
			}						
		}				
												
	 }

	//single template
	public function sk_plugin_single($single_template){
		global $post;
		if ($post->post_type == self::APPETIT_CPT_TYPE) {			
			$single_template = dirname( __FILE__ ) . '/single/appetit_cpt-template.php';										
		}
		return $single_template;
	}

	//init listeners
	public function init($opts=NULL){
		add_action( 'init', array($this, 'initializeHandler' ) );
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		add_action( 'admin_enqueue_scripts', array($this, 'adminEnqueueScriptsHandler' ) );
		add_action( 'wp_ajax_appetit_admin_api', array( $this, 'appetit_admin_api' ) );
		add_action( 'wp_before_admin_bar_render', array($this, 'adminBarCustom' ) );
		add_action(	'admin_init', array($this, 'adminInitHandler'));
		add_action(	'save_post', array($this, 'savePostHandler'));
		add_filter(	'single_template', array($this, 'sk_plugin_single'));
		add_action(	'wp_enqueue_scripts', array($this, 'wpEnqueueScriptsHandler'));
	}
}

?>