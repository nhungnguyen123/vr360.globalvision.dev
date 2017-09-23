(function (w, $)
{
	/**
	 * Handle tours
	 * @type {{hooks: hooks}}
	 */
	vrAdmin.Validate = {
		/**
		 * Validate form fields
		 * @returns {string}
		 */
		validate: function ()
		{
			vrAdmin.Log.append('File validating');

			// File validate
			$('#tour-panos input[type=file]').each(function ()
			{
				var file = this.files[0];

				// File type validate
				if (file.type != 'image/jpeg' && file.type != 'image/png')
				{
					return 'File format is not valid'
				}

				// File size validate
				var allowedFilesize = 26214400; // 25 MB
				var minFilesize = 5242880; // 5 MB

				if (file.size >= allowedFilesize || file.size <= minFilesize)
				{
					return 'File size is not valid';
				}
			})

			return '';
		},
	}
})(window, jQuery.noConflict());