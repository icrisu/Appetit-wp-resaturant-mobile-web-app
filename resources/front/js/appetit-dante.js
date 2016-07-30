'use strict';
jQuery(document).ready(function() {
	jQuery('.appetit-dante-main').each(function() {
		var h = new AppetitDanteHandler();
		h.init(jQuery(this));
	});    
});

var AppetitDanteHandler = function() {
	this.menuItems = [];
	this.items = [];
}

//init packery
AppetitDanteHandler.prototype.initPackery = function() {
	this.packeryContainer.packery({
	  itemSelector: '.appetit-dante-grid-item',
	  gutter: 0
	});	
};

AppetitDanteHandler.prototype.packeryLayout = function() {
	try {
		this.packeryContainer.packery('layout');
	} catch (e) {}	
};

AppetitDanteHandler.prototype.preloader = function(val) {
	var preloader = this.mainMenuContainer.find('.appetit-dante-preloader');
	if (!val) {
		preloader.animate({
			opacity: 0
		}, 200, function() {
			preloader.remove();
		});
	}
};

//select menu item
AppetitDanteHandler.prototype.selectMenuItem = function(sectionID) {
	// body...
	for (var i = 0; i < this.menuItems.length; i++) {
		if (this.menuItems[i].hasClass('appetit-dante-sections-menu-item-selected')) {
			this.menuItems[i].removeClass('appetit-dante-sections-menu-item-selected');
		}
		if (this.menuItems[i].data().sectionid == sectionID) {
			this.menuItems[i].addClass('appetit-dante-sections-menu-item-selected')
		}
	}
};

//select section
AppetitDanteHandler.prototype.selectSection = function(sectionID) {
	this.selectMenuItem(sectionID);
	if (sectionID == 'dante-all') {
		this.packeryContainer.find('.' + sectionID).css('display', 'block');
	} else {
		this.packeryContainer.find('.dante-all').css('display', 'none');
		this.packeryContainer.find('.' + sectionID).css('display', 'block');
	}
	this.packeryLayout();
};

//register events
AppetitDanteHandler.prototype.events = function() {
	var _self = this;
	this.mainMenuContainer.find('.appetit-dante-sections-menu li a').each(function(indx) {		
		_self.menuItems.push(jQuery(this));
		jQuery(this).click(function(e) {
			e.preventDefault();
			_self.selectSection(jQuery(this).data().sectionid);
		});	
	});
};

//build item
AppetitDanteHandler.prototype.Item = function(itemUI, indx, context) {
	this.next;
	this.itemUI = itemUI;
	this.itemUI.css('opacity', 0);
	this.infoUI = this.itemUI.find('.appetit-dante-item-about');
	this.infoUI.css('opacity', 0);
	this.infoUI.css('display', 'block');
	
	this.setNext = function(item) {
		this.next = item;
	}

	this.nextItem = function() {
		if (this.next) {
			this.next.load();
		}
	}

	this.load = function() {
		var imgHolder = this.itemUI.find('.appetit-dante-image-holder');
		var imgSrc = imgHolder.data().src;
		var imgSrcset = imgHolder.data().srcset;

		var img = jQuery('<img class="appetit-dante-grid-item-image" src="" alt="" />');
		img.bind('load', function() {
			this.itemUI.animate({
				opacity: 1
			});
			context.packeryLayout();
			if (indx == 0) {
				context.preloader(false);
			}
			this.handleHoverBehavior();
			this.nextItem();
		}.bind(this));

		img.attr('src', imgSrc);
		img.attr('srcset', imgSrcset);

		img.appendTo(imgHolder);		
	}

	this.hover = function(val) {
		this.infoUI.find('.appetit-dante-item-about-inside').css('top', this.infoUI.height()/2 - this.infoUI.find('.appetit-dante-item-about-inside').height()/2);
		if (val) {
			this.infoUI.stop(true, true).animate({
				opacity: 1
			}, 200);			
		} else {		
			this.infoUI.stop(true, true).animate({
				opacity: 0
			}, 200);
		}
	}

	this.handleHoverBehavior = function() {
		this.itemUI.hover(function() {
			this.hover(true);
		}.bind(this), function() {
			this.hover(false);
		}.bind(this));
	}

};

//load items
AppetitDanteHandler.prototype.loadItems = function() {	
	var _self = this;
	var item;

	this.mainMenuContainer.find('.appetit-dante-items-ui .appetit-dante-grid-item').each(function(indx) {
		
		var tempItem = new _self.Item(jQuery(this), indx, _self);

		if (item) {
			item.setNext(tempItem);
		}

		_self.items.push(tempItem);

		item = tempItem;
	});

	if (this.items.length != 0) {
		this.items[0].load();
	}
};

//init
AppetitDanteHandler.prototype.init = function(mainMenuContainer) {
	this.mainMenuContainer = mainMenuContainer;
	this.packeryContainer = this.mainMenuContainer.find('.appetit-dante-items-ui');
	this.initPackery();
	this.loadItems();
	this.events();
	this.selectSection('dante-all');
};

