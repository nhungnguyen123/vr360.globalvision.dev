(function (w, $) {

	vrAdmin.Log = {
		reset: function () {
			$('#overlay-waiting').html('');
		},

		append: function (message) {
			$('#overlay-waiting').append('<div class="message">' + message + '</div>');
		},

		appendArray: function (messages) {
			$.each(messages, function (index, value) {
				vrAdmin.Log.append(value);
			})
		}
	}
})(window, jQuery);