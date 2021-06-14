(function( $ ) {

    "use strict";

    window.nedwp_fm = window.nedwp_fm || {};

    window.nedwp_fm.public = {

    	init: function() {

    		var $fm = $("#nedwp-fm");

    		if ($fm.length) {

    			if (typeof nedwp_fm_public_var !== "undefined") {
		    		this.handleViewActions();
		    		this.handleScreenActions();
		    		this.handleWindowResize();
    			} else {
    				$fm.remove();
    			}

    		}

    	},

    	handleViewActions: function() {

    		var $toggle = $("#nedwp-fm-toggle"),
	    		$shadow = $("#nedwp-fm-shadow"),
	    		$modal = $("#nedwp-fm-modal"),
	    		$modalClose = $("#nedwp-fm-modal-close");
    		
    		if ($toggle.length) {

    			this.handleToggleRendering();

		    	$toggle.on("click", function() {

	    			if ($modal.hasClass("nedwp-active")) {
	    				$shadow.removeClass("nedwp-active");
	    				$modal.removeClass("nedwp-active");
	    			} else {
	    				$shadow.addClass("nedwp-active");
	    				$modal.addClass("nedwp-active");
	    			}

		    	});

    		}

    		if ($modalClose.length) {

		    	$modalClose.on("click", function() {
	    			$shadow.removeClass("nedwp-active");
	    			$modal.removeClass("nedwp-active");
		    	});

    		}

			$(document).keyup(function(e) {
				if (27 === e.keyCode) {
	    			$shadow.removeClass("nedwp-active");
	    			$modal.removeClass("nedwp-active");
				}
			});

    		this.handleOpinionField();

    	},

    	handleToggleRendering: function() {

    		var $toggle = $("#nedwp-fm-toggle").css({width: "", height: ""}),
	    		toggleWidth = Math.round($toggle.outerWidth()),
	    		toggleHeight = Math.round($toggle.outerHeight());

	    	$toggle.css({
	    		width: (0 !== (toggleWidth % 2)) ? toggleWidth + 1 : toggleWidth,
	    		height: (0 !== (toggleHeight % 2)) ? toggleHeight + 1 : toggleHeight
	    	});

	    	this.handleTogglePosition();

    	},

    	handleTogglePosition: function() {

    		var $toggle = $("#nedwp-fm-toggle"),
	    		toggleXPos = ($toggle.outerWidth() - $toggle.outerHeight()) / 2;

			if ($("#nedwp-fm").hasClass("nedwp-fm-right")) {
				$toggle.css({right: -toggleXPos, opacity: 1});
			} else {
				$toggle.css({left: -toggleXPos, opacity: 1});
			}

    	},

    	handleModalRendering: function() {

    		var $modal = $("#nedwp-fm-modal"),
	    		$adjust = $("#nedwp-fm-modal-adjust").removeClass("nedwp-active"),
	    		modalHeight = $modal.outerHeight(),
	    		windowWidth = $(window).width();

            if (0 !== (modalHeight % 2)) {
            	$adjust.addClass("nedwp-active");
            }

            if (windowWidth <= 768 && 0 !== (windowWidth % 2)) {
            	$modal.addClass("nedwp-fm-modal-shrink");
            } else {
            	$modal.removeClass("nedwp-fm-modal-shrink");
            }

    	},

    	handleOpinionField: function() {

    		var $opinionChoices = $(".nedwp-fm-modal-opinion-value");

	    	if ($opinionChoices.length) {

	            $opinionChoices.on("click", function(e) {

	                e.preventDefault();
	                var $choice = $(this);

	                if ($choice.hasClass("nedwp-active")) {
						$choice.removeClass("nedwp-active");
	                } else {
	                	$("#nedwp-fm-modal-opinion-notice").empty();
	                	$opinionChoices.removeClass("nedwp-active");
	                	$choice.addClass("nedwp-active");
	                }
	                
	            });

	    	}

    	},

    	fieldNoticeReq: function() {
    		var text = nedwp_fm_public_var.fm_i18n.required;
    		return (text) ? text : '*';
    	},

    	fieldNoticeInv: function() {
    		var text = nedwp_fm_public_var.fm_i18n.invalid;
    		return (text) ? text : '?';
    	},

    	screenLoaderShow: function() {

    		var $screenLoader = $("#nedwp-fm-modal-loader");

    		if ($screenLoader.length) {
    			$screenLoader.addClass("nedwp-active");
    		}

    	},

    	screenLoaderHide: function() {

    		var $screenLoader = $("#nedwp-fm-modal-loader");

    		if ($screenLoader.length) {
    			$screenLoader.removeClass("nedwp-active");
    		}

    	},

    	handleFbScreen: function() {
	    	
	    	var flag = false, $opinion = $(".nedwp-fm-modal-opinion-value"),
		    	$opinionActive = $(".nedwp-fm-modal-opinion-value.nedwp-active"),
		    	opinionRequired = $("#nedwp-fm-modal-opinion-choice").hasClass("nedwp-required"),
		    	$comment = $("textarea#nedwp-fm-modal-textarea-comment"),
		    	commentRequired = ($comment.length) ? $comment.prop("required") : false;

			$comment.val(function(i, val) {
				return val.trim();
			});

		    if (opinionRequired && $opinion.length && !$opinionActive.length) {
		    	$("#nedwp-fm-modal-opinion-notice").text(this.fieldNoticeReq());
		    	flag = true;
		    } else {
		    	$("#nedwp-fm-modal-opinion-notice").empty();
		    }

		    if (commentRequired && $comment.length && "" == $comment.val()) {
		    	$("#nedwp-fm-modal-comment-notice").text(this.fieldNoticeReq());
		    	flag = true;
		    } else {
		    	$("#nedwp-fm-modal-comment-notice").empty();
		    }

    		return (!flag);
    	},

    	handleEmailScreen: function() {

    		var flag = false, $email = $("input#nedwp-fm-modal-input-email"),
	    		emailRequired = ($email.length) ? $email.prop("required") : false;

			$email.val(function(i, val) {
				return val.trim();
			});

		    if (emailRequired && $email.length) {

		    	if ("" != $email.val()) {

					var re = /\S+@\S+\.\S+/;

					if (!re.test($email.val())) {
						$("#nedwp-fm-modal-email-notice").text(this.fieldNoticeInv());
						flag = true;
					}

		    	} else {
		    		$("#nedwp-fm-modal-email-notice").text(this.fieldNoticeReq());
		    		flag = true;
		    	}

		    }

		    if (!flag) {
		    	$(".nedwp-fm-modal-field-notice").empty();
		    }

    		return (!flag);
    	},

    	handleScreenActions: function() {

    		var $screenFb = $("#nedwp-fm-modal-screen-fb"),
	    		$screenEmail = $("#nedwp-fm-modal-screen-email"),
	    		$screenDone = $("#nedwp-fm-modal-screen-done"),
	    		$actionNext = $("#nedwp-fm-modal-next"),
	    		$actionBack = $("#nedwp-fm-modal-back"),
	    		$actionSubmit = $("#nedwp-fm-modal-submit"),
	    		that = this, $currentScreen = $screenFb;

	    	$actionNext.on("click", function() {
	    		that.screenLoaderShow();
	    		if (that.handleFbScreen()) {
	    			$currentScreen.hide();
	    			$currentScreen = $screenEmail;
		    		$screenEmail.show();
	    		}
	    		that.handleModalRendering();
	    		that.screenLoaderHide();
	    	});

	    	$actionBack.on("click", function() {
	    		that.screenLoaderShow();
	    		$currentScreen.hide();
	    		$currentScreen = $screenFb;
	    		$screenFb.show();
	    		that.handleModalRendering();
	    		that.screenLoaderHide();
	    	});

	    	$actionSubmit.on("click", function() {
	    		if (that.handleFbScreen() && that.handleEmailScreen()) {
	    			that.screenLoaderShow();
    				$currentScreen.hide();
	    			$currentScreen = $screenDone;
	    			$screenDone.show();
	    			that.handleModalRendering();
	    			that.handleModalSubmit();
	    		}
	    	});

    	},

    	handleModalSubmit: function() {

	    	var $fm = $("#nedwp-fm"),
		    	$opinion = $(".nedwp-fm-modal-opinion-value.nedwp-active"),
		    	$comment = $("textarea#nedwp-fm-modal-textarea-comment"),
		    	$email = $("input#nedwp-fm-modal-input-email"),
		    	that = this, params = {
		    		"action": "nedwp_fm_handle_submit",
		    		"security": nedwp_fm_public_var.ajax_nonce
		    	};

		    if ($opinion.length) {
		    	params.opinion = $opinion.data("opinion");
		    }

		    if ($comment.length && "" != $comment.val()) {
		    	params.comment = $comment.val();
		    }

		    if ($email.length && "" != $email.val()) {
		    	params.email = $email.val();
		    }

            $.ajax({
                type: "POST",
                url: nedwp_fm_public_var.ajax_url,
                data: params,                
                success: function(response) {
                	that.screenLoaderHide();
                },
				error: function(req, st, err) { 
					$fm.remove();
				}
            });

    	},

    	handleWindowResize: function() {

    		var that = this;
    		that.handleModalRendering();

	    	$(window).on("resize", function() {
	    		that.handleToggleRendering();
	    		that.handleModalRendering();
	    	});

    	}

    };

	jQuery(document).ready(function() {
	    nedwp_fm.public.init();
	});

})(jQuery);