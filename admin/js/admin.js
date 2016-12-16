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
		if (this.checked) {
			value = value + "[]";
			$(target).val(value);
		} else {
			$(target).val(value);
		}
	});

	$(document).on('change', '.clonable .regular-select-box', function (e) {
		$(this).parents('.clonable').find('input[readonly]').val($(this).find('option:selected').text());
	});

	///////////////////////
	// Custom File input //
	///////////////////////
	$(document).on('change', '.file-control > input[type=file]', function (e) {
		var fullPath = $(this).val();
		if (fullPath) {
		    var startIndex = (fullPath.indexOf('\\') >= 0 ? fullPath.lastIndexOf('\\') : fullPath.lastIndexOf('/'));
		    var filename = fullPath.substring(startIndex);
		    if (filename.indexOf('\\') === 0 || filename.indexOf('/') === 0) {
		        filename = filename.substring(1);
		    }
		    $(this).parent().find('.file-name').val(filename);
		}
	});
	$('.clonable-button-close-all').on('click', function (e) {
		var parent = $(this).closest('.clonable-block');
		parent.find('.clonable:not(:first)').remove();
		parent.find('.clonable input').val('');
		parent.find('.clonable select').val('');
		parent.find('.clonable textarea').text('');
		e.preventDefault();
	});

	///////////
	// Admin //
	///////////
	$('.accordion').accordion({ collapsible: true, active: false });
	$('.sortables').sortable({
		update: function (e, ui) {
			var $clonables = $(this).find('.clonable');
			var _i = 1;
			$clonables.each(function () {
				$(this).find('.clonable-increment-html').text(_i);
				_i+=1;
			});

			var _i = 0;
			$clonables.each(function () {
				var dis = $(this).find('.clonable-increment-name');
				var old_val = dis.attr('name');
				dis.attr('name', old_val.replace(/\d+(?=\D*$)/, _i));
				_i+=1;
			});
		},
	});

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

	////////////////
	// Ugly fixes //
	////////////////
	$('span:contains(Saveed)').text('Saved');
});