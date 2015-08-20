/**
 * User: ZX
 * Date: 2015/8/5
 * Time: 15:30
 */
$.extend($.fn.dategrid.defaults.editors, {
	datetimebox: {
		init: function(container, options) {
			var input = $('<input class="easyui-datetimebox">').appendTo(container);
			return input;
		},
		getValue: function(target) {
			$(target).parent().find('input.combo-value').val();
		},
		setValue: function(target, value) {
			$(target).datetimebox("setValue",value);
		},
		resize: function(target, width) {
			var input = $(target);
			if ($.boxModel == true) {
				input.width(width - (input.outerWidth() - input.width()));
			} else {
				input.width(width);
			}
		}
	}
});

 

