(function (w, $)
{
	var vrWaiting = {
		_elements: {
			closeButton: '#overlay-waiting .btn-log-close'
		},

		waiting: function()
		{
			$('body').addClass('loading');
			$(vrWaiting._elements.closeButton).addClass('hide');
			vrAdmin.Log.reset();
		},

		success: function()
		{
			$('body').removeClass('loading');
		},

		stay: function()
		{
			$(vrWaiting._elements.closeButton).removeClass('hide');
		}
	}

	w.vrAdmin.Waiting = vrWaiting;
})(window, jQuery);