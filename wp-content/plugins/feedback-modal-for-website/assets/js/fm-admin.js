(function( $ ) {

    "use strict";

    window.nedwp_fm_admin = window.nedwp_fm_admin || {};
    
    window.nedwp_fm_admin.colorPicker = function() {
    	jQuery("input.wp-color-picker").wpColorPicker();
    };

    window.nedwp_fm_admin.stickyContent = function() {
        if (jQuery("#nedwp-fm-admin-sidebar").length) {
            jQuery("#nedwp-fm-admin-sidebar").theiaStickySidebar({
                additionalMarginTop: 52,
                additionalMarginBottom: 20
            });
        }
    };

	jQuery(document).ready(function() {
	    nedwp_fm_admin.colorPicker();
	    nedwp_fm_admin.stickyContent();
	});

})(jQuery);