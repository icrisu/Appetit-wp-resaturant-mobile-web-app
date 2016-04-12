'use strict';

var AViews = function() {
	return {

		//section view
		SectionView: function(AppetitAdmin) {

			this.AppetitAdmin = AppetitAdmin;
			this.menuItems = [];
			this.cid = SakuraPlugins.utils.generateUID();

			this.buildHtml = function(data) {
				return [
				'<div class="appetit_section">',
				    '<h3 class="section-header"><span>|  </span><span class="m_title"> ' + data.sectionName + ' </span> <span class="appetit-move a-pull-right"></span><span style="margin-right: 10px;" class="sectionRemoveBTN appetit-trashcan2 a-pull-right"></span></h3>',
				    '<div class="clearfix">',
					    '<div class="section-content-header">',
					    	'<input class="generic_input one-third section_name" placeholder="Section name" type="text" value="'+ data.sectionName +'" />',
					    	'<div class="section_img_ui"></div>',
					    	'<input class="section_img_id" type="hidden" />',
					    	'<a class="base-button sectionImageBTN" href="#"><span class="appetit-upload"></span>Upload section image</a>',
					    	'<a class="base-button addMenuItemBTN a-pull-right" href="#"><span class="appetit-plus"></span>Add menu item</a>',
					    	'<div class="clearfix"></div>',
					    '</div>',
					    '<div class="section-space-line"></div>',
					    '<div class="section_content">',
					    	'<div class="menu_items">',
					    	'</div>',
					    '</div>',			    
				    '</div>',
				'</div>'
				].join('');
			}

			this.setData = function(data) {
				this.el = jQuery(this.buildHtml(data));
			}

			//useing set element for existing sections
			this.setElement = function(elUI) {
				this.el = elUI;
			}

			this.renderTo = function(UI) {
				UI.append(this.el);
				return this;
			}

			this.init = function() {
				this.addEvents();
				this.initJQUI();
				this.initExistingMenuItems();		
			}

			this.initExistingMenuItems = function() {
				if (this.el.find('.menu_item_ui').length == 0) {
					return;
				}
				var _self = this;
				this.el.find('.menu_item_ui').each(function(index) {
					_self.addMenuItemFromExistingElement(jQuery(this));
				});
			}

			this.addEvents = function() {
				var _self = this;
				this.el.find('.sectionImageBTN').click(_.bind(function(e) {
					e.preventDefault();
					SakuraPlugins.utils.openMedia(false, _.bind(function(data) {
						if (data[0]) {
							this.el.find('.section_img_ui').html('<img src="' + data[0].iconUrl + '" alt="" />');
							this.el.find('.section_img_id').val(data[0].id);
						}
					}, this));
				}, this));

				this.el.find('.sectionRemoveBTN').click(_.bind(function(e) {
					e.preventDefault();
					if (confirm('Are you sure you want to remove this section?')) {
						_self.el.remove();
						_self.AppetitAdmin.handleSectionRemove(_self);
					}					
				}, this));

				this.el.find('.section_name').on('input', _.bind(function() {
					this.el.find('.m_title').html(this.el.find('.section_name').val());		
				}, this));	

				this.el.find('.addMenuItemBTN').click(_.bind(function(e) {
					e.preventDefault();
					var popup = new AppetitViews.SectionNamePopup(_.bind(function(val) {
						this.addMenuItem(val);
					}, this));
					popup.show({
						name: 'Menu item name',
						example: 'Menu item name. Ex: Sour Cream',
						placeholder: 'item name',
						alert: 'Please enter an item name!'
					});				
				}, this));																			
			}

			this.handleItemRemove = function(itemView) {
				for (var i = 0; i < this.menuItems.length; i++) {
					if (this.menuItems[i].cid === itemView.cid) {
						this.menuItems.splice(i, 1);
						this.refreshMenu();
						break;
					}
				}
			}

			this.addMenuItem = function(val) {
				var menuItem = new AppetitViews.MenuItemView(this);
				menuItem.setData({ itemName : val });
				menuItem.renderTo(this.el.find('.menu_items'));
				menuItem.init();
				this.refreshMenu();
				this.menuItems.push(menuItem);
			}

			this.addMenuItemFromExistingElement = function(itemUI) {
				var menuItem = new AppetitViews.MenuItemView(this);
				menuItem.setElement(itemUI);
				menuItem.init();
				this.refreshMenu();
				this.menuItems.push(menuItem);
			}

			this.initJQUI = function() {
				this.el.find('.menu_items').accordion({
					header: "> div > h3",
					'heightStyle': 'content',
					'collapsible': true,
					animate: {
					    duration: 200
					}			
				});
				
				this.el.find('.menu_items').sortable({
			        axis: "y",
			        handle: "h3",
					start: function(event, ui) {
					    ui.item.data('start_pos', ui.item.index());
					},
				    stop: _.bind(function(event, ui) {
				        var start_pos = ui.item.data('start_pos');		        
				        if (start_pos != ui.item.index()) {
				            // the item got moved
				            this.handleSort(parseInt(start_pos, 10), parseInt(ui.item.index(), 10));
				        }	        
				    }, this)			        				        		
				});				
			}

			this.handleSort = function(from, to) {
				this.menuItems.splice(to, 0, this.menuItems.splice(from, 1)[0]);
			}

			this.refreshMenu = function() {
				this.el.find('.menu_items').accordion('refresh');
				this.el.find('.menu_items').sortable('refresh');				
			}

			this.serialize = function() {
				var menuItemsData = [];
				_.each(this.menuItems, function(menuItem) {
					menuItemsData.push(menuItem.serialize());
				});
				return {
					section_name : this.el.find('.section_name').val(),
					section_img_id : this.el.find('.section_img_id').val(),
					section_items : menuItemsData
				};
			}
		},
		//end section view

		//menu item view
		MenuItemView: function(SectionView) {

			this.SectionView = SectionView;
			this.cid = SakuraPlugins.utils.generateUID();

			this.buildHtml = function(data) {
				return [
				'<div class="menu_item_ui">',
				    '<h3 class="section-header"><span>| </span><span class="menu_title"> ' + data.itemName + ' </span> <span class="appetit-move a-pull-right"></span><span style="margin-right: 10px;" class="menuRemoveBTN appetit-trashcan2 a-pull-right"></span></h3>',
				    '<div class="clearfix">',
					    '<div class="section-content-header">',
					    	'<span class="input_label_prepend">Name</span><input class="generic_input one-third input_margin menu_item_name" placeholder="Item name" type="text" value="'+ data.itemName +'" />',
					    	'<span class="input_label_prepend">Price</span><input class="generic_input price_input" type="number" min="0" />',
					    	'<div class="menu_img_ui"></div>',
					    	'<input class="menu_img_id" type="hidden" />',
					    	'<a class="base-button menuImageBTN" href="#"><span class="appetit-upload"></span>Upload image</a>',					    	
					    	'<div class="clearfix"></div>',
					    	'<textarea class="item_small_description" placeholder="small description"></textarea>',
					    '</div>',
				    '</div>',
				'</div>'
				].join('');
			}

			this.setData = function(data) {
				this.el = jQuery(this.buildHtml(data));
			}

			this.setElement = function(ui) {
				this.el = ui;
			}

			this.renderTo = function(UI) {
				UI.append(this.el);
				return this;
			}

			this.init = function() {
				this.addEvents();
			}

			this.addEvents = function() {
				var _self = this;
				this.el.find('.menuImageBTN').click(_.bind(function(e) {
					e.preventDefault();
					SakuraPlugins.utils.openMedia(false, _.bind(function(data) {
						if (data[0]) {
							this.el.find('.menu_img_ui').html('<img src="' + data[0].iconUrl + '" alt="" />');
							this.el.find('.menu_img_id').val(data[0].id);
						}
					}, this));
				}, this));

				this.el.find('.menuRemoveBTN').click(_.bind(function(e) {
					e.preventDefault();
					if (confirm('Are you sure you want to remove this item?')) {
						_self.el.remove();
						this.SectionView.handleItemRemove(_self);
					}					
				}, this));

				this.el.find('.menu_item_name').on('input', _.bind(function() {
					this.el.find('.menu_title').html(this.el.find('.menu_item_name').val());		
				}, this));																				
			}			

			this.serialize = function() {
				return {
					menu_item_name: this.el.find('.menu_item_name').val(),
					price_input: this.el.find('.price_input').val(),
					menu_img_id: this.el.find('.menu_img_id').val(),	
					item_small_description: this.el.find('.item_small_description').html()
				};
			}			
		},
		//end menu item view	

		//section name popup
		SectionNamePopup: function(callback) {

			this.callback = callback;
			this.buildHtml = function(data) {
				if (!data) {
					data = {};
				}
				return [
					'<div class="popup-overlay">',
						'<div class="popup-body">',
							'<div class="popup-header">',
								'<span class="a-pull-left">' + (data.name || 'Section name') + '</span><span class="popupCloseBTN a-pull-right appetit-cross"></span>',
								'<div class="clearfix"></div>',
							'</div>',
							'<div class="popup-content">',
								'<p>' + (data.example || 'Menu section name. Ex: (Breakfast)') + '</p>',
								'<input class="section_name_popup_input generic_input" type="text" placeholder="' + (data.placeholder || 'section name') + '" />',
							'</div>',
							'<div class="popup-bottom">',
								'<a class="popupOkBTN popup-button a-pull-right" href=#>OK</a>',
								'<div class="clearfix"></div>',							
							'</div>',
						'</div>',
					'</div>'
				].join('');
			}

			this.show = function(data) {
				this.dataObj = data;
				this.popupUI = jQuery(this.buildHtml(data));
				this.popupUI.css('opacity', 0);
				jQuery('body').append(this.popupUI);
				this.popupUI.animate({
					opacity: 1,
				}, 200);
				this.popupUI.find('.popup-body').animate({
					width: 500
				}, 150);
				this.popupUI.find('.popupOkBTN').click(_.bind(function(e) {
					e.preventDefault();
					this.okEvent();
				}, this));
				this.popupUI.find('.popupCloseBTN').click(_.bind(function(e) {
					e.preventDefault();
					this.close();
				}, this));	
				this.popupUI.find('.section_name_popup_input').focus();

				this.popupUI.keydown(_.bind(function(e) {
			        var code = e.keyCode || e.which;
			        if(code==13)
			        	this.okEvent();
			        if(code==27)
			        	this.close();
				}, this));										
			}

			this.okEvent = function() {
				var val = this.popupUI.find('.section_name_popup_input').val();
				if (val === '') {
					alert((this.dataObj.alert || 'Please enter a section name!'));
					return;
				}	
				this.callback(val);				
				this.close();
			}

			this.close = function() {
				var val = this.popupUI.find('.section_name_popup_input').val();
				this.popupUI.find('.popup-body').html('');
				this.popupUI.animate({
					opacity: 0,
				}, 200);				
				this.popupUI.find('.popup-body').animate({
					width: 1
				}, 200, _.bind(function() {
					this.popupUI.remove();					
				}, this));								
			}
		}
		//end section name popup

	}
}
var AppetitViews = new AViews();