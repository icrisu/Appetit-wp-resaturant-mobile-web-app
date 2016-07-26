'use strict';

var AppetitMobileApp = function() {
	this.currencySymbol = $('.appetit-content-view').data().currencySymbol || '$';
	this.currencyPositionAfter = parseInt($('.appetit-content-view').data().currencyPosition, 10) || 0;
}

AppetitMobileApp.prototype.views = {
	CategoryView: function(data, context) {
		this.data = data;
		this.context = context;

		this.buidPrice = function(price) {
			if (this.context.currencyPositionAfter) {
				return 'Price: ' + price + '' + this.context.currencySymbol;
			} else {
				return 'Price: ' + this.context.currencySymbol + '' + price;
			}
		}

		this.renderDummy = function() {
			var itemsHTML = '';
			for (var i = 0; i < this.data.length; i++) {
				itemsHTML += [
					'<li>',
						'<div class="appetit-list-item">',
							'<div class="appetit-list-item-thumb pull-left">',
								'<img src="' + this.data[i].menu_img_src + '" alt="" />',
							'</div>',
							'<div class="appetit-list-item-about pull-left">',
								'<p class="appetit-list-item-name">' + this.data[i].menu_item_name + '</p>',								
								'<p class="appetit-list-item-price">' + this.buidPrice(this.data[i].price_input) + '</p>',
								'<div class="appetit-item-amount-controler-ui">',
									'<div class="appetit-item-amount-controler pull-left">',
										'<a class="appetit-item-amont-btn appetit-item-minus pull-left" href="#"><span class="appetit-icon-minus"></span></a>',
										'<div class="appetit-item-count pull-left">1</div>',
										'<a class="appetit-item-amont-btn appetit-item-plus pull-left" href="#"><span class="appetit-icon-plus"></span></a>',																		
										'<div class="clear-fx"></div>',
									'</div>',
									'<a class="appetit-mobile-base-button pull-left" href="#item">Save<span class="appetit-icon-angle-right appetit-mobile-base-button-icon"></span></a>',
									'<div class="clear-fx"></div>',
								'</div>',
							'</div>',
							'<div class="clear-fx"></div>',
						'</div>',
					'</li>'
				].join('');				
			}
			return [
				'<ul class="list inset">',
					itemsHTML,
				'</ul>'
			].join('');
		}		

		this.render = function() {
			var itemsHTML = '';
			for (var i = 0; i < this.data.length; i++) {				
				itemsHTML += [
					'<li>',
						'<div class="appetit-list-item" onclick="AppetitMobile.openItem(\'' + this.data[i].menu_item_id + '\');">',
							'<div class="appetit-list-item-thumb pull-left">',
								'<img src="' + this.data[i].menu_img_src + '" alt="" />',
							'</div>',
							'<div class="appetit-list-item-about pull-left">',
								'<p class="appetit-list-item-name">' + this.data[i].menu_item_name + '</p>',								
								'<p class="appetit-list-item-price">' + this.buidPrice(this.data[i].price_input) + '</p>',
							'</div>',
							'<div class="clear-fx"></div>',
						'</div>',
					'</li>'
				].join('');				
			}
			return [
				'<ul class="list inset">',
					itemsHTML,
				'</ul>'
			].join('');
		}
		return this;
	}
}

AppetitMobileApp.prototype.populateCategory = function(dataObj) {
	
	var sectionTitle = dataObj.section_name || 'Section name';
	var sectionItems = dataObj.section_items || [];
	var section_img_id = dataObj.section_img_id || '';

	$('#category-title').html(sectionTitle);
	var categoryContentUI = $('#category').find('.category-content');
	categoryContentUI.empty();
	categoryContentUI.append(new this.views.CategoryView(sectionItems, this).render());
};

AppetitMobileApp.prototype.handleData = function() {
	try {
		this.itemsData = JSON.parse($('#items_data').html());
	} catch (e) {
		throw e;
	}
};

AppetitMobileApp.prototype.getItemsBySection = function(sectionId) {
	var out = [];
	for (var i = 0; i < this.itemsData.length; i++) {
		if (this.itemsData[i].inSection == sectionId) {
			out.push(this.itemsData[i]);
		}
	}
	return out;
};

AppetitMobileApp.prototype.getItem = function(itemId) {
	var out = null;
	for (var i = 0; i < this.itemsData.length; i++) {
		if (this.itemsData[i].menu_item_id == itemId) {
			out = this.itemsData[i];
			break;
		}
	}
	return out;
};

AppetitMobileApp.prototype.openSection = function(sectionId, sectionName) {	
	this.populateCategory({
		section_name: sectionName,
		section_items: this.getItemsBySection(sectionId)
	});
};

AppetitMobileApp.prototype.openItem = function(itemID) {
	$.afui.loadContent("#item", false, false, 'up-reveal', '#test');
};

AppetitMobileApp.prototype.init = function() {
	this.handleData();
};