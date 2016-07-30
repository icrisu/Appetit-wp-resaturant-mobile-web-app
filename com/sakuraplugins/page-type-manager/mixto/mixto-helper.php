<?php
require_once(dirname(__FILE__) . '/../../utils/AppetitUtils.php');
/**
* mixto helper class
*/
class MixtoHelper
{	
	public static function buildContent($genericPageObject, $options = array()) {
		$showLogo = (isset($options['showLogo']) && $options['showLogo'] == 'true') ? true : false;
		$removeShadow = (isset($options['removeShadow']) && $options['removeShadow'] == 'true') ? true : false;
		$mixtoShadowCSS = ($removeShadow) ? '' : ' appetit-mixto-main-shadow';
		?>
		<div class="appetit-mixto-main<?php echo $mixtoShadowCSS; ?>">
		<?php
		?>
			<?php if($showLogo): ?>
			<!--logo-->
			<div class="mixto-main-logo-ui">
                <?php if (!is_null($genericPageObject->welcomeLogoId)): ?>
                <img src="<?php echo wp_get_attachment_image_url($genericPageObject->welcomeLogoId); ?>" srcset="<?php echo wp_get_attachment_image_srcset($genericPageObject->welcomeLogoId); ?>" alt="logo" />
                <?php else: ?>
                <img src="<?php echo $genericPageObject->defaultLogo?>" alt="logo" />                        
                <?php endif; ?>				
			</div>
			<!--/logo-->
			<?php endif;?>


			<!--sections-->	
			<?php $c = 0; ?>		
            <?php if (!empty($genericPageObject->sectionsData)): ?>
	            <?php foreach($genericPageObject->sectionsData as $section): ?>	            	
	            	<?php self::renderSection($section, $genericPageObject->currencySymbol, $genericPageObject->currencyPosition, $c%2, $c, $showLogo, $c == sizeof($genericPageObject->sectionsData) - 1); ?>
	            	<?php $c++; ?>
	            <?php endforeach; ?>                                            
            <?php endif; ?>			
			<!--/sections-->


		</div>
		<?php
	}

	//render each section
	protected static function renderSection($section, $currencySymbol, $currencyPosition, $modulo, $count, $showLogo, $isLastSection) {		
		$cssSectionBox = ($modulo) ? 'appetit-mixto-section-info-ui-right' : 'appetit-mixto-section-info-ui-left';
		$cssFirstSection = ($count == 0) ? ' appetit-mixto-section-ui-first' : '';
		$isLastSectionCSS = ($isLastSection) ? ' appetit-mixto-last-section' : '';
		?>
		<div class="appetit-mixto-section-ui<?php echo $cssFirstSection;?><?php echo $isLastSectionCSS;?>">
			<div class="appetit-mixto-section-header" style="background: url('<?php echo $section['section_image_src'];?>') no-repeat center center fixed; ">
				
				<!--section title ui -->
				<div data-stellar-ratio="1" class="appetit-mixto-section-info-ui <?php echo $cssSectionBox;?>">
					<div class="appetit-mixto-section-title-ui">
						<div class="appetit-mixto-section-title-container">							
							<div class="appetit-mixto-section-title-bordered">
								<span class="appetit-mixto-section-title-border-small mixto-border-small-top"></span>
								<div class="appetit-mixto-section-title"><?php echo $section['section_name'];?></div>
								<span class="appetit-mixto-section-title-border-small mixto-border-small-bottom"></span>
							</div>							
						</div>
					</div>					
				</div>
				<!--/section title ui -->
				
				<?php if($modulo): ?>
				<div class="mixto-clear"></div>
				<?php endif; ?>
			</div>
			<div class="appetit-mixto-section-items-content mixto-clearfix">
	            <?php if (!empty($section['section_items'])): ?>
		            <?php foreach($section['section_items'] as $item): ?>
		            	<?php self::renderItem($item, $currencySymbol, $currencyPosition); ?>
		            <?php endforeach; ?>      
	            <?php endif; ?>
	            <div class="mixto-clear"></div>				
			</div>
		</div>
		<?php
	}

	//render item
	protected static function renderItem($item, $currencySymbol, $currencyPosition) {		
		?>
		<div class="appetit-mixto-section-item-ui">
			<div class="appetit-mixto-section-item-ui-inside">
				<p class="appetit-mixto-item-title"><?php echo $item['menu_item_name']; ?></p>
				<p class="appetit-mixto-item-title appetit-mixto-item-title-price"><?php echo AppetitUtils::getFormatedPrice($item['price_input'], $currencySymbol, $currencyPosition); ?></p>
				<div class="mixto-clearfix"></div>
				<div class="appetit-mixto-item-title-line"></div>
				<img class="appetit-mixto-item-thumb" src="<?php echo $item['menu_img_src']; ?>" alt="<?php echo $item['menu_item_name']; ?>">
				<div class="appetit-mixto-item-description"><?php echo $item['item_small_description']; ?></div>
				<div class="mixto-clearfix"></div>
			</div>
		</div>
		<?php
	}	
}
?>