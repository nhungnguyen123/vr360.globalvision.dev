(function (w, $) {
	/**
	 * Handle a tour
	 * @type {{validate: validate, hooks: hooks}}
	 */
	vrAdmin.Tour = {
		/**
		 *
		 */
		disableForm: function () {
			$('form input').prop("disabled", true);
			$('form button').prop("disabled", true);
			$('body').addClass('loading');
		},

		/**
		 *
		 */
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

		/**
		 *
		 * @param data
		 * @returns {boolean}
		 */
		saveHotspot: function (data) {
			var ifHotspotObj = document.getElementById('editTourHotspots').contentWindow;
			if (!ifHotspotObj.rdy4save()) {
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
					hotspotList: JSON.stringify(ifHotspotObj.superHotspot.getData().hotspotList),
					defaultViewList: JSON.stringify(ifHotspotObj.defaultViewList)
				},
				async: true,
				cache: false,
			}).done(function (data, textStatus, jqXHR) {
				if (data.status === true) {

					vrAdmin.Log.appendArray(data.messages);
					vrAdmin.Log.append('Page reloading ...');

					// Reload page
					setTimeout(window.location.replace('index.php'), 2000);
				}
				else {// Append messages
					vrAdmin.Log.appendArray(data.messages);
				}
			})
		},

		generateAlias: function()
		{
			$('body').on('blur', 'input[name=alias]', function(){
				// Prepare
				var alias = $(this).val();
				alias = alias.toLowerCase()
					.replace(/ /g,'-')
					.replace(/[^\w-]+/g,'');
				$(this).val(alias);
			})

		},
		/**
		 * Hooks
		 */
		hooks: function () {
			vrAdmin.Tour.generateAlias();

			// Create & edit tour
			$('body').on('submit', '#createTour', function (event) {
				vrAdmin.Log.reset();

				// JS Tour form validate
				vrAdmin.Validate.validate();

				// There is no errors
				if(vrAdmin.Validate.errors.length <= 0){
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

									// Append tour id if possible
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
															setTimeout(window.location.replace('index.php'), 2000);
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
				}
				else
				{
					console.log (vrAdmin.Validate.errors);
					alert ('File validate failed');
				}

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
})(window, jQuery.noConflict())