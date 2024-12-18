define(['jquery', 'fab/element'], function (jQuery, FbElement) {

	window.FbEmundusColorpicker= new Class({
		Extends: FbElement,

		// Variables

		initialize: function (element, options) {
			this.setPlugin('emundus_colorpicker');
			this.parent(element, options);
		},
	});

	return window.FbEmundusColorpicker;
});