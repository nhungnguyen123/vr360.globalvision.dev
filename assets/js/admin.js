(function (w, $) {

	var vrAdmin = {
		/**
		 *
		 */
		addPano: function (el) {
			var target =$(el).parent().parent().parent().parent()
			$(target).find('#divMain').append($('#divMain #rootPano').html());
		},

		removePano: function (el) {
			if ($('#divMain .pano').length == 1) {

			}
			else {
				$(el).parent().remove();
			}

		},

		/**
		 * Show modal with embed code
		 */
		hookButtonGetEmbed: function () {
			$(document).on('click', '.embedCode', function (e) {
				var elId = $(this).attr('data-id');
				vrAdmin.vrTours.getEmbed(elId);
				e.preventDefault();
			})
		},

		/**
		 * Show model with edit form
		 */
		hookButtonEditTour: function () {
			$(document).on('click', '.editTour', function (e) {
				var elId = $(this).attr('data-id');

				// Ajax to get edit tour HTML form
				$.ajax({
					url: 'index.php',
					type: 'POST',
					data: {
						'task': 'getEditTourHtml',
						'id': parseInt(elId)
					},
					async: true,
					cache: false
				})
					.done(function (data, textStatus, jqXHR) {
						$('#editTour.modal .modal-body .container-fluid').html(data.data.html);
						$('#editTour').modal('show');
					})

				e.preventDefault();
			})
		},

		hookButtonRemoveTour: function()
		{
			$(document).on('click', '.removeTour', function (e) {

				if ( confirm("Are you sure") )
				{
					var elId = parseInt($(this).attr('data-id'));

					// Ajax to get edit tour HTML form
					$.ajax({
						url: 'index.php',
						type: 'POST',
						data: {
							'task': 'removeTour',
							'id': elId
						},
						async: true,
						cache: false
					})
						.done(function (data, textStatus, jqXHR) {
							if (data.status === true)
							{
								$('#vtour-' + elId).fadeOut(300, function(){ $(this).remove();});
							}
						})
				}

				e.preventDefault();
			})
		},

		/**
		 * Create new tour
		 */
		hookButtonCreateTour: function () {
			$(document).on('submit', '#createTour', function (e) {
				var formData = new FormData(this);
				// Prepare form data
				formData.append('task', 'createTour');
				formData.append('step', 'upload');
				$.ajax({
					url: 'index.php',
					type: 'POST',
					data: formData,
					async: true,
					cache: false,
					contentType: false,
					processData: false
				})
				/**
				 * Request success
				 */
					.done(function (data, textStatus, jqXHR) {
						vrLog.append(data.message);

						// Upload file success
						if (data.status == true) {
							reqData = new FormData();

							// Prepare data for next ajax
							reqData.append(data.data.token, 1);
							reqData.append('task', 'createTour');
							reqData.append('step', 'generate');
							reqData.append('id', data.data.id);
							$.ajax({
								url: 'index.php',
								type: 'POST',
								data: reqData,
								async: true,
								cache: false,
								contentType: false,
								processData: false
							})
							/**
							 * Request success
							 */
								.done(function (data, textStatus, jqXHR) {
									vrLog.append(data.message);
								})
						}

					})

				e.preventDefault();
			})
		},

		/**
		 * Add more pano
		 */
		hookButtonAddPano: function () {
			$(document).on('click', 'button#addPano', function (e) {
				vrAdmin.addPano(this);
				e.preventDefault();
			})
		},
	}

	w.vrAdmin = vrAdmin;

	$(document).ready(function () {
		vrAdmin.hookButtonGetEmbed();
		vrAdmin.hookButtonEditTour();
		vrAdmin.hookButtonRemoveTour();

		vrAdmin.hookButtonAddPano();
		vrAdmin.hookButtonCreateTour();
	})
})(window, jQuery);
