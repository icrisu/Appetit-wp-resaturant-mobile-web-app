<?php
require_once(realpath(dirname(__FILE__) . '/../..') . '/plugin-core.php');
/**
* base core UI class
*/
class CoreUI {

	public function renderMaster() {
		$this->_renderMainContainer();		
		$this->_renderContent();		
		$this->_renderMainContainer(true);
	}

	//main container
	private function _renderMainContainer($isBottom = false) {
		echo ($isBottom) ? '</div>' : '<div class="appetit-admin-ui">';
	}

	//render condent
	private function _renderContent() {
		?>
		<div class="appetit-admin-content" style="display: none;">		  
		  <ul>
		    <li><a href="#menu">Menu</a></li>
		    <li><a href="#welcome">Welcome page</a></li>
		    <li><a href="#options">Options</a></li>
		    <li><a href="#qrcode">QR Code</a></li>
		  </ul>

		  <!--menu-->
		  <div id="menu">
		  	<div class="centered-content">
		  		<a id="addSectionBTN" class="content-large-button" href="#">Add menu section</a>
		  	</div>

		  	<!--menu sections-->
		  	<div id="sections_accordion"><?php $this->_renderMenuSections(); ?></div>
		  	<!--/menu sections-->
		  </div>
		  <!--/menu-->

		  <!--welcome page-->
		  <div id="welcome">
		  	<?php $this->_renderWelcome(); ?>
		  </div>
		  <!--/welcome page-->


		  <div id="options">
		    <?php $this->_renderOptions(); ?>
		  </div>
		  <div id="qrcode">
		    <p>Morbi tincidunt, dui sit amet facilisis feugiat, odio metus gravida ante, ut pharetra massa metus id nunc. Duis scelerisque molestie turpis. Sed fringilla, massa eget luctus malesuada, metus eros molestie lectus, ut tempus eros massa ut dolor. Aenean aliquet fringilla sem. Suspendisse sed ligula in ligula suscipit aliquam. Praesent in eros vestibulum mi adipiscing adipiscing. Morbi facilisis. Curabitur ornare consequat nunc. Aenean vel metus. Ut posuere viverra nulla. Aliquam erat volutpat. Pellentesque convallis. Maecenas feugiat, tellus pellentesque pretium posuere, felis lorem euismod felis, eu ornare leo nisi vel felis. Mauris consectetur tortor et purus.</p>
		  </div>		  			
		</div>
		<?php
	}

	//render menu sections
	private function _renderMenuSections() {
		$data = get_option(AppetitCore::APPETIT_MENU_DATA, array());
		if (isset($data['sectionsData'])) {
			$sectionsData = $data['sectionsData'];
			foreach ($sectionsData as $section) {
				$sectionImageHTML = isset($section['section_img_id']) ? '<img src="' . wp_get_attachment_image_src($section['section_img_id'], 'thumbnail')[0] . '" alt="" />' : '';
				$section_items = isset($section['section_items']) ? $section['section_items'] : array();
				?>
				<div class="appetit_section">
				    <h3 class="section-header"><span>|  </span><span class="m_title"><?php echo wptexturize($section['section_name']);?></span> <span class="appetit-move a-pull-right"></span><span style="margin-right: 10px;" class="sectionRemoveBTN appetit-trashcan2 a-pull-right"></span></h3>
				    <div class="clearfix">
					    <div class="section-content-header">
					    	<input class="generic_input one-third section_name" placeholder="Section name" type="text" value="<?php echo wptexturize($section['section_name']);?>" />
					    	<div class="section_img_ui"><?php echo $sectionImageHTML;?></div>
					    	<input class="section_img_id" type="hidden" value="<?php echo (isset($section['section_img_id']) ? $section['section_img_id'] : '') ?>" />
					    	<a class="base-button sectionImageBTN" href="#"><span class="appetit-upload"></span>Upload section image</a>
					    	<a class="base-button addMenuItemBTN a-pull-right" href="#"><span class="appetit-plus"></span>Add menu item</a>
					    	<div class="clearfix"></div>
					    </div>
					    <div class="section-space-line"></div>
					    <div class="section_content">
					    	<div class="menu_items"><?php $this->_renderMenuItems($section_items); ?></div>
					    </div>
				    </div>
				</div>
				<?php
			}
		}		
	}

