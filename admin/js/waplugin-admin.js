(function( $ ) {
	'use strict';

	/**
	 * All of the code for your admin-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */
	$(function() {
		// Tabs
		$('.waplugin-tabs').each(function(index) {
		    var $tabParent = $(this);
		    var $tabs = $tabParent.find('li');
		    var $contents = $tabParent.next('.waplugin-tabs-content').find('.waplugin-tab-content');

		    $tabs.click(function() {
		        var curIndex = $(this).index();
		        // toggle tabs
		        $tabs.removeClass('is-active');
		        $tabs.eq(curIndex).addClass('is-active');
		        // toggle contents
		        $contents.removeClass('is-active');
		        $contents.eq(curIndex).addClass('is-active');
		    });
		});
		// Auto close Alert
		function waplugin_close_alert(id, reload = true) {
			$("#"+id).fadeTo(2000, 500).slideUp(500, function(){
				$("#"+id).slideUp(500);
				if (reload) {location.reload();}
			});
		}
		// Show Alert
		function waplugin_show_alert(id) {
			$("#"+id).fadeIn('slow');
		}
		// Submit API
		$( "#submit-waplugin-api" ).on( "click", function(e) {
			e.preventDefault();
			var api = $("input[name=waplugin_api]").val();
			var btn = $(this);
			btn.addClass('is-loading');
			if (api.length && api != '') {
				$.ajax({
				    type: "post",
				    dataType: "json",
				    url: ajax_object.ajaxurl,
				    data: 'action=waplugin_check_api_key&sid=' + ajax_object.ajax_nonce + '&waplugin_api=' +api,
				    success: function(response) {
				    	btn.removeClass('is-loading');
				    	if (response.success) {
				    		waplugin_show_alert('waplugin-api-valid');
				    		waplugin_close_alert('waplugin-api-valid');
				    	} else {
				    		waplugin_show_alert('waplugin-api-invalid');
				    		waplugin_close_alert('waplugin-api-invalid', false);
				    	}
				    }
				});
			} else {
				alert('API is required!');
			}
		});
		// Add Account
		$( "#submit-waplugin-account" ).on( "click", function(e) {
			e.preventDefault();
			var account_id = $("select[name=waplugin_account_id]").val();
			var btn = $(this);
			btn.addClass('is-loading');
			if (account_id.length && account_id != '') {
				$.ajax({
				    type: "post",
				    dataType: "json",
				    url: ajax_object.ajaxurl,
				    data: 'action=waplugin_add_account&sid=' + ajax_object.ajax_nonce + '&waplugin_account_id=' +account_id,
				    success: function(response) {
				    	btn.removeClass('is-loading');
				    	if (response.success) {
				    		waplugin_show_alert('waplugin-account-valid');
				    		waplugin_close_alert('waplugin-account-valid');
				    	} else {
				    		waplugin_show_alert('waplugin-account-invalid');
				    		waplugin_close_alert('waplugin-account-invalid', false);
				    	}
				    }
				});
			} else {
				alert('Account is required!');
			}
		});
		// Save Admin phone
		$( "#submit-waplugin-admin" ).on( "click", function(e) {
			e.preventDefault();
			var country = $("select[name=waplugin_admin_country]").val();
			var phone = $("input[name=waplugin_admin_phone]").val();
			var btn = $(this);
			btn.addClass('is-loading');
			if (country.length && country != '' && phone.length && phone != '') {
				$.ajax({
				    type: "post",
				    dataType: "json",
				    url: ajax_object.ajaxurl,
				    data: 'action=waplugin_save_admin&sid=' + ajax_object.ajax_nonce + '&waplugin_admin_country=' +country+'&waplugin_admin_phone='+phone,
				    success: function(response) {
				    	btn.removeClass('is-loading');
				    	if (response.success) {
				    		waplugin_show_alert('waplugin-admin-valid');
				    		waplugin_close_alert('waplugin-admin-valid');
				    	} else {
				    		waplugin_show_alert('waplugin-admin-invalid');
				    		waplugin_close_alert('waplugin-admin-invalid', false);
				    	}
				    }
				});
			} else {
				alert('Country & Phone number is required!');
			}
		});
	});
})( jQuery );
