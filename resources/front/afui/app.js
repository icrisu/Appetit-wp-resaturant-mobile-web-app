'use strict';

var AppetitMobileApp = function() {}

//views helper
AppetitMobileApp.prototype.views = {

	//category view
	CategoryView: function(data, context) {
		this.data = data;
		this.context = context;

		this.buidPrice = function(price) {
			return this.context.buildPrice(price);
		}	

		this.render = function() {
			var itemsHTML = '';
			var tempAnchorId = '_a_' + this.context.generateUID();
			for (var i = 0; i < this.data.length; i++) {				
				itemsHTML += [
					'<li>',
						'<a style="display: none;" id="' + tempAnchorId + '" href="#item"></a>',
						'<div class="appetit-list-item" onclick="AppetitMobile.openItem(\'' + this.data[i].menu_item_id + '\', \'' + tempAnchorId + '\');">',
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
	},

	//item view
	ItemView: function(itemData, context) {
		this.itemData = itemData;
		this.context = context;

		this.buidPrice = function(price) {
			return this.context.buildPrice(price, true);
		}

		this.buildSaveToOrderController = function(isInCover) {
			return [
				'<div id="appetit-item-add-to-cart-controller" class="appetit-item-amount-controler-ui' + ((isInCover) ? ' appetit-item-amount-controler-in-cover' : '') + '">',
					'<div class="appetit-item-amount-controler pull-left">',
						'<a class="appetit-item-amont-btn appetit-item-minus pull-left" href="#"><span class="appetit-icon-minus"></span></a>',
						'<div class="appetit-item-count pull-left">1</div>',
						'<a class="appetit-item-amont-btn appetit-item-plus pull-left" href="#"><span class="appetit-icon-plus"></span></a>',																		
						'<div class="clear-fx"></div>',
					'</div>',
					'<a class="appetit-mobile-base-button pull-left appetit-mobile-save-item-btn" href="#item"><span class="appetit-mobile-save-icon appetit-icon-floppy-o"></span>' + this.context.labels.saveLabel + '</a>',
					'<div class="clear-fx"></div>',
				'</div>'
			].join('');
		}

		this.buildWithImage = function() {
			var saveToOrderControllerUI = '';
			if (this.context.isSaveToOrderAllowed) {
				saveToOrderControllerUI = this.buildSaveToOrderController(true);
			}
			return ['<div class="appetit-mobile-item-img-ui">',
						'<div>',
							'<img class="appetit-mobile-item-image" src="' + this.itemData.menu_img_src + '" srcset="' + this.itemData.menu_img_srcset + '" alt="" />',
						'</div>',
						'<div class="appetit-mobile-item-img-ui-cover">',
							'<p class="appetit-mobile-item-img-ui-title">' + this.itemData.menu_item_name + '</p>',
							'<p class="appetit-mobile-item-img-ui-categ">' + this.itemData.sectionName + '</p>',
							saveToOrderControllerUI,
						'</div>',
					'</div>'
			].join('');
		}

		this.render = function() {
			var itemImgPart = '';
			var saveToOrderControllerUI = '';
			if (this.itemData.menu_img_src) {
				itemImgPart = this.buildWithImage();
			} else {
				saveToOrderControllerUI = '<div class="panel-content">' + this.buildSaveToOrderController(false) + '</div>';
			}
			return [
				itemImgPart,
				'<div class="appetit-mobile-item-price-ui">',
					'<p>' + this.buidPrice(this.itemData.price_input) + '</p>',
				'</div>',
				saveToOrderControllerUI,
				'<div class="panel-content item-content-description">',
					'<div>' + this.itemData.item_small_description + '</div>',
				'</div>',
			].join('');
		}
		return this;
	},

	CartView: function(Cart, context) {
		this.Cart = Cart;
		this.context = context;
		this.el = $('#cart .cart-content');

		this.removeFromCart = function(cid) {
			this.el.find('.appetit-list-item-order-active').each(function(indx) {
				if ($(this).attr('data-cid') == cid) {
					var toBeRemoved = $(this).parent();
					toBeRemoved.animate({
						height: 0
					}, 150, function() {
						toBeRemoved.remove();
					});
				}
			});
			this.Cart.getInstance().removeFromCart(cid);
			this.updateCartInfo();
			if (this.Cart.getInstance().getAllItems() == 0) {
				this.render();
			}
		}

		this.buidPrice = function(price) {
			return this.context.buildPrice(price, true, 'displaytotal');
		}

		this.buildCurrentOrder = function(data) {
			if (!data) {
				return '';
			}
			var itemsHTML = '';
			var total = 0;
			for (var i = 0; i < data.length; i++) {
				itemsHTML += [
					'<li>',
						'<div class="appetit-list-item-order-active" data-cid="' + data[i].cid + '">',
							'<div class="appetit-list-item-thumb pull-left">',
								'<img src="' + data[i].menu_img_src + '" alt="" />',
							'</div>',
							'<div class="appetit-list-item-about pull-left">',
								'<p class="appetit-list-item-name">' + data[i].menu_item_name + '</p>',								
								'<p class="appetit-list-item-price">' + this.buidPrice(data[i].price_input) + '</p>',
							'</div>',
							'<div class="pull-right remove-order-item-ui">',
								'<a onclick="AppetitMobile.removeFromCart(\'' + data[i].cid + '\');" class="remove-order-item-btn" href="#"><span class="appetit-icon-cross"></span></a>',
							'</div>',
							'<div class="clear-fx"></div>',
						'</div>',
					'</li>'
				].join('');
				total += parseFloat(data[i].price_input);
			}
			return [
				'<div class="order-block">',
					'<p class="appetit-mobile-order-title">Opened order: ' + this.buidPrice(total.toFixed(2)) + '</p>',
					'<ul class="list inset">',
						itemsHTML,
					'</ul>',
				'<div>',
			].join('');
		}

		this.render = function() {
			this.el.empty();
			var existingCartData = this.Cart.getInstance().loadExistingData();
			console.log(existingCartData);

			if (!existingCartData || (existingCartData instanceof Array && existingCartData.length == 0)) {
				this.el.append($('<p class="appetit-mobile-cart-info">' + this.context.labels.noSavedOrders + '</p>'));
			} else {
				this.el.append($(this.buildCurrentOrder(this.Cart.getInstance().getCurrentItems())));
			}
			return this;
		}

		//update cart info
		this.updateCartInfo = function() {
			var currentCartOpenItems = this.Cart.getInstance().getAllItems().length;
			if (currentCartOpenItems == 0) {
				$('#cartButton .cart-info').hide();
			} else {
				$('#cartButton .cart-info').show();
			}
			$('#cartButton .cart-info').html(currentCartOpenItems);

			this.animatecart = function(value, stop) {
				$('#cartButton .cart-info').animate({
					width: value,
					height: value
				}, 150, function() {
					if (stop) {
						return;
					}
					this.animatecart(20, true);
				}.bind(this));
			}
			this.animatecart(23);
		}

		this.updateCartInfo();

		this.addToCart = function(objectItem, times, context) {
			this.Cart.getInstance().addToCart(objectItem, times, context);
			this.updateCartInfo();
			this.render();			
		}
	}	
}

//populate category helper
AppetitMobileApp.prototype.populateCategory = function(dataObj) {
	
	var sectionTitle = dataObj.section_name || 'Section name';
	var sectionItems = dataObj.section_items || [];
	var section_img_id = dataObj.section_img_id || '';

	$('#category').attr('data-title', sectionTitle);
	var categoryContentUI = $('#category').find('.category-content');
	categoryContentUI.empty();
	categoryContentUI.append(new this.views.CategoryView(sectionItems, this).render());
};

//open item panel helper (ppulate with data)
AppetitMobileApp.prototype.openItem = function(itemID, tempAnchorId) {
	var itemContentUI = $('#item').find('.item-content');
	itemContentUI.empty();
	var itemData = this.getItem(itemID);
	$('#item').attr('data-title', itemData.menu_item_name);
	itemContentUI.append(new this.views.ItemView(itemData, this).render());
	$.afui.loadContent("#item", false, false, 'slide-right', '#' + tempAnchorId);
	new this.AddToCartController('appetit-item-add-to-cart-controller', this, itemData);
};

//open cart ui
AppetitMobileApp.prototype.beforeCartOpen = function(first_argument) {
	this.cartView.render();
};

//build price helper
AppetitMobileApp.prototype.buildPrice = function(price, isStrong, label) {
	var priceLabel = this.labels.priceLabel;
	if (label) {
		if (label === 'displaytotal') {
			priceLabel = this.labels.priceLabelTotal;
		}
	}
	if (this.currencyPositionAfter) {
		if (isStrong) {
			return '<span class="strong_price">' + priceLabel + '</span>: ' + price + '' + this.currencySymbol;
		} else {
			return priceLabel + ': ' + price + '' + this.currencySymbol;
		}		
	} else {
		if (isStrong) {
			return '<span class="strong_price">' + priceLabel + '</span>: ' + this.currencySymbol + '' + price;
		} else {
			return priceLabel + ': ' + this.currencySymbol + '' + price;
		}		
	}
};

AppetitMobileApp.prototype.handleData = function() {
	this.isSaveToOrderAllowed = true;
	this.labels = {};
	this.labels.priceLabel = 'Price';
	this.labels.priceLabelTotal = 'Total';
	this.labels.saveLabel = 'Save';
	this.labels.noSavedOrders = 'There are no saved orders';

	this.currencySymbol = $('.appetit-content-view').data().currencySymbol || '$';
	this.currencyPositionAfter = parseInt($('.appetit-content-view').data().currencyPosition, 10) || 0;	

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

//router
AppetitMobileApp.prototype.router = function() {

	this.handleMain = function() {
		if (window.location.hash == '#main') {
			$('#app-header').hide();
		} else {
			$('#app-header').show();
		}
		if (window.location.hash == '#cart') {
			$('#app-header .back-icon').removeClass('appetit-icon-arrow-left2');
			$('#app-header .back-icon').addClass('appetit-icon-cross');			
		} else {
			$('#app-header .back-icon').removeClass('appetit-icon-cross');
			$('#app-header .back-icon').addClass('appetit-icon-arrow-left2');
		}		
	}

	$(window).on('hashchange', function(event) {
		this.handleMain();
	}.bind(this));

	this.handleMain();
};


AppetitMobileApp.prototype.generateUID = function() {
    return Math.random().toString(36).substring(2, 15) + Math.random().toString(36).substring(2, 15);
};

AppetitMobileApp.prototype.removeFromCart = function(cid) {
	this.cartView.removeFromCart(cid);
};

//cart helper - orders
AppetitMobileApp.prototype.Cart = {
	
	instance: null,

	Cart: function() {
		this.items = [];
		this.firstDataLoad = false;
		this.uidStorage = '32632987892eydhuahjs298';

		this.getAllItems = function() {
			if (!this.firstDataLoad) {
				this.loadExistingData();
			}
			return this.items;
		}

		//add item/s to cart
		this.addToCart = function(objectItem, times, context) {
			for (var i = 0; i < parseInt(times, 10); i++) {
				var newObjectItem = jQuery.extend({}, objectItem);
				newObjectItem.isUnclosed = true;
				newObjectItem.cid = '_cid_' + context.generateUID() + i;
				this.items.push(newObjectItem);
			}
			this.saveData();
		}

		//get current items
		this.getCurrentItems = function() {
			var unclosedItems = [];
			for (var i = this.items.length - 1; i >= 0; i--) {
				if (this.items[i].isUnclosed) {
					unclosedItems.push(this.items[i]);
				} else {
					break;
				}
			}
			if (unclosedItems.length ==0) {
				return null;
			} else {
				return unclosedItems;
			}
		}

		this.loadExistingData = function() {
			if (typeof(Storage) === "undefined") {
				return;
			}
			this.firstDataLoad = true;
			var raw = localStorage.getItem(this.uidStorage);
			if (raw) {
				try {
					this.items = JSON.parse(raw);
				} catch (e) {
					alert('error, can not parse JSON');
					alert(e);
				}
				return this.items;
			} else {
				return null;
			}
		}

		this.removeFromCart = function(cid) {
			for (var i = this.items.length - 1; i >= 0; i--) {
				if (this.items[i].isUnclosed && this.items[i].cid == cid) {					
					this.items.splice(i, 1);
					break;
				}
			}
			this.saveData();		
		}

		this.closeOrder = function() {

		}

		this.saveData = function() {
			if (typeof(Storage) === "undefined" && this.items.length != 0) {
				return;
			}
			localStorage.setItem(this.uidStorage, JSON.stringify(this.items));
		}
	},
	
	getInstance: function() {
		if (!this.instance) {
			this.instance = new this.Cart();
		}
		return this.instance;
	}	
}

//add to cart controller
AppetitMobileApp.prototype.AddToCartController = function(controllerID, context, itemData) {
	this.itemData = itemData;
	this.context = context;
	this.controllerID = controllerID;
	this.controllerUI = $('#' + controllerID);
	this.plusBTN = $('#' + controllerID + ' .appetit-item-plus');
	this.minusBTN = $('#' + controllerID + ' .appetit-item-minus');
	this.inputUI = $('#' + controllerID + ' .appetit-item-count');
	this.saveBTN = $('#' + controllerID + ' .appetit-mobile-save-item-btn');

	this.currentValue = 1;
	this.maxValue = 5;

	this.add = function() {
		if (this.currentValue > this.maxValue - 1 ) {
			return;
		}
		this.currentValue++;
		this.updateValue();
	}

	this.substract = function() {
		if (this.currentValue < 2) {
			return;
		}
		this.currentValue--;
		this.updateValue();
	}

	this.updateValue = function() {
		this.inputUI.html(this.currentValue);
	}

	this.save = function() {
		this.context.cartView.addToCart(this.itemData, this.currentValue, this.context);
		this.currentValue = 1;
		this.updateValue();
	}

	this.plusBTN.click(function() {
		this.add();
	}.bind(this));

	this.minusBTN.click(function() {
		this.substract();
	}.bind(this));

	this.saveBTN.click(function() {
		this.save();
	}.bind(this));

	this.updateValue();

};

//init
AppetitMobileApp.prototype.init = function() {
	this.handleData();
	this.router();
	this.cartView = new this.views.CartView(this.Cart, this).render();

	var obj = [];
	for (var i = 0; i < 2000; i++) {
		obj.push({
			itemName: '___name___' + this.generateUID()
		});
	}
	console.log(JSON.stringify(obj));
};