	private function _renderMenuItems($section_items) {
		foreach ($section_items as $menuItem) {
				$menuImageHTML = isset($menuItem['menu_img_id']) ? '<img src="' . wp_get_attachment_image_src($menuItem['menu_img_id'], 'thumbnail')[0] . '" alt="" />' : '';
				$menuItemName = isset($menuItem['menu_item_name']) ? $menuItem['menu_item_name'] : '';
				$itemSmallDescription = isset($menuItem['item_small_description']) ? $menuItem['item_small_description'] : '';
			?>
				<div class="menu_item_ui">
				    <h3 class="section-header"><span>| </span><span class="menu_title"><?php echo wptexturize($menuItemName); ?></span> <span class="appetit-move a-pull-right"></span><span style="margin-right: 10px;" class="menuRemoveBTN appetit-trashcan2 a-pull-right"></span></h3>
				    <div class="clearfix">
					    <div class="section-content-header">
					    	<span class="input_label_prepend">Name</span><input class="generic_input one-third input_margin menu_item_name" placeholder="Item name" type="text" value="<?php echo wptexturize($menuItem['menu_item_name']); ?>" />
					    	<span class="input_label_prepend">Price</span><input class="generic_input price_input" type="number" min="0" value="<?php echo $menuItem['price_input']; ?>" />
					    	<div class="menu_img_ui"><?php echo $menuImageHTML;?></div>
					    	<input class="menu_img_id" type="hidden" value="<?php echo $menuItem['menu_img_id'];?>" />
					    	<a class="base-button menuImageBTN" href="#"><span class="appetit-upload"></span>Upload image</a>	
					    	<div class="clearfix"></div>
					    	<textarea id="<?php echo uniqid('_desc_'); ?>" class="item_small_description" placeholder="small description"><?php echo wptexturize($itemSmallDescription); ?></textarea>
					    </div>
				    </div>
				</div>
			<?php
		}
	}


	private function _renderWelcome() {
			$data = get_option(AppetitCore::APPETIT_MENU_DATA, array());
			$welcomeData = isset($data['welcomeData']) ? $data['welcomeData'] : array(); 			
			$welcomeAbout = ( isset($welcomeData['welcomeAbout'])) ? $welcomeData['welcomeAbout'] : '';
			$welcomeLogoId = ( isset($welcomeData['welcomeLogoId'])) ? $welcomeData['welcomeLogoId'] : '';
			$welcomeLabel = ( isset($welcomeData['welcomeLabel'])) ? $welcomeData['welcomeLabel'] : 'Menu';
			?>
		    <p class="admin_medium_headline">Edit welcome page below. The mobile web app first starts with welcome page.</p>
		    
		    <div class="hr-line"></div>
		    
		    <p class="admin_small_headline">Small description</p>
		    <textarea id="homepage-small-description" class="homepage-small-description" placeholder="small description"><?php echo wptexturize($welcomeAbout);?></textarea>
		    
		    <div class="hr-line"></div>

		    <p class="admin_small_headline">Restaurant logo</p>	    		
	    		<input class="welcome-logo-id" type="hidden" value="<?php echo $welcomeLogoId;?>" />
	    		<a class="base-button welcomeImageBTN" href="#"><span class="appetit-upload"></span>Upload restaurant logo</a>
	    		<div class="generic_thumb_ui welcome-logo-ui">
	    			<?php if($welcomeLogoId !== ''):?>
	    				<img src="<?php echo wp_get_attachment_image_src($welcomeLogoId, 'thumbnail')[0]; ?>" alt="" />
	    			<?php else: ?>
	    				<img src="<?php echo APPETIT_ADMIN_URI . '/img/logo-frontend-default.png' ?>" alt="" />
	    			<?php endif;?>
	    		</div>
	    		<div class="clearfix"></div>
		    <div class="hr-line"></div>

		    <p class="admin_small_headline">Enter app label</p>
		    <input class="generic_input one-third input_margin enter_app_label" placeholder="Menu" type="text" value="<?php echo wptexturize($welcomeLabel);?>" />			
			<?php	
	}

	private function _renderOptions() {
		$data = get_option(AppetitCore::APPETIT_MENU_DATA, array());
		$optionsData = isset($data['optionsData']) ? $data['optionsData'] : array(); 

		$currencySymbol = ( isset($optionsData['currencySymbol'])) ? $optionsData['currencySymbol'] : '$';
		$currencyPosition = ( isset($optionsData['currencyPosition'])) ? (int) $optionsData['currencyPosition'] : 0;
		$appetitSlug = ( isset($optionsData['appetitSlug'])) ? $optionsData['appetitSlug'] : 'appetit';

		$checkbox_checked = ($currencyPosition === 1) ? ' checked' : '';		

		?>
		<p class="admin_medium_headline">Edit options.</p>
		<div class="hr-line"></div>

		<p class="admin_small_headline">Currency symbol</p>
		<input class="generic_input price_input input_margin currency_symbol" placeholder="$" type="text" value="<?php echo wptexturize($currencySymbol);?>" />
		<div class="hr-line"></div>

		<p class="admin_small_headline">Symbol position</p>
		<input class="currency_position" type="hidden" value="<?php echo $currencyPosition;?>" />
		<input type="checkbox" class="currency_position_cb"<?php echo $checkbox_checked;?>> Show after the price<br>
		<div class="hr-line"></div>

		<p class="admin_small_headline">Appetit re-write slug</p>
		<p class="appetit_notification"><b>NOTE! </b>The re-write slug will affect the way Appetit's permalinks look, ex: http://website.com/slug/page-name. If you change the slug, in order the changes to take effect, go to Admin > Settings > Permalinks and click "Save". Do not add spaces within the slug! Make sure you do not have the same slug as the name of a static page.</p>
		<input class="generic_input one-third input_margin appetit_slug" placeholder="enter appetit slug" type="text" value="<?php echo wptexturize($appetitSlug);?>" />

		<?php
	}


}

?>