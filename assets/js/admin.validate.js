(function (w, $) {
	/**
	 * Handle tours
	 * @type {{hooks: hooks}}
	 */
	vrAdmin.Validate = {
		errors: [],
		/**
		 * Validate form fields
		 * @returns {string}
		 */
		validate: function () {
			vrAdmin.Log.append('File validating');
			vrAdmin.Validate.errors = [];

			// File validate
			$('#tour-panos input[type=file]').each(function () {
				var file = this.files[0];

				// File type validate
				if (file.type != 'image/jpeg' && file.type != 'image/png') {
					vrAdmin.Validate.errors.push ('File format is not valid');
				}

				// File size validate
				var allowedFilesize = 26214400; // 25 MB
				var minFilesize = 5242880; // 5 MB

				if (file.size >= allowedFilesize || file.size <= minFilesize) {
					//vrAdmin.Validate.errors.push ('File size is not valid');
				}
			})
		},
	}
})(window, jQuery.noConflict());