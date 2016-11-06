function invert(id) {
	if (jQuery("#fifu_toggle_" + id).attr("class") == "toggleon") {
		jQuery("#fifu_toggle_" + id).attr("class", "toggleoff");
		jQuery("#fifu_input_" + id).val('off');
	}
	else {
		jQuery("#fifu_toggle_" + id).attr("class", "toggleon");
		jQuery("#fifu_input_" + id).val('on');
	}
}

jQuery(function () {
	var url = window.location.href;

	jQuery("#fifu_form_woocommerce").submit(function () {

		var frm = jQuery("#fifu_form_woocommerce");

		jQuery.ajax({
			type: frm.attr('method'),
			url: url,
			data: frm.serialize(),
			success: function (data) {
				//alert('saved');
			}
		});
	});

	jQuery("#fifu_form_content").submit(function () {

		var frm = jQuery("#fifu_form_content");

		jQuery.ajax({
			type: frm.attr('method'),
			url: url,
			data: frm.serialize(),
			success: function (data) {
				//alert('saved');
			}
		});
	});

	jQuery("#fifu_form_cpt").submit(function () {

		var frm = jQuery("#fifu_form_cpt");

		jQuery.ajax({
			type: frm.attr('method'),
			url: url,
			data: frm.serialize(),
			success: function (data) {
				//alert('saved');
			}
		});
	});

	jQuery("#fifu_form_hope").submit(function () {

		var frm = jQuery("#fifu_form_hope");

		jQuery.ajax({
			type: frm.attr('method'),
			url: url,
			data: frm.serialize(),
			success: function (data) {
				//alert('saved');
			}
		});
	});
});
