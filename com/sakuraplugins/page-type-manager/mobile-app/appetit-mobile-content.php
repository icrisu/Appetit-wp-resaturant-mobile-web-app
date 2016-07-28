    <div class="view appetit-content-view" id="mainview" data-currency-symbol="<?php echo $this->currencySymbol;?>" data-currency-position="<?php echo $this->currencyPosition;?>" data-disable-save-order="<?php echo $this->disable_save_to_order;?>">

        <header id="app-header">
            <a id="backButton" href="#" onclick="$.afui.goBack();" class="app-mobile-button app-mobile-button-back" ><span class="back-icon appetit-icon-arrow-left2"></span></a>
            <h1></h1>
            <?php if (!$this->disable_save_to_order): ?>
            <a id="cartButton" href="#cart" data-transition="up-reveal" onclick="AppetitMobile.beforeCartOpen();" class="app-mobile-button app-mobile-button-cart"><span class="appetit-icon-cart"></span><span class="cart-info">1</span></a>
            <a id="closeCartButton" href="#" onclick="$.afui.goBack();" class="app-mobile-button app-mobile-button-back pull-right" ><span class="back-icon appetit-icon-cross"></span></a>            
            <?php endif; ?>
        </header>

        <!--pages-->
        <div class="pages">
 
             <!--home page-->
            <div class="panel" id="main" data-selected="true" data-title="">
                <div class="panel-content">
                    <div class="app-mobile-logo_ui">
                        <?php if (!is_null($this->welcomeLogoId)): ?>
                        <img src="<?php echo wp_get_attachment_image_url($this->welcomeLogoId); ?>" srcset="<?php echo wp_get_attachment_image_srcset($this->welcomeLogoId); ?>" alt="logo" />
                        <?php else: ?>
                        <img src="<?php echo $this->defaultLogo?>" alt="logo" />                        
                        <?php endif; ?>
                    </div>
                    <div class="app-mobile-welcome-text"><?php echo wpautop($this->welcomeAbout);?></div>
                    <div class="app-mobile-welcome-button-label"><a class="appetit-mobile-base-button" href="#menu-categories" data-transition="pop"><?php echo $this->welcomeLabel;?><span class="appetit-icon-angle-right appetit-mobile-base-button-icon"></span></a></div>
                </div>
            </div>
            <!--/home page-->
            
            <!--menu categories-->
            <div class="panel" id="menu-categories" data-title="<?php echo $this->appetitLabels['menu_cat_label']; ?>">

                <div class="panel-content">
                    <ul class="app-mobile-categories-list list inset">
                        <?php if (!empty($this->sectionsData)): ?>
                        <?php foreach($this->sectionsData as $section): ?>
                            <?php
                                $sectionName = isset($section['section_name']) ? $section['section_name'] : 'No section name';
                            ?>
                            <li>
                                <a class="open_section_btn" onclick="AppetitMobile.openSection('<?php echo $section['menu_section_id']; ?>', '<?php echo $sectionName; ?>')" href="#category"><?php echo $sectionName;?></a>
                            </li>
                        <?php endforeach; ?>                                            
                        <?php else: ?>
                            <p>There are no sections to this menu, please add sections from admin.</p>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
            <!--/menu categories-->
            
            <!--category page-->
            <div class="panel" id="category">
                <div class="panel-content category-content"></div>
            </div>
            <!--/category page-->
            
            <!--item page-->
            <div class="panel" id="item">
                <div class="item-content"></div>
            </div>
            <!--/item page-->

            <!--cart-->
            <div class="panel" id="cart" data-title="<?php echo $this->appetitLabels['menu_saved_orders_label']; ?>">
                <div class="panel-content cart-content"></div>
            </div>
            <!--/cart-->            
 
        </div>
        <!--/pages-->
    </div>