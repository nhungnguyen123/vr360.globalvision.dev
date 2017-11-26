(function (w, vrAdmin, $) {

	var vrLog = {
		reset: function () {
			$('#overlay-waiting .messages').html('');
		},

		append: function (message) {
			$('#overlay-waiting .messages').append(message);
		},

		appendArray: function (messages) {
			$.each(messages, function (index, value) {
				vrLog.append(value);
			});
		}
	};

	vrAdmin.Log = vrLog;
})(window, vrAdmin, jQuery);