(function (w, $) {

	var vrAdmin = {
		/**
		 * Add new tour
		 */
		addTour: function () {
			$.ajax({
				url: "index.php",
				type: "POST",
				data: {
					view: "tour",
					task: "ajaxGetTourHtml"
				},
				async: true,
				cache: false,
				beforeSend: function () {
					$('body').addClass('loading');
				},
			}).done(function (data, textStatus, jqXHR) {
				if (data.status) {
					$('body').removeClass('loading');

					// Update title
					$("#vrTour .modal-title").html("<i class=\"fa fa-plus-square\" aria-hidden=\"true\"></i> New tour");
					// Update body
					$("#vrTour .modal-body .container-fluid").html(data.data.html);
					$("#vrTour").modal("show");

					// Init default pano
					$(".addPano").click();
				}
			});

			$("#vrTour").modal("show");
		},

		/**
		 * Edit tour
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
				async: true,
				cache: false
			}).done(function (data, textStatus, jqXHR) {
				if (data.status) {
					// Update title
					$("#vrTour .modal-title").html("<i class=\"fa fa-plus-square\" aria-hidden=\"true\"></i> Edit tour");
					// Update body
					$("#vrTour .modal-body .container-fluid").html(data.data.html);
					$("#vrTour").modal("show");

					// Init default pano
					$(".addPano").click();
				}
			});

			$("#vrTour").modal("show");
		},

		/**
		 * Edit tour
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
				cache: false
			}).done(function (data, textStatus, jqXHR) {
				if (data.status) {
					// Update title
					$("#vrTour .modal-title").html("<i class=\"fa fa-plus-square\" aria-hidden=\"true\"></i> Add hotspot <br/>");

					// Update body
					$("#vrTour .modal-body .container-fluid").html(data.data.html);
					$("#vrTour").modal("show");
				}
			});

			$("#vrTour").modal("show");
			vrAdmin.Tour.Hotspot.hooks();
		},

		/**
		 * Embed tour
		 */
		embedTour: function (el) {
			var alias = $(el).attr("data-tour-alias");
			var template = "<iframe width=\"800px\" height=\"400px\" src=\"/" + alias + "\" ></iframe>";

			$("#vrTour .modal-title").html("<i class=\"fa fa-plus-square\" aria-hidden=\"true\"></i> Embed code");
			$("#vrTour .modal-body .container-fluid").text(template);
			$("#vrTour").modal("show");
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
				cache: false
			}).done(function (data, textStatus, jqXHR) {
				if (data.status == true) {
					$("table#vTours tr#vtour-" + tourId).remove();
				}
			});
		},

		/**
		 * Hooks event.
		 */
		hooks: function () {
			$("body").on("click", ".addNew", function () {
				vrAdmin.addTour();
			});

			$("body").on("click", ".editTour", function () {
				vrAdmin.editTour(this);
			});

			$("body").on("click", ".editTourHotspot", function () {
				vrAdmin.editTourHotspot(this);
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

			vrTour.hooks();
		}
	};

	var vrTour = {

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

		hooks: function () {

			vrScene.hooks();

			$("body").on("blur", "#form-tour input#name", function () {
				vrTour.generateAlias();
			});

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
						$('body').addClass('loading')
					}
				}).done(function (data, textStatus, jqXHR) {
					vrAdmin.Log.appendArray(data.messages);
				})
			});
		}
	};

	var vrScene = {

		addNew: function () {
			var sceneHtml = $(".hidden .scene").parent().html();

			$("#scenes").append(sceneHtml);

			$.validate({
				modules: "file"
			});
		},

		makeDefault: function (el) {
			$("#form-tour input[type='checkbox'][name='sceneDefault']:checked").prop('checked', false);
			$(el).prop('checked', true);
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
		saveHotspot: function (el) {
			var tourId = $(el).data('tour-id');
			var ifHotspotObj = document.getElementById('editTourHotspots').contentWindow;

			if (!ifHotspotObj.isReady()) {
				alert('Please finish to add hotspot before saving or click cancel');
				return false;
			}

			$.ajax({
				url: 'index.php',
				type: 'POST',
				data: {
					view: 'hotspot',
					task: 'ajaxSaveHotspot',
					id: tourId,
					hotspotList: JSON.stringify(ifHotspotObj.superHotspot.getData().hotspotList),
					defaultViewList: JSON.stringify(ifHotspotObj.defaultViewList)
				},
				async: true,
				cache: false,
			}).done(function (data, textStatus, jqXHR) {
				console.log(data);
			});
		},
		/*
		 *
		 */
		hooks: function () {
			console.log("vHotspot hooks");

			$('body').on('click', '#saveHotspots', function (event) {
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