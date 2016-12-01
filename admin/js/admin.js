jQuery(document).ready(function ($) {
	//////////////
	// start... //
	//////////////
	// requires jquery-slugger
	if ($.fn.slugger != undefined) {
		$('#title').slugger({
			target: '.the-slug',
			separator: '_'
		});
	}
});