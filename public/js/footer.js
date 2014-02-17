var fixHeights = function(){
		var contentHeight = verge.viewportH() - (jQuery('#header').outerHeight(true) + jQuery('#footer').outerHeight(true))-25;
		jQuery('#body').css('min-height', contentHeight);
		jQuery('#footer').fadeIn(1500);
	};
	
    jQuery(document).ready(function(){
    	jQuery(window).resize(function() {
    		fixHeights();
   		});
    });
    
    jQuery(window).load(function () {
    	fixHeights();
    });
