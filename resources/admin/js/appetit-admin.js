'use strict';
jQuery( document ).ready(function() {        
    var appetitAdmin = new AppetitAdmin();
    appetitAdmin.init();
});

var AppetitAdmin = function() {}

AppetitAdmin.prototype.addMenuSection = function(data) {
	var sectionView = new AppetitViews.SectionView(data);
	sectionView.renderTo(this.sectionsUI);
	sectionView.init();
	this.refreshSections();
};

AppetitAdmin.prototype.initEvents = function() {
	jQuery('#addSectionBTN').click(_.bind(function(e) {
		e.preventDefault();
		var popup = new AppetitViews.SectionNamePopup(_.bind(function(val) {
			this.addMenuSection(val);
		}, this));
		popup.show();
	}, this));
};

AppetitAdmin.prototype.refreshSections = function(first_argument) {
	jQuery('#sections_accordion').accordion('refresh');
	jQuery('#sections_accordion').sortable('refresh');
};

AppetitAdmin.prototype.initJQUI = function(first_argument) {
	jQuery('.appetit-admin-content').tabs();

	jQuery('#sections_accordion').accordion({
		header: "> div > h3",
		'heightStyle': 'content',
		'collapsible': true,
		animate: {
		    duration: 200
		}			
	});

	jQuery('#sections_accordion').sortable({
        axis: "y",
        handle: "h3",
		start: function(event, ui) {
		    ui.item.data('start_pos', ui.item.index());
		},
	    stop: _.bind(function(event, ui) {
	        var start_pos = ui.item.data('start_pos');		        
	        if (start_pos != ui.item.index()) {
	            // the item got moved
	            //this.handleSort(parseInt(start_pos, 10), parseInt(ui.item.index(), 10));
	        }
	        //this.refreshSections();
	    }, this)						        		
	});		
};

AppetitAdmin.prototype.init = function() {
	this.sectionsUI = jQuery('#sections_accordion');
	this.initJQUI();
	this.initEvents();
};