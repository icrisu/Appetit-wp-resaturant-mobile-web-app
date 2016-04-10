'use strict';
var SakuraPlugins = {};
SakuraPlugins.utils = (function() {
    return {
        hexToRgb: function(hex) {
            var bigint = parseInt(hex, 16);
            var r = (bigint >> 16) & 255;
            var g = (bigint >> 8) & 255;
            var b = bigint & 255;
            return [r, g, b];
        },
        
        generateUID: function() {
            return Math.random().toString(36).substring(2, 15) +
                Math.random().toString(36).substring(2, 15);            
        },

		openMedia: function(isMultiple, callBack) {
		      var send_attachment_bkp = wp.media.editor.send.attachment;
		      var frame = wp.media({
		          title: "Select Images",
		          multiple: isMultiple,
		          library: { type: 'image' },
		          button : { text : 'add image' }
		      });
		        frame.on('close',function() {                        
		            var selection = frame.state().get('selection');
		            if(selection.length==0)
		              return; 
		            var data = new Array();
		            selection.each(function(attachment) { 
		              var iconUrl = 'http://placehold.it/150x150';    
		              if(attachment.attributes.sizes.thumbnail!=undefined){
		                 iconUrl = (attachment.attributes.sizes.thumbnail.url!='')?attachment.attributes.sizes.thumbnail.url:iconUrl;
		              }               
		              data.push({id:attachment.id, iconUrl:iconUrl});                                                                  
		            });
		            callBack(data);
		            wp.media.editor.send.attachment = send_attachment_bkp;
		        }); 
		        frame.open();
		        return false;    
		  }         
    }
})();