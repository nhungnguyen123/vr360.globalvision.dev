(function (w, $) {

	var vrAdmin = {};

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
		addNew: function () {
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
				.done(function (data, textStatus, jqXHR) {
					if (data.status) {
						// Update title
						$('#vrTour .modal-title').html('<i class="fa fa-plus-square" aria-hidden="true"></i> New tour');
						// Update body
						$('#vrTour .modal-body .container-fluid').html(data.data.html);
						$('#vrTour').modal('show');
					}
				});

			$('#vrTour').modal('show');
		},

		/**
		 * Show embed code modal
		 * @param el
		 */
		showEmbed: function (el) {
			$(el).find('.embedCode').on('click', function (event) {
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
		showEdit: function (el) {
			var tourData = $(el).data();
			$(el).find('.editTour').on('click', function (event) {
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
					.done(function (data, textStatus, jqXHR) {
						if (data.status) {
							// Update title
							$('#vrTour .modal-title').html('<i class="fa fa-plus-square" aria-hidden="true"></i> Edit tour <br/><small>' + tourData.tour.name + '</small>');
							// Update body
							$('#vrTour .modal-body .container-fluid').html(data.data.html);
							$('#vrTour').modal('show');
						}
					})
			})
		},
		showEditHotspot: function (el) {
			var data = $(el).data();
			$(el).find('.editTourHotspot').on('click', function (event) {
				$.ajax({
					url: 'index.php',
					type: 'POST',
					data: {
						id: data.tour.id,
						view: 'tour',
						task: 'ajaxGetHotspotEditorHtml',
					},
					async: true,
					cache: false,
				})
					.done(function (data, textStatus, jqXHR) {
						if (data.status) {
							// Update title

							// Update body
							$('#vrTour .modal-body .container-fluid').html(data.data.html);
							$('#vrTour').modal('show');
						}
					})

			})
		},

		showPreview: function (el) {
			var data = $(el).data();

			$(el).find('.previewTour').on('click', function (event) {
				var newTab = window.open('./' + data.tour.alias, '_blank');

				//newTab.focus();
			})

		},

		hooks: function () {
			$('body').on('click', '.addNew', function () {
				vrAdmin.Tours.addNew()
			})
			$('#vTours tbody tr').each(function () {
				vrAdmin.Tours.showEmbed(this);
				vrAdmin.Tours.showEdit(this);
				vrAdmin.Tours.showEditHotspot(this);
				vrAdmin.Tours.showPreview(this);
			});
		}
	}

	/**
	 * Handle a tour
	 * @type {{validate: validate, hooks: hooks}}
	 */
	vrAdmin.Tour = {
		/**
		 * Validate form fields
		 * @returns {string}
		 */
		validate: function () {
			vrAdmin.Log.append('File validating');

			// File validate
			$('#tour-panos input[type=file]').each(function () {
				var file = this.files[0];

				// File type validate
				if (file.type != 'image/jpeg' && file.type != 'image/png') {
					return 'File format is not valid'
				}

				// File size validate
				var allowedFilesize = 26214400; // 25 MB
				var minFilesize = 5242880; // 5 MB

				if (file.size >= allowedFilesize || file.size <= minFilesize) {
					return 'File size is not valid';
				}
			})

			return '';
		},
		disableForm: function () {
			$('form input').prop("disabled", true);
			$('form button').prop("disabled", true);
			$('body').addClass('loading');
		},
		enableForm: function () {
			$('form input').prop("disabled", false);
			$('form button').prop("disabled", false);

			$('body').removeClass('loading');
		},

		/**
		 * Alert when ajax failed
		 * @param data
		 * @param textStatus
		 * @param jqXHR
		 */
		throwFail: function (data, textStatus, jqXHR) {
			alert(textStatus);

			// Release form
			vrAdmin.Tour.enableForm();
		},

		/**
		 *
		 * @param data
		 */
		removeTour: function (data) {
			$.ajax({
				url: 'index.php',
				type: 'POST',
				data: {
					view: 'tour',
					task: 'ajaxRemoveTour',
					id: data.id
				}
			})
				.done(function (data, textStatus, jqXHR) {

					if (data.status == true) {
						$('#vtour-' + data.data.id).fadeOut("slow");
					}
					else {
						alert('Something wrong');
					}
				})
		},
		saveHotspot: function (data)
		{
			var ifHotspotObj = document.getElementById('editTourHotspots').contentWindow;
			if (!ifHotspotObj.rdy4save())
			{
				alert('Please finish to add hotspot before saving or click cancel');
				return false;
			}

			$.ajax({
				url: 'index.php',
				type: 'POST',
				data: {
					view: 'tour',
					task: 'ajaxSaveHotspot',
					id: data.sceneId,
					hotspotList: JSON.stringify(ifHotspotObj.superHotspot.getData().hotspotList)
				},
				async: true,
				cache: false,
			}).done(function (data, textStatus, jqXHR) {
					if (data.status === true) {

						vrAdmin.Log.appendArray(data.messages);
						vrAdmin.Log.append('Page reloading ...');

						// Reload page
						setTimeout(location.reload(), 2000);
					}
					else {
						// Append messages
						vrAdmin.Log.appendArray(data.messages);
					}
			})
		},
		/**
		 * Hooks
		 */
		hooks: function () {
			// Create & edit tour
			$('body').on('submit', '#createTour', function (event) {
				vrAdmin.Log.reset();

				// @TODO JS Filter
				validate = vrAdmin.Tour.validate();

				var formData = new FormData(this);

				// First ajax used for file uploading
				$.ajax({
					url: 'index.php',
					type: 'POST',
					data: formData,
					async: true,
					cache: false,
					contentType: false,
					processData: false,
					beforeSend: function (xhr) {
						// @TODO Blocking elements
						vrAdmin.Tour.disableForm();
					}
				})
				/**
				 * Ajax to create tour database
				 */
					.done(function (data, textStatus, jqXHR) {
							// File upload success
							if (data.status === true) {

								// Append messages
								vrAdmin.Log.appendArray(data.messages);

								// Second ajax to create tour into database
								var postData = data.data.tour;
								postData.view = 'tour';
								postData.task = 'ajaxCreateTour';
								postData.step = 'createTour';

								if (typeof data.data.id !== 'undefined') {
									postData.id = data.data.id;
								}

								// Ajax to create tour database
								$.ajax({
									url: 'index.php',
									type: 'POST',
									data: postData,
									async: true,
									cache: false,
								})
								/**
								 * Ajax to generate 360
								 */
									.done(function (data, textStatus, jqXHR) {
										if (data.status === true) {

											vrAdmin.Log.appendArray(data.messages);

											vrAdmin.Log.append('Generating vr360');

											// Last ajax to generate tour 360
											$.ajax({
												url: 'index.php',
												type: 'POST',
												data: {
													view: 'tour',
													task: 'ajaxGenerateTour',
													id: data.data.id
												},
												async: true,
												cache: false,
											})
												.done(function (data, textStatus, jqXHR) {
													if (data.status === true) {

														vrAdmin.Log.appendArray(data.messages);
														vrAdmin.Log.append('Page reloading ...');

														// Reload page
														setTimeout(location.reload(), 2000);
													}
													else {
														// Append messages
														vrAdmin.Log.appendArray(data.messages);
													}
												})
												.always(function (data, textStatus, jqXHR) {
													if (textStatus == 'timeout') {
														vrAdmin.Tour.throwFail(data, textStatus, jqXHR);
													}

													// Release form
													vrAdmin.Tour.enableForm();
												})
												// Fail case always release form and alert
												.fail(function (data, textStatus, jqXHR) {
													vrAdmin.Tour.throwFail(data, textStatus, jqXHR);
												});
										}
										else {
											// Append messages
											vrAdmin.Log.appendArray(data.messages);

											// Release form
											vrAdmin.Tour.enableForm();
										}
									})
									// Fail case always release form and alert
									.fail(function (data, textStatus, jqXHR) {
										vrAdmin.Tour.throwFail(data, textStatus, jqXHR);
									});
							}
							else {
								// Append messages
								vrAdmin.Log.appendArray(data.messages);

								// Release form
								vrAdmin.Tour.enableForm();
							}
						}
					)
					// Fail case always release form and alert
					.fail(function (data, textStatus, jqXHR) {
						vrAdmin.Tour.throwFail(data, textStatus, jqXHR);
					});

				event.preventDefault();
			})

			// Hook on remove tour
			$('body').on('click', '.removeTour', function (event) {
				if (confirm("Confirm delete a tour")) {
					var data = $(this).parent().parent().data();

					vrAdmin.Tour.removeTour(data.tour);

					event.preventDefault();
				}
			})

			$('body').on('click', '#saveHotspots', function (event) {
					vrAdmin.Tour.saveHotspot($(this).data());
			})
		}
	}
	vrAdmin.Panos = {};

	/**
	 * Handle a pano
	 * @type {{hooks: hooks}}
	 */
	vrAdmin.Pano = {
		/**
		 * Pano hooking
		 */
		hooks: function () {
			// Add more pano
			$('body').on('click', '#addPano', function () {
				var tourHtml = $('.hidden .pano').parent().html();
				$('#tour-panos').append(tourHtml);
			})

			// Remove a pano
			$('body').on('click', '.removePano', function (event) {
				$(this).parent().parent().remove();
			})
		}
	};

	w.vrAdmin = vrAdmin;

	$(document).ready(function () {

		vrAdmin.Tours.hooks();
		vrAdmin.Tour.hooks();
		vrAdmin.Pano.hooks();

		$('input').tooltip();
	})
})(window, jQuery);
