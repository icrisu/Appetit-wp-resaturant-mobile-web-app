    <div class="view appetit-content-view" id="mainview" data-currency-symbol="<?php echo $this->currencySymbol;?>" data-currency-position="<?php echo $this->currencyPosition;?>">

        <!--pages-->
        <div class="pages">
 
             <!--home page-->
            <div class="panel" id="main" data-selected="true">
                <div class="panel-content">                    
                    <div class="app-mobile-logo_ui">
                        <?php if (!is_null($this->welcomeLogoId)): ?>
                        <img src="<?php echo wp_get_attachment_image_url($this->welcomeLogoId); ?>" srcset="<?php echo wp_get_attachment_image_srcset($this->welcomeLogoId); ?>" alt="logo" />
                        <?php else: ?>
                        <img src="<?php echo $this->defaultLogo?>" alt="logo" />                        
                        <?php endif; ?>
                    </div>
                    <div class="app-mobile-welcome-text"><?php echo wpautop($this->welcomeAbout);?></div>
                    <div class="app-mobile-welcome-button-label"><a class="appetit-mobile-base-button" href="#menu-categories"><?php echo $this->welcomeLabel;?><span class="appetit-icon-angle-right appetit-mobile-base-button-icon"></span></a></div>
                </div>
            </div>
            <!--/home page-->
            
            <!--menu categories-->
            <div class="panel" id="menu-categories">
                <header>
                    <a id="backButton" href="#" onclick="$.afui.goBack();;" class="app-mobile-button app-mobile-button-back" ><span class="appetit-icon-arrow-left2"></span></a>
                    <h1>Menu categories</h1>
                </header>

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
                <header>
                    <a id="backButton" href="#" onclick="$.afui.goBack();;" class="app-mobile-button app-mobile-button-back" ><span class="appetit-icon-arrow-left2"></span></a>
                    <h1 id="category-title"></h1>
                </header>
                <div class="panel-content category-content"></div>
            </div>
            <!--/category page-->
            
            <!--item page-->
            <div class="panel" id="item">
                <header>
                    <a id="backButton" href="#" onclick="$.afui.goBack();;" class="app-mobile-button app-mobile-button-back" ><span class="appetit-icon-arrow-left2"></span></a>
                    <h1 id="category-title">Item</h1>
                </header>
                <div class="panel-content"></div>
            </div>
            <!--/item page-->
 
        </div>
        <!--/pages-->
    </div>