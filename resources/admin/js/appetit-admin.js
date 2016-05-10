'use strict';
jQuery( document ).ready(function() {        
    var appetitAdmin = new AppetitAdmin();
    appetitAdmin.init();
});

var AppetitAdmin = function() {}

AppetitAdmin.prototype.addMenuSection = function(sectionName) {
	var sectionView = new AppetitViews.SectionView(this);
	sectionView.setData({ sectionName : sectionName });
	sectionView.renderTo(this.sectionsUI);
	sectionView.init();
	this.refreshSections();
	this.sections.push(sectionView);
	this.save();
};

AppetitAdmin.prototype.initEvents = function() {
	jQuery('#addSectionBTN').click(_.bind(function(e) {
		e.preventDefault();
		var popup = new AppetitViews.SectionNamePopup(_.bind(function(val) {
			this.addMenuSection(val);
		}, this));
		popup.show();
	}, this));

	jQuery('#appetitSaveBtn').click(_.bind(function(e) {
		e.preventDefault();
		if (this.isSaving) {
			return;
		}
		this.save();
	}, this));
};

AppetitAdmin.prototype.handleSectionRemove = function(sectionView) {
	for (var i = 0; i < this.sections.length; i++) {
		if (this.sections[i].cid === sectionView.cid) {
			this.sections.splice(i, 1);
			this.refreshSections();
			break;
		}
	}
	this.save();
};

AppetitAdmin.prototype.handleSectionRemove = function(sectionView) {
	for (var i = 0; i < this.sections.length; i++) {
		if (this.sections[i].cid === sectionView.cid) {
			this.sections.splice(i, 1);
			this.refreshSections();
			break;
		}
	}
	this.save();
};

AppetitAdmin.prototype.handleSort = function(from, to) {
    this.sections.splice(to, 0, this.sections.splice(from, 1)[0]);
    this.save();
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
		},
		active: false
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
	            this.handleSort(parseInt(start_pos, 10), parseInt(ui.item.index(), 10));
	        }	        
	    }, this)						        		
	});		
};

AppetitAdmin.prototype.setSaving = function(val) {
	if (this.savingStatus === val) {
		return;
	}
	this.savingStatus = true;
};

AppetitAdmin.prototype.showSaveInfo = function(show) {
	if (show) {
		jQuery('#appetitSaveInfo').css('opacity', 0);
		jQuery('#appetitSaveInfo').css('display', 'block');
		jQuery('#appetitSaveInfo').animate({
			opacity: 1
		}, 250);
	} else {
		jQuery('#appetitSaveInfo').animate({
			opacity: 0
		}, 250, function() {
			jQuery('#appetitSaveInfo').css('display', 'none');
		});
	}
};

AppetitAdmin.prototype.save = function() {
	this.isSaving = true;
	this.showSaveInfo(true);
	_.delay(_.bind(function() {
		this.saveInit();
	}, this), 300);
};

AppetitAdmin.prototype.saveInit = function() {
	var _self = this;
	var data = this.serialize();

	jQuery.post(
	    ajaxurl, 
	    {
	        'action': 'appetit_admin_api',
	        'sectionsData':   data.sectionsData,
	        'welcomeData': data.welcomeData,
	        'optionsData': data.optionsData
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
			_self.isSaving = false;
			_self.showSaveInfo(false);	    	       
	    }
	);	
};

AppetitAdmin.prototype.serialize = function() {
	var sectionsData = [];
	_.each(this.sections, function(sectionView) {
		sectionsData.push(sectionView.serialize());
	});
	return {sectionsData: sectionsData, welcomeData: this.welcomeView.serialize(), optionsData: this.optionsView.serialize()};
};

AppetitAdmin.prototype.initExistingData = function(first_argument) {
	if (jQuery('.appetit_section').length == 0) {
		return;
	}
	var _self = this;
	jQuery('.appetit_section').each(function(index) {
		var sectionView = new AppetitViews.SectionView(_self);		
		sectionView.setElement(jQuery(this));		
		sectionView.init();
		_self.refreshSections();
		_self.sections.push(sectionView);
	});
};

AppetitAdmin.prototype.createSaveButton = function() {
	jQuery('body').append(jQuery([
		'<div id="appetit_save_ui">',
			'<div id="appetitSaveInfo">',
				'<div class="save_info_container">',
					'<span class="save_spinner"><img src="' + AppetitHelper.APPETIT_ADMIN_URI + '/img/dark-preloader.gif" alt="" /></span>',
					'<span class="save_label_info">Saving...</span>',
				'</div>',
			'</div>',		
			'<div id="appetitSaveBtn" class="save-entry-ui">',
				'<span class="save_btn_ico"><span class="appetit-floppy-o"></span></span>',
				'<span class="save_label">Save</span>',
			'</div>',		
		'</div>',
		].join('')));
};

AppetitAdmin.prototype.initWelcome = function() {
	this.welcomeView = new AppetitViews.WelcomeView(this);
};

AppetitAdmin.prototype.initOptions = function() {
	this.optionsView = new AppetitViews.OptionsView(this);	
};

AppetitAdmin.prototype.init = function() {
	this.createSaveButton();
	this.sectionsUI = jQuery('#sections_accordion');
	this.initJQUI();
	this.initEvents();
	this.sections = [];	

	this.initWelcome();
	this.initOptions();
	this.initExistingData();
	jQuery('.appetit-admin-content').css('display', 'block');	
};