'use strict';

(function() {
	jQuery( document ).ready(function() {        
	    var aaCPT = new AppetitAdminCPT();
	    aaCPT.init();
	});

	var AppetitAdminCPT = function() {}

	AppetitAdminCPT.prototype.handleSelectPage = function() {
		if(jQuery('#wedx_typeSelect').length==0)
			return;
	    jQuery('#wedx_typeSelect').change(function(e){        
	        jQuery('#pageTypeUI').val(jQuery('#wedx_typeSelect option:selected').val());
	    });
	};

	AppetitAdminCPT.prototype.init = function() {
		this.handleSelectPage();
	};

})();