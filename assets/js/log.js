(function (w, vrAdmin, $) {

	var vrLog = {
		reset: function () {
			$('#overlay-waiting').html('');
		},

		append: function (message) {
			$('#overlay-waiting').append('<div class="message">' + message + '</div>');
		},

		appendArray: function (messages) {
			$.each(messages, function (index, value) {
				vrLog.append(value);
			})
		}
	}

	vrAdmin.Log = vrLog;
})(window, vrAdmin, jQuery);