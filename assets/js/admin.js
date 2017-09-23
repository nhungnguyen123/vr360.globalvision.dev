(function (w, $)
{

	var vrAdmin = {};


	vrAdmin.Panos = {};

	/**
	 * Handle a pano
	 * @type {{hooks: hooks}}
	 */
	vrAdmin.Pano = {
		/**
		 * Pano hooking
		 */
		hooks: function ()
		{
			// Add more pano
			$('body').on('click', '#addPano', function ()
			{
				var tourHtml = $('.hidden .pano').parent().html();
				$('#tour-panos').append(tourHtml);
			})

			// Remove a pano
			$('body').on('click', '.removePano', function (event)
			{
				$(this).parent().parent().remove();
			})
		}
	};

	w.vrAdmin = vrAdmin;

	$(document).ready(function ()
	{

		vrAdmin.Tours.hooks();
		vrAdmin.Tour.hooks();
		vrAdmin.Pano.hooks();

		$('input').tooltip();
	})
})(window, jQuery);
