<?php
require_once(realpath(dirname(__FILE__) . '/../..') . '/plugin-core.php');
/**
* base core UI class
*/
class CoreUI {

	public function renderMaster() {
		require_once(dirname(__FILE__) . '/appetit-admin-header.php');

		$this->_renderMainContainer();
		
		$appetitHeader = new AppetitHeader();
		$appetitHeader->render();	

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

		  <div id="menu">
		  	<div class="centered-content">
		  		<a id="addSectionBTN" class="content-large-button" href="#">Add menu section</a>
		  	</div>

		  	<!--menu sections-->
		  	<div id="sections_accordion"><?php $this->_renderMenuSections(); ?></div>
		  	<!--/menu sections-->

		  </div>
		  <div id="welcome">
		    <p>Morbi tincidunt, dui sit amet facilisis feugiat, odio metus gravida ante, ut pharetra massa metus id nunc. Duis scelerisque molestie turpis. Sed fringilla, massa eget luctus malesuada, metus eros molestie lectus, ut tempus eros massa ut dolor. Aenean aliquet fringilla sem. Suspendisse sed ligula in ligula suscipit aliquam. Praesent in eros vestibulum mi adipiscing adipiscing. Morbi facilisis. Curabitur ornare consequat nunc. Aenean vel metus. Ut posuere viverra nulla. Aliquam erat volutpat. Pellentesque convallis. Maecenas feugiat, tellus pellentesque pretium posuere, felis lorem euismod felis, eu ornare leo nisi vel felis. Mauris consectetur tortor et purus.</p>
		  </div>
		  <div id="options">
		    <p>Mauris eleifend est et turpis. Duis id erat. Suspendisse potenti. Aliquam vulputate, pede vel vehicula accumsan, mi neque rutrum erat, eu congue orci lorem eget lorem. Vestibulum non ante. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Fusce sodales. Quisque eu urna vel enim commodo pellentesque. Praesent eu risus hendrerit ligula tempus pretium. Curabitur lorem enim, pretium nec, feugiat nec, luctus a, lacus.</p>
		    <p>Duis cursus. Maecenas ligula eros, blandit nec, pharetra at, semper at, magna. Nullam ac lacus. Nulla facilisi. Praesent viverra justo vitae neque. Praesent blandit adipiscing velit. Suspendisse potenti. Donec mattis, pede vel pharetra blandit, magna ligula faucibus eros, id euismod lacus dolor eget odio. Nam scelerisque. Donec non libero sed nulla mattis commodo. Ut sagittis. Donec nisi lectus, feugiat porttitor, tempor ac, tempor vitae, pede. Aenean vehicula velit eu tellus interdum rutrum. Maecenas commodo. Pellentesque nec elit. Fusce in lacus. Vivamus a libero vitae lectus hendrerit hendrerit.</p>
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
			?>
				<div class="menu_item_ui">
				    <h3 class="section-header"><span>| </span><span class="menu_title"><?php echo wptexturize($menuItem['menu_item_name']); ?></span> <span class="appetit-move a-pull-right"></span><span style="margin-right: 10px;" class="menuRemoveBTN appetit-trashcan2 a-pull-right"></span></h3>
				    <div class="clearfix">
					    <div class="section-content-header">
					    	<span class="input_label_prepend">Name</span><input class="generic_input one-third input_margin menu_item_name" placeholder="Item name" type="text" value="<?php echo wptexturize($menuItem['menu_item_name']); ?>" />
					    	<span class="input_label_prepend">Price</span><input class="generic_input price_input" type="number" min="0" value="<?php echo $menuItem['price_input']; ?>" />
					    	<div class="menu_img_ui"><?php echo $menuImageHTML;?></div>
					    	<input class="menu_img_id" type="hidden" value="<?php echo $menuItem['menu_img_id'];?>" />
					    	<a class="base-button menuImageBTN" href="#"><span class="appetit-upload"></span>Upload image</a>	
					    	<div class="clearfix"></div>
					    	<textarea class="item_small_description" placeholder="small description"></textarea>
					    </div>
				    </div>
				</div>
			<?php
		}
	}


}

?>