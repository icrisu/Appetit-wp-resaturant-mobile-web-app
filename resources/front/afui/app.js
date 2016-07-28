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
			if (this.data && this.data.length == '') {
				return 'empty ... no items added';
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
								'<a onclick="AppetitMobile.removeFromCart(\'' + data[i].cid + '\');" class="remove-order-btn remove-order-item-btn" href="#"><span class="appetit-icon-cross"></span></a>',
							'</div>',
							'<div class="clear-fx"></div>',
						'</div>',
					'</li>'
				].join('');
				total += parseFloat(data[i].price_input);
			}
			return [
				'<div class="order-block order-block-active">',
					'<p class="appetit-mobile-order-title pull-left">' + this.context.labels.openedOrder + ': ' + this.buidPrice(total.toFixed(2)) + '</p>',
					'<a href="#" onclick="AppetitMobile.closeOrder();" class="pull-right appetit-close-order-btn"><span class="appetit-icon-cross"></span>' + this.context.labels.closeOrder + '</a>',
					'<div class="clear-fx"></div>',
					'<ul class="list inset">',
						itemsHTML,
					'</ul>',
				'<div>',
			].join('');
		}

		//build orders UI
		this.buildOrdersUI = function(ordersData) {
			var pastOrdersHTML = '';

			for (var i = 0; i < ordersData.length; i++) {
				var orderItemsHTML = '';

				var orderItems = ordersData[i].items;
				for (var k = 0; k < orderItems.length; k++) {

					orderItemsHTML += [
						'<div class="order-item-info">',
							'<p>' + orderItems[k].menu_item_name + ' | ' + this.context.buildPrice(orderItems[k].price_input, 'no_label') +'</p>',
						'</div>',					
					].join('');
				}

				pastOrdersHTML += [
					'<li data-orderid="'+ ordersData[i].id +'" data-isclosed="true">',
						'<div class="pull-left">',
							'<p>' + ordersData[i].orderName + '</p>',
							'<p>' + this.buidPrice(ordersData[i].total) + '</p>',
						'</div>',
						'<div class="pull-right remove-order-item-ui">',
							'<a onclick="AppetitMobile.expandOrderItem(\'' + ordersData[i].id + '\');" class="remove-order-btn expand-order-btn pull-left" href="#"><span class="appetit-icon-plus"></span></a>',
							'<a onclick="AppetitMobile.colapseOrderColapse(\'' + ordersData[i].id + '\');" class="remove-order-btn colapse-order-btn pull-left" href="#"><span class="appetit-icon-minus"></span></a>',
							'<a onclick="AppetitMobile.deleteOrder(\'' + ordersData[i].id + '\');" class="remove-order-btn remove-order-btn-remove pull-left" href="#"><span class="appetit-icon-trashcan"></span></a>',							
							'<div class="clear-fx"></div>',
						'</div>',
						'<div class="clear-fx"></div>',
						'<div class="appetit-order-info-ui">',
							orderItemsHTML,
						'</div>',
					'</li>'
				].join('');
			}

			return [
				'<div class="appetit-past-orders-ui">',
					'<p class="appetit-mobile-order-title"><strong>' + this.context.labels.pastOrders + '</strong></p>',
					'<ul class="list inset">',
						pastOrdersHTML,
					'</ul>',
				'</div>'
			].join('');
		}

		this.animateOrderItemInfo = function(itemUI, height, opacity, callback) {
			itemUI.animate({
				height: height,
				opacity: opacity
			}, 200, function() {
				callback();
			});
		}

		this.colapseOrExpand = function(id) {
			var orderItem = this.el.find('[data-orderid="'+ id +'"]');
			var orderInfo = orderItem.find('.appetit-order-info-ui');
			if (!orderInfo.attr('data-initialheight')) {
				orderInfo.attr('data-initialheight', orderInfo.height());
			}

			var expandBtn = orderItem.find('.expand-order-btn');
			var colapseBtn = orderItem.find('.colapse-order-btn');

			if (orderItem.attr('data-isclosed') == 'true') {
				//open
				orderItem.attr('data-isclosed', 'false');
				orderInfo.show();
				expandBtn.hide();
				colapseBtn.show();				
				this.animateOrderItemInfo(orderInfo, parseFloat(orderInfo.attr('data-initialheight')) + 10 + 20, 1, function() {
				});
			} else {
				//close
				orderItem.attr('data-isclosed', 'true');
				expandBtn.show();
				colapseBtn.hide();				
				this.animateOrderItemInfo(orderInfo, 0, 0, function() {
					orderInfo.hide();
				});
			}			
		}

		//expand order item
		this.expandOrderItem = function(id) {
			this.colapseOrExpand(id);
		}

		//colapse order item
		this.colapseOrderColapse = function(id) {
			this.colapseOrExpand(id);
		}

		//delete order
		this.deleteOrder = function(id) {
			var _self = this;
			var orderItem = this.el.find('[data-orderid="'+ id +'"]');		
			if (confirm(this.context.labels.confirmDeleteOrder)) {
				this.animateOrderItemInfo(orderItem, 0, 0, function() {
					orderItem.remove();
					_self.Cart.getInstance().deleteOrder(id);
					if (_self.Cart.getInstance().getCurrentOrders().length == 0) {
						_self.render();
					}
				});				
			}
		}				

		this.render = function() {
			this.el.empty();
			var existingCartData = this.Cart.getInstance().getCurrentItems();

			if (!existingCartData || (existingCartData instanceof Array && existingCartData.length == 0)) {
				this.el.append($('<p class="appetit-mobile-cart-info">' + this.context.labels.noSavedOrders + '</p>'));
			} else {
				this.el.append($(this.buildCurrentOrder(existingCartData)));
			}

			//get past orders
			var ordersObj = this.Cart.getInstance().getCurrentOrders();			
			if (ordersObj && (ordersObj instanceof Array && ordersObj.length != 0)) {
				//build orders UI
				this.el.append($(this.buildOrdersUI(ordersObj)));
			}

			return this;
		}

		//update cart info
		this.updateCartInfo = function() {
			var currentCartOpenItems = this.Cart.getInstance().getCurrentItems();
			if (!currentCartOpenItems || (currentCartOpenItems instanceof Array && currentCartOpenItems.length == 0)) { 
				$('#cartButton .cart-info').hide();
			} else {
				$('#cartButton .cart-info').show();
			}
			$('#cartButton .cart-info').html(currentCartOpenItems.length);

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
			var removedItem = this.Cart.getInstance().removeFromCart(cid);			
			this.updateCartInfo();
			var currentItems = this.Cart.getInstance().getCurrentItems();
			if (!currentItems || (currentItems instanceof Array && currentItems.length == 0)) {
				this.render();
			} else {
				this.el.find('.appetit-mobile-order-title .appetit-price-value').html(this.Cart.getInstance().getTotal());
			}
		}		

		this.addToCart = function(objectItem, times, context) {
			this.Cart.getInstance().addToCart(objectItem, times, context);
			this.updateCartInfo();
			this.render();
		}

		this.closeOrder = function() {
			this.el.find('.order-block-active').animate({
				height: 0
			}, 300, function() {
				this.Cart.getInstance().closeOrder();
				this.render();
				this.updateCartInfo();
			}.bind(this));
		}

		this.Cart.getInstance().init(this.context);
		this.updateCartInfo();
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
AppetitMobileApp.prototype.beforeCartOpen = function() {
	this.cartView.render();
};

