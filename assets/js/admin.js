(function (w, $) {
	var vrAdmin = {

		/**
		 * Load form to add new tour
		 */
		addTour: function () {
			$.ajax({
				url: "index.php",
				type: "POST",
				data: {
					view: "tour",
					task: "ajaxGetTourHtml"
				},
				dataType: 'json',
				beforeSend: function () {
					vrAdmin.Waiting.waiting();
				}
			})
				.done(function (data, textStatus, jqXHR) {
					if (data.status) {
						vrAdmin.Modal.showModal('<i class="fas fa-plus"></i> New tour', data.data.html);
						vrAdmin.Waiting.success();
					}
					else {
						vrAdmin.Log.appendArray(data.messages);
						vrAdmin.Waiting.stay();
					}
				})
				.fail(function (jqXHR, textStatus, errorThrown) {
					alert('Ajax failed');
					vrAdmin.Waiting.stay();
				})
				.always(function () {
					vrAdmin.Waiting.stay();
				});
		},

		/**
		 * Load form to edit a tour
		 */
		editTour: function (el) {
			var tourId = $(el).attr("data-tour-id");

			$.ajax({
				url: "index.php",
				type: "POST",
				data: {
					id: tourId,
					view: "tour",
					task: "ajaxGetTourHtml"
				},
				dataType: 'json',
				beforeSend: function () {
					vrAdmin.Waiting.waiting();
				}
			})
				.done(function (data, textStatus, jqXHR) {
					if (data.status) {
						vrAdmin.Modal.showModal('<i class="fas fa-edit"></i> Edit tour', data.data.html);
						vrAdmin.Waiting.success();
					}
					else {
						vrAdmin.Log.appendArray(data.messages);
						vrAdmin.Waiting.stay();
					}
				})
				.fail(function (jqXHR, textStatus, errorThrown) {
					alert('Ajax failed');
					vrAdmin.Waiting.stay();
				})
				.always(function( jqXHR, textStatus, jqXHR ) {
					vrAdmin.Waiting.stay();
				});
		},

		/**
		 * Load form to edit a tour' hotspot
		 * @param el
		 */
		editTourHotspot: function (el) {
			var tourId = $(el).attr("data-tour-id");

			$.ajax({
				url: "index.php",
				type: "POST",
				data: {
					id: tourId,
					view: "tour",
					task: "ajaxGetHotspotEditorHtml"
				},
				async: true,
				cache: false,
				beforeSend: function () {
					vrAdmin.Waiting.waiting();
				}
			})
				.done(function (data, textStatus, jqXHR) {
					if (data.status) {
						vrAdmin.Modal.showModal('<i class="fas fa-edit"></i> Hotspots management', data.data.html);
						vrAdmin.Waiting.success();
					}
					else {
						vrAdmin.Log.appendArray(data.messages);
						vrAdmin.Waiting.stay();
					}
				})
				.fail(function () {
					vrAdmin.Waiting.stay();
				})
				.always(function () {
					vrAdmin.Waiting.stay();
				});
		},

		editProfile: function (el) {
			$.ajax({
				url: "index.php",
				type: "POST",
				data: {
					view: "user",
					task: "ajaxGetUserHtml"
				},
				async: true,
				cache: false,
				beforeSend: function () {
					vrAdmin.Waiting.waiting();
				}
			})
				.done(function (data, textStatus, jqXHR) {
					if (data.status) {
						vrAdmin.Modal.showModal('<i class="fa fa-user-circle" aria-hidden="true"></i> Edit profile', data.data.html);
						vrAdmin.Waiting.success();
					}
					else {
						vrAdmin.Log.appendArray(data.messages);
						vrAdmin.Waiting.stay();
					}
				})
				.fail(function () {
					vrAdmin.Waiting.stay();
				})
				.always(function () {
					vrAdmin.Waiting.stay();
				});
		},

		/**
		 * Show embed form
		 * @param el
		 */
		embedTour: function (el) {
			var tourId = $(el).attr("data-tour-id");

			$.ajax({
				url: "index.php",
				type: "POST",
				data: {
					id: tourId,
					view: "tour",
					task: "ajaxGetTourEmbedHtml"
				},
				async: true,
				cache: false,
				beforeSend: function () {
					vrAdmin.Waiting.waiting();
				}
			})
				.done(function (data, textStatus, jqXHR) {
					if (data.status) {
						vrAdmin.Modal.showModalWithText('<i class="fa fa-code" aria-hidden="true"></i> Embed code', data.data.html);
						vrAdmin.Waiting.success();
					}
					else {
						vrAdmin.Log.appendArray(data.messages);
						vrAdmin.Waiting.stay();
					}
				})
				.fail(function () {
					vrAdmin.Waiting.stay();
				})
				.always(function () {
					vrAdmin.Waiting.stay();
				});
		},

		/**
		 * Delete tour
		 */
		deleteTour: function (el) {
			var tourId = $(el).attr("data-tour-id");

			$.ajax({
				url: "index.php",
				type: "POST",
				data: {
					id: tourId,
					view: "tour",
					task: "ajaxDeleteTour"
				},
				async: true,
				cache: false,
				beforeSend: function () {
					vrAdmin.Waiting.waiting();
				}
			})
				.done(function (data, textStatus, jqXHR) {
					if (data.status == true) {
						$("table#vTours tr#vtour-" + tourId).remove();
						vrAdmin.Waiting.success();
					}
				})
				.fail(function () {
					vrAdmin.Waiting.stay();
				})
				.always(function () {
					vrAdmin.Waiting.stay();
				});
		},

		/**
		 * Hooks event.
		 */
		hooks: function () {
			// Reset search
			$('body').on('click', '#reset-search', function (event) {
				event.preventDefault();
				$('input[name="keyword"]').val('');
				$('form[name="search-form"]').submit();
			})

			$("body").on("click", ".addNew", function () {
				vrAdmin.addTour();
			});

			$("body").on("click", ".editTour", function () {
				vrAdmin.editTour(this);
			});

			$("body").on("click", ".editTourHotspot", function () {
				vrAdmin.editTourHotspot(this);
			});

			// Edit profile
			$("body").on("click", "a.navbar-brand", function () {
				vrAdmin.editProfile(this);
			});

			// Save profile
			$("body").on("submit", "form#form-user", function (event) {
				event.preventDefault();
				var formData = new FormData(this);

				$.ajax({
					url: "index.php",
					type: "POST",
					data: formData,
					async: true,
					cache: false,
					processData: false,
					contentType: false,
					/**
					 *
					 */
					beforeSend: function () {
						vrAdmin.Waiting.waiting();
					}
				})
					.done(function (data, textStatus, jqXHR) {
						vrAdmin.Log.appendArray(data.messages);
						vrAdmin.Waiting.stay();
					})
					.fail(function () {
						vrAdmin.Waiting.stay();
					})
					.always(function () {
						vrAdmin.Waiting.stay();
					});
			});

			$("body").on("click", ".embedCode", function () {
				vrAdmin.embedTour(this);
			});

			$("body").on("click", "button.removeTour", function (event) {
				event.preventDefault();

				if (confirm("Confirm delete a tour")) {
					vrAdmin.deleteTour(this);
				}
			});

			$("body").on("click", "button.btn-log-close", function (event) {
				event.preventDefault();

				vrAdmin.Log.reset();
				$("body").removeClass("loading");
			});

			// Call hooks for each tour
			vrTour.hooks();
		}
	};

	var vrTour = {

		/**
		 * Generate alias with input name
		 */
		generateAlias: function () {
			// Prepare
			var alias = $("#form-tour input#name").val();

			alias = alias.toLowerCase().replace(/\s+/g, "-")           // Replace spaces with -
				.replace(/[^\w\-]+/g, "")       // Remove all non-word chars
				.replace(/\-\-+/g, "-")         // Replace multiple - with single -
				.replace(/^-+/, "")             // Trim - from start of text
				.replace(/-+$/, "");            // Trim - from end of text
			$("#form-tour input#alias").val(alias);
		},

		/**
		 * Hook events
		 */
		hooks: function () {
			$("body").on("blur", "#form-tour input#name", function () {
				vrTour.generateAlias();
			});

			$('body').on('blur', '#form-tour input#alias', function () {
				var tourId = $('#form-tour input[name="id"]');
				var alias = $('#form-tour input#alias').val();

				if (tourId.length == 1) {
					tourId = $(tourId).val();
				}
				else {
					tourId = 0;
				}

				$.ajax({
					url: "index.php",
					type: "POST",
					data: {
						id: tourId,
						alias: alias,
						view: "tour",
						task: "ajaxValidateAlias"
					},
					dataType: 'json',
					/**
					 *
					 */
					beforeSend: function () {
						vrAdmin.Waiting.waiting();
					}
				})
					.done(function (data, textStatus, jqXHR) {
						if (data.status == true) {
							vrAdmin.Waiting.success();
						}
						else {
							vrAdmin.Log.appendArray(data.messages);
							vrAdmin.Waiting.stay();
						}
					})
					.fail(function (jqXHR, textStatus, errorThrown) {
						alert('Ajax failed');
						vrAdmin.Waiting.stay();
					})
					.always(function( jqXHR, textStatus, jqXHR ) {
						vrAdmin.Waiting.stay();
					});
			})

			/**
			 * Main function to submit save tour
			 */
			$("body").on("submit", "form#form-tour", function (event) {
				event.preventDefault();

				var formData = new FormData(this);

				// First ajax used for file uploading
				$.ajax({
					url: "index.php",
					type: "POST",
					data: formData,
					async: true,
					cache: false,
					processData: false,
					contentType: false,
					beforeSend: function () {
						$("body").addClass("loading");
						$('#overlay-waiting .btn-log-close').addClass('hide');
					}
				})
					.done(function (data, textStatus, jqXHR) {
						vrAdmin.Log.appendArray(data.messages);
						vrAdmin.Waiting.stay();
					})
					.fail(function () {
						$('#overlay-waiting .btn-log-close').removeClass('hide');
					})
					.always(function () {
						$('#overlay-waiting .btn-log-close').removeClass('hide');
					});
			});

			vrScene.hooks();
			vrHotspot.hooks();
		}
	};

	var vrScene = {

		addNew: function () {
			var sceneHtml = $(".hidden .scene").parent().html();

			$("#scenes .alert").remove();
			$("#scenes").append(sceneHtml);

			$.validate({
				modules: "file"
			});
		},

		makeDefault: function (el) {
			$("#form-tour input[type='checkbox'][name='sceneDefault']:checked").prop("checked", false);
			$(el).prop("checked", true);
		},

		remove: function (el) {
			$(el).parent().parent().parent().parent().remove();
		},

		/**
		 *
		 */
		hooks: function () {
			$("body").on("click", "button#addScene", function () {
				vrScene.addNew();
			});

			// Remove a scene
			$("body").on("click", "#form-tour button.removeScene", function (event) {
				var el = event.target || event.currentTarget;
				vrScene.remove(el);
			});

			$("body").on("change", "#form-tour input[type='checkbox'][name='sceneDefault']", function (event) {
				var el = event.target || event.currentTarget;
				vrScene.makeDefault(el);
			});
		}
	};

	var vrHotspot = {
		/**
		 *
		 * @param el
		 * @returns {boolean}
		 */
		saveHotspot: function (el) {
			var tourId = $(el).data("tour-id");
			var ifHotspotObj = document.getElementById("editTourHotspots").contentWindow;

			if (!ifHotspotObj.isReady()) {
				alert("Please finish to add hotspot before saving or click cancel");
				return false;
			}

			$.ajax({
				url: "index.php",
				type: "POST",
				data: {
					view: "hotspot",
					task: "ajaxSaveHotspot",
					id: tourId,
					hotspotList: JSON.stringify(ifHotspotObj.superHotspot.getData().hotspotList),
					defaultViewList: JSON.stringify(ifHotspotObj.defaultViewList)
				},
				async: true,
				cache: false,
				beforeSend: function () {
					vrAdmin.Waiting.waiting();
				}
			})
				.done(function (data, textStatus, jqXHR) {
					// Actually we won't remove body.loading by ourself. Provide button Close / Reload to do that.
					vrAdmin.Log.appendArray(data.messages);
					vrAdmin.Waiting.stay();
				})
				.fail(function () {
					$('#overlay-waiting .btn-log-close').removeClass('hide');
				})
				.always(function () {
					$('#overlay-waiting .btn-log-close').removeClass('hide');
				});
		},
		/*
		 *
		 */
		hooks: function () {
			$("body").on("click", "button#saveHotspots", function (event) {
				event.preventDefault();
				vrAdmin.Tour.Hotspot.saveHotspot(this);
			});
		}
	};

	vrAdmin.Tour = vrTour;
	vrAdmin.Tour.Scene = vrScene;
	vrAdmin.Tour.Hotspot = vrHotspot;

	w.vrAdmin = vrAdmin;

	$(document).ready(function () {
		w.vrAdmin.hooks();
	});

})(window, jQuery);