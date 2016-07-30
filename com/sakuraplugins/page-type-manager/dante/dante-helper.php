<?php
require_once(dirname(__FILE__) . '/../../utils/AppetitUtils.php');
/**
* dante cafe helper class
*/
class DanteHelper
{	
	public static function buildContent($genericPageObject, $options = array()) {
		$showLogo = (isset($options['showLogo']) && $options['showLogo'] == 'true') ? true : false;
		$removeShadow = (isset($options['removeShadow']) && $options['removeShadow'] == 'true') ? true : false;
		$danteShadowCSS = ($removeShadow) ? '' : ' appetit-dante-main-shadow';
		?>
		<div class="appetit-dante-main<?php echo $danteShadowCSS; ?>">
		<?php
		?>
			<?php if($showLogo): ?>
			<!--logo-->
			<div class="dante-main-logo-ui">
                <?php if (!is_null($genericPageObject->welcomeLogoId)): ?>
                <img src="<?php echo wp_get_attachment_image_url($genericPageObject->welcomeLogoId); ?>" srcset="<?php echo wp_get_attachment_image_srcset($genericPageObject->welcomeLogoId); ?>" alt="logo" />
                <?php else: ?>
                <img src="<?php echo $genericPageObject->defaultLogo?>" alt="logo" />                        
                <?php endif; ?>				
			</div>
			<!--/logo-->
			<?php endif;?>


			<!--sections menu-->
	        <?php if (!empty($genericPageObject->sectionsData)): ?>
            	<ul class="appetit-dante-sections-menu">
            		<li><a class="appetit-dante-sections-menu-item" data-sectionid="dante-all" href="#"><?php echo $genericPageObject->appetitLabels['allLabel']?></a></li>
		            <?php foreach($genericPageObject->sectionsData as $section): ?>
		            	<li><a class="appetit-dante-sections-menu-item" data-sectionid="<?php echo $section['menu_section_id']; ?>" href="#"><?php echo $section['section_name'];?></a></li>
		            <?php endforeach; ?>
	            </ul>
            <?php endif; ?>
			<!--/sections menu-->

			<!--sections items-->
	        <?php if (!empty($genericPageObject->sectionsData)): ?>
            	<div class="appetit-dante-items-ui">
            		<div class="appetit-dante-preloader"><img src="<?php echo APPETIT_FRONT_URI; ?>/img/preloader.gif" alt="preloader" /></div>
		            <?php foreach($genericPageObject->sectionsData as $section): ?>
		            	<?php self::renderSection($section, $genericPageObject->currencySymbol, $genericPageObject->currencyPosition, $genericPageObject); ?>
		            <?php endforeach; ?>		            
	            </div>
	            <div class="dante-clearfix"></div>
            <?php endif; ?>			
			<!--/sections items-->


		</div>
		<?php
	}

	//render grid item
	protected static function renderSection($section, $currencySymbol, $currencyPosition, $genericPageObject) {	
		?>
        <?php if (!empty($section['section_items'])): ?>
            <?php foreach($section['section_items'] as $item): ?>
            	<?php self::renderGridItem($item, $currencySymbol, $currencyPosition, $section['menu_section_id'], $genericPageObject); ?>
            <?php endforeach; ?>                                            
        <?php endif; ?>	
		<?php
	}

	//render item
	protected static function renderGridItem($item, $currencySymbol, $currencyPosition, $sectionID, $genericPageObject) {
		?>
		<div class="appetit-dante-grid-item appetit-dante-grid-item-cursor <?php echo $sectionID; ?> dante-all">
			<div class="appetit-dante-grid-item-inside">
				<div class="appetit-dante-image-holder" data-src="<?php echo $item['menu_img_src']; ?>" data-srcset="<?php echo $item['menu_img_srcset']; ?>"></div>
				<div class="appetit-dante-item-about">
					<div class="appetit-dante-item-about-inside">
						<p class="appetit-dante-item-title"><?php echo $item['menu_item_name']; ?></p>
						<div class="appetit-dante-item-description"><?php echo $item['item_small_description']; ?></div>
						<p class="appetit-dante-item-price"><?php echo $genericPageObject->appetitLabels['priceLabel']?>: <?php echo AppetitUtils::getFormatedPrice($item['price_input'], $currencySymbol, $currencyPosition); ?></p>
					</div>
				</div>
			</div>			
		</div>
		<?php
	}	
}
?>