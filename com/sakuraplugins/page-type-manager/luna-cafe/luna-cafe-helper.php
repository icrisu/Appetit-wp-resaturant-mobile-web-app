<?php
require_once(dirname(__FILE__) . '/../../utils/AppetitUtils.php');
/**
* luna cafe helper class
*/
class LunaCafeHelper
{	
	public static function buildContent($genericPageObject) {
		?>
		<div class="luna-cafe-main">
		<?php
		?>
			<!--logo-->
			<div class="luna-cafe-main-logo-ui">
                <?php if (!is_null($genericPageObject->welcomeLogoId)): ?>
                <img src="<?php echo wp_get_attachment_image_url($genericPageObject->welcomeLogoId); ?>" srcset="<?php echo wp_get_attachment_image_srcset($genericPageObject->welcomeLogoId); ?>" alt="logo" />
                <?php else: ?>
                <img src="<?php echo $genericPageObject->defaultLogo?>" alt="logo" />                        
                <?php endif; ?>				
			</div>
			<!--/logo-->

			<!--stars-->
			<ul class="luna-cafe-starts">
				<li><span class="icon-appetit-frontstar3"></span></li>
				<li><span class="icon-appetit-frontstar3"></span></li>
				<li><span class="icon-appetit-frontstar3"></span></li>
			</ul>
			<!--/stars-->

			<!--sections-->			
            <?php if (!empty($genericPageObject->sectionsData)): ?>
	            <?php foreach($genericPageObject->sectionsData as $section): ?>
	            	<?php self::renderSection($section, $genericPageObject->currencySymbol, $genericPageObject->currencyPosition); ?>
	            <?php endforeach; ?>                                            
            <?php endif; ?>			
			<!--/sections-->

			<!--stars-->
			<ul class="luna-cafe-starts">
				<li><span class="icon-appetit-frontstar3"></span></li>
				<li><span class="icon-appetit-frontstar3"></span></li>
				<li><span class="icon-appetit-frontstar3"></span></li>
			</ul>
			<!--/stars-->

		</div>
		<?php
	}

	//render each section
	protected static function renderSection($section, $currencySymbol, $currencyPosition) {	
		?>
		<div class="luna-cafe-section">
			<h2 class="luna-cafe-section-title"><?php echo $section['section_name'];?></h2>
			<div class="luna-cafe-section-items">
	            <?php if (!empty($section['section_items'])): ?>
		            <?php foreach($section['section_items'] as $item): ?>
		            	<?php self::renderItem($item, $currencySymbol, $currencyPosition); ?>
		            <?php endforeach; ?>                                            
	            <?php endif; ?>				
			</div>
		</div>
		<?php
	}

	//render item
	protected static function renderItem($item, $currencySymbol, $currencyPosition) {
		?>
		<div class="luna-cafe-section-item">
			<p class="luna-cafe-section-item-name"><?php echo $item['menu_item_name']; ?></p>
			<p class="luna-cafe-section-item-price"><?php echo AppetitUtils::getFormatedPrice($item['price_input'], $currencySymbol, $currencyPosition); ?></p>
			<div class="luna-cafe-clear"></div>
		</div>
		<?php
	}	
}
?>