AppetitMobileApp.prototype.closeOrder = function() {
	this.cartView.closeOrder();
};

//build price helper
AppetitMobileApp.prototype.buildPrice = function(price, isStrong, label) {
	var priceLabel = this.labels.priceLabel;
	var labelSeparator = ': ';
	if (label) {
		if (label === 'displaytotal') {
			priceLabel = this.labels.priceLabelTotal;
		}
	}

	if (isStrong == 'no_label') {
		priceLabel = '';
		labelSeparator = '';
	}

	if (this.currencyPositionAfter) {
		if (isStrong && isStrong != 'no_label') {
			return '<span class="strong_price">' + priceLabel + '</span>: ' + '<span class="appetit-price-value">' + price + '</span>' + '' + this.currencySymbol;
		} else {
			return priceLabel + labelSeparator + '<span class="appetit-price-value">' + price + '</span>' + '' + this.currencySymbol;
		}		
	} else {
		if (isStrong && isStrong != 'no_label') {
			return '<span class="strong_price">' + priceLabel + '</span>: ' + this.currencySymbol + '' + '<span class="appetit-price-value">' + price + '</span>';
		} else {
			return priceLabel + labelSeparator + this.currencySymbol + '' + '<span class="appetit-price-value">' + price + '</span>';
		}		
	}
};

AppetitMobileApp.prototype.handleData = function() {
	this.isSaveToOrderAllowed = !parseInt($('.appetit-content-view').data().disableSaveOrder, 10);

	this.labels = {};
	this.labels.priceLabel = 'Price';
	this.labels.priceLabelTotal = 'Total';
	this.labels.saveLabel = 'Save';
	this.labels.noSavedOrders = 'There are no opened orders';
	this.labels.openedOrder = 'Opened order';
	this.labels.closeOrder = 'Close order';
	this.labels.pastOrders = 'Past orders';
	this.labels.confirmDeleteOrder = 'Are you sure you want to delete this order?';

	this.currencySymbol = $('.appetit-content-view').data().currencySymbol || '$';
	this.currencyPositionAfter = parseInt($('.appetit-content-view').data().currencyPosition, 10) || 0;	

	try {
		this.itemsData = JSON.parse($('#items_data').html());	
	} catch (e) {
		throw e;
	}

	try {
		this.labels = JSON.parse($('#labels_data').html());
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
			$('#app-header #cartButton').hide();
			$('#app-header #backButton').hide();
			$('#app-header #closeCartButton').show();
		} else {
			$('#app-header #cartButton').show();
			$('#app-header #backButton').show();
			$('#app-header #closeCartButton').hide();
		}		
	}

	$(window).on('hashchange', function(event) {
		this.handleMain();
	}.bind(this));

	this.handleMain();
};


AppetitMobileApp.prototype.removeFromCart = function(cid) {
	this.cartView.removeFromCart(cid);
};

//expand order item
AppetitMobileApp.prototype.expandOrderItem = function(id) {
	this.cartView.expandOrderItem(id);
}

//colapse order item
AppetitMobileApp.prototype.colapseOrderColapse = function(id) {
	this.cartView.colapseOrderColapse(id);
}

//delet order
AppetitMobileApp.prototype.deleteOrder = function(id) {
	this.cartView.deleteOrder(id);
}

//cart helper - orders
AppetitMobileApp.prototype.Cart = {
	
	instance: null,

	Cart: function() {
		this.items = [];
		this.orders = [];
		this.context;

		this.uidStorage = '32632987892eydhsaduahjs298';
		this.uidStorageOrders = '326329878324sadad7y7127391';

		//get total price
		this.getTotal = function() {
			var total = 0;
			var items = this.getCurrentItems();
			for (var i = 0; i < items.length; i++) {
				total += parseFloat(items[i].price_input);
			}
			return total.toFixed(2);
		}

		//get current items
		this.getCurrentItems = function() {
			if (this.items.length != 0) {
				return this.items;
			} else {
				return this.loadExistingData();
			}			
		}

		this.loadExistingData = function() {
			if (typeof(Storage) === "undefined") {
				return;
			}

			var raw = localStorage.getItem(this.uidStorage);
			if (raw) {
				try {
					this.items = JSON.parse(raw);
				} catch (e) {
					alert('error, can not parse JSON');
					alert(e);
				}				
			}
			return this.items;
		}

		//add item/s to cart
		this.addToCart = function(objectItem, times, context) {
			for (var i = 0; i < parseInt(times, 10); i++) {
				var newObjectItem = jQuery.extend({}, objectItem);
				newObjectItem.cid = '_cid_' + context.generateUID() + i;
				this.items.unshift(newObjectItem);
			}
			this.saveData();
		}		

		this.removeFromCart = function(cid) {
			var removedItem;
			for (var i = this.items.length - 1; i >= 0; i--) {
				if (this.items[i].cid == cid) {
					removedItem = this.items[i];
					this.items.splice(i, 1);
					break;
				}
			}
			this.saveData();
			return removedItem;
		}

		//close current order
		this.closeOrder = function() {
			var total = this.getTotal();
			var orderObj = {
				orderName: this.context.getCurrentTime(), 
				total: total, 
				items: this.items,
				id: 'order_' + this.context.generateUID()
			};
			this.orders.unshift(orderObj);
			this.items = [];
			this.saveData();
			this.saveOrders();
		}

		this.saveData = function() {
			if (typeof(Storage) === "undefined") {
				return;
			}
			localStorage.setItem(this.uidStorage, JSON.stringify(this.items));
		}

		//get current items
		this.getCurrentOrders = function() {
			if (this.orders.length != 0) {
				return this.orders;
			} else {
				return this.loadOrders();
			}			
		}

		//delete an order
		this.deleteOrder = function(id) {
			var removedOrder;
			for (var i = this.orders.length - 1; i >= 0; i--) {
				if (this.orders[i].id == id) {
					removedOrder = this.orders[i];
					this.orders.splice(i, 1);
					break;
				}
			}
			this.saveOrders();
			return removedOrder;
		}

		this.saveOrders = function() {
			if (typeof(Storage) === "undefined") {
				return;
			}			
			localStorage.setItem(this.uidStorageOrders, JSON.stringify(this.orders));
		}

		//load past orders
		this.loadOrders = function() {
			if (typeof(Storage) === "undefined") {
				return;
			}

			var raw = localStorage.getItem(this.uidStorageOrders);
			if (raw) {
				try {
					this.orders = JSON.parse(raw);
				} catch (e) {
					alert('error, can not parse orders JSON');
					alert(e);
				}				
			}
			return this.orders;
		}

		this.init = function(context) {
			this.context = context;
			this.loadExistingData();
			this.loadOrders();
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
		if (this.itemData.price_input === '') {
			alert('Can not add item, there is no price!');
			return;
		}
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

//utils generate uid
AppetitMobileApp.prototype.generateUID = function() {
    return Math.random().toString(36).substring(2, 15) + Math.random().toString(36).substring(2, 15);
};

AppetitMobileApp.prototype.getHours = function(date) {
	var hours = date.getHours();
	var minutes = date.getMinutes();
	var ampm = hours >= 12 ? 'pm' : 'am';
	hours = hours % 12;
	hours = hours ? hours : 12; // the hour '0' should be '12'
	minutes = minutes < 10 ? '0'+minutes : minutes;
	var strTime = hours + ':' + minutes + ' ' + ampm;
	return strTime;
};
//utils, get time
AppetitMobileApp.prototype.getCurrentTime = function() {
	var d = new Date();
	var weekday = new Array(7);
	weekday[0]=  "Sunday";
	weekday[1] = "Monday";
	weekday[2] = "Tuesday";
	weekday[3] = "Wednesday";
	weekday[4] = "Thursday";
	weekday[5] = "Friday";
	weekday[6] = "Saturday";
	var n = weekday[d.getDay()];

	return n + ' | ' + this.getHours(d);
};

//init
AppetitMobileApp.prototype.init = function() {
	this.handleData();
	this.router();
	this.cartView = new this.views.CartView(this.Cart, this).render();
};
