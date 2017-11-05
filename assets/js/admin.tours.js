(function (w, $)
{
	/**
	 * Handle tours
	 * @type {{hooks: hooks}}
	 */
	vrAdmin.Tours = {

		Controls: {
			getProperty: function (el, property)
			{
				var data = $(el).parent().parent().data();

				return data.tour[property];
			}
		},

		/**
		 * Show add new modal with right content
		 */
		addNew: function ()
		{
			$.ajax({
				url: 'index.php',
				type: 'POST',
				data: {
					view: 'tour',
					task: 'ajaxGetTourHtml',
				},
				async: true,
				cache: false,
			})
				.done(function (data, textStatus, jqXHR)
				{
					if (data.status)
					{
						// Update title
						$('#vrTour .modal-title').html('<i class="fa fa-plus-square" aria-hidden="true"></i> New tour');
						// Update body
						$('#vrTour .modal-body .container-fluid').html(data.data.html);
						$('#vrTour').modal('show');

						// Init default pano
						$('.addPano').click();
					}
				});

			$('#vrTour').modal('show');
		},

		/**
		 * Show embed code modal
		 * @param el
		 */
		showEmbed: function (el)
		{
			$(el).find('.embedCode').on('click', function (event)
			{
				var alias = vrAdmin.Tours.Controls.getProperty(this, 'alias');
				var template = '<iframe width="800px" height="400px" src="http://vr360.globalvision.ch/' + alias + '" ></iframe>';
				$('#vrModal .modal-body').text(template);
				$('#vrModal').modal('show');
			})
		},

		/**
		 * Show edit tour modal
		 * @param el
		 */
		showEdit: function (el)
		{
			var tourData = $(el).data();
			$(el).find('.editTour').on('click', function (event)
			{
				$.ajax({
					url: 'index.php',
					type: 'POST',
					data: {
						id: tourData.tour.id,
						view: 'tour',
						task: 'ajaxGetTourHtml',
					},
					async: true,
					cache: false,
				})
					.done(function (data, textStatus, jqXHR)
					{
						if (data.status)
						{
							// Update title
							$('#vrTour .modal-title').html('<i class="fa fa-plus-square" aria-hidden="true"></i> Edit tour <br/><small>' + tourData.tour.name + '</small>');
							// Update body
							$('#vrTour .modal-body .container-fluid').html(data.data.html);
							$('#vrTour').modal('show');
							$( "#tour-panos" ).sortable({
								placeholder: "ui-state-highlight"
							});
							$( "#tour-panos" ).disableSelection();
						}
					})
			})
		},
		/**
		 *
		 * @param el
		 */
		showEditHotspot: function (el)
		{
			var tourData = $(el).data();
			$(el).find('.editTourHotspot').on('click', function (event)
			{
				$.ajax({
					url: 'index.php',
					type: 'POST',
					data: {
						id: tourData.tour.id,
						view: 'tour',
						task: 'ajaxGetHotspotEditorHtml',
					},
					async: true,
					cache: false,
				})
					.done(function (data, textStatus, jqXHR)
					{
						if (data.status)
						{
							// Update title
							$('#vrTour .modal-title').html('<i class="fa fa-plus-square" aria-hidden="true"></i> Add hotspot <br/><small>' + tourData.tour.name + '</small>');

							// Update body
							$('#vrTour .modal-body .container-fluid').html(data.data.html);
							$('#vrTour').modal('show');
						}
					})

			})
		},

		showPreview: function (el)
		{
			var data = $(el).data();

			$(el).find('.previewTour').on('click', function (event)
			{
				var newTab = window.open('./' + data.tour.alias, '_blank');

				//newTab.focus();
			})

		},

		hooks: function ()
		{
			$('body').on('click', '.addNew', function ()
			{
				vrAdmin.Tours.addNew()
			})
			$('#vTours tbody tr').each(function ()
			{
				vrAdmin.Tours.showEmbed(this);
				vrAdmin.Tours.showEdit(this);
				vrAdmin.Tours.showEditHotspot(this);
				vrAdmin.Tours.showPreview(this);
			});
		}
	}
})(window, jQuery.noConflict());

