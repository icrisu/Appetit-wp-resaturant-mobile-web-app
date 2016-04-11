'use strict';
jQuery( document ).ready(function() {        
    var appetitAdmin = new AppetitAdmin();
    appetitAdmin.init();
});

var AppetitAdmin = function() {}

AppetitAdmin.prototype.addMenuSection = function(sectionName) {
	var sectionView = new AppetitViews.SectionView();
	sectionView.setData({ sectionName : sectionName });
	sectionView.renderTo(this.sectionsUI);
	sectionView.init();
	this.refreshSections();
	this.sections.push(sectionView);
};

AppetitAdmin.prototype.initEvents = function() {
	jQuery('#addSectionBTN').click(_.bind(function(e) {
		e.preventDefault();
		var popup = new AppetitViews.SectionNamePopup(_.bind(function(val) {
			this.addMenuSection(val);
		}, this));
		popup.show();
	}, this));

	jQuery('#saveAppetitDataBTN').click(_.bind(function(e) {
		e.preventDefault();
		this.save();		
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

AppetitAdmin.prototype.save = function(callback, isSilent) {
	var sectionsData = this.serialize();	
	jQuery.post(
	    ajaxurl, 
	    {
	        'action': 'appetit_admin_api',
	        'appetitData':   sectionsData
	    }, 
	    function(response){
	    	try {
	    		var responseData = JSON.parse(response);
	    		if (responseData.status == 'OK') {

	    		} else {
	    			alert(responseData.msg);
	    		}
	    	} catch (e) {
	    		alert('invalid server response');
	    	}	        
	    }
	);	
};

AppetitAdmin.prototype.serialize = function() {
	var sectionsData = [];
	_.each(this.sections, function(sectionView) {
		sectionsData.push(sectionView.serialize());
	});
	return sectionsData;
};

AppetitAdmin.prototype.init = function() {
	this.sectionsUI = jQuery('#sections_accordion');
	this.initJQUI();
	this.initEvents();
	this.sections = [];
};