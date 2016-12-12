jQuery(document).ready(function ($) {
	//////////////
	// start... //
	//////////////
	// requires jquery-slugger
	if ($.fn.slugger != undefined) {
		$('#title').slugger({
			target: '.the-slug',
			separator: '_'
		}).slugger({
			target: '.the-label',
			separator: " ",
			convertToLowerCase: false,
		});
	}

	////////////
	// Custom //
	////////////
	// requi
	$('#custom-repeatable-check').change(function (e) {
		var target = $(this).data('target');
		var value = $(target).val();
		value = value.replace('[]', '');
		console.log(value);
		if (this.checked) {
			value = value + "[]";
			$(target).val(value);
		} else {
			$(target).val(value);
		}
	});


	///////////
	// Admin //
	///////////
	$('.accordion').accordion({ collapsible: true, active: false });

	$(document).on('click', '[data-make]', function (e) {
		var make = $(this).data('make');
		var attr = [];
		for (var a in make.attr) {
			attr.push(a+'="'+make.attr[a]+'"');
		}
		attr = attr.join(" ");
		$html = $('<'+make.html+' '+attr+'/>');
		$html.addClass(make.attr.class);
		$(this).parent().html($html);
		e.preventDefault();
	});

	///////////////
	// Selectize //
	///////////////
	$(function() {
	    $('.selectizable').selectize();
	});
});