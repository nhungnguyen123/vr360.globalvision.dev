(function (w, $) {
	vrAdmin.Log = {
		reset: function () {
			$('#vrLogging').html('');
		},

		append: function (message) {
			$('#vrLogging').append('<div class="message">' + message + '</div>');
		},

		appendArray: function (messages) {
			$.each(messages, function (index, value) {
				vrAdmin.Log.append(value);
			})
		}
	}
})(window, jQuery);