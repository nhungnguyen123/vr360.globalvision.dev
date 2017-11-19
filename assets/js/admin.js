(function (w, $) {

    var vrAdmin = {
        /**
         * Add new tour
         */
        addTour: function () {
            $.ajax({
                url  : "index.php",
                type : "POST",
                data : {
                    view: "tour",
                    task: "ajaxGetTourHtml"
                },
                async: true,
                cache: false
            }).done(function (data, textStatus, jqXHR) {
                if (data.status) {
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
            var tourId = $(el).attr('data-tour-id');

            $.ajax({
                url  : "index.php",
                type : "POST",
                data : {
                    id   : tourId,
                    view : "tour",
                    task : "ajaxGetTourHtml"
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
            var tourId = $(el).attr('data-tour-id');

            $.ajax({
                url  : "index.php",
                type : "POST",
                data : {
                    id   : tourId,
                    view : "tour",
                    task : "ajaxGetHotspotEditorHtml"
                },
                async: true,
                cache: false
            }).done(function (data, textStatus, jqXHR) {
                if (data.status) {
                    // Update title
                    $('#vrTour .modal-title').html('<i class="fa fa-plus-square" aria-hidden="true"></i> Add hotspot <br/>');

                    // Update body
                    $('#vrTour .modal-body .container-fluid').html(data.data.html);
                    $('#vrTour').modal('show');
                }
            });

            $("#vrTour").modal("show");
        },

        /**
         * Embed tour
         */
        embedTour: function (el) {
            var alias = $(el).attr('data-tour-alias');
            var template = '<iframe width="800px" height="400px" src="http://localhost/vr360.globalvision.dev/' + alias + '" ></iframe>';

            $('#vrTour .modal-title').html('<i class="fa fa-plus-square" aria-hidden="true"></i> Embed code');
            $('#vrTour .modal-body .container-fluid').text(template);
            $('#vrTour').modal('show');
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

            vrTour.hooks();
        }
    };

    var vrTour = {

        generateAlias: function () {
            // Prepare
            var alias = $("#form-tour input#name").val();
            alias     = alias.toLowerCase().replace(/\s+/g, "-")           // Replace spaces with -
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
                    url        : "index.php",
                    type       : "POST",
                    data       : formData,
                    async      : true,
                    cache      : false,
                    processData: false,
                    contentType: false
                });
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
        /**
         *
         */
        hooks : function () {
            $("body").on("click", "button#addScene", function () {
                vrScene.addNew();
            });
        }
    };

    vrAdmin.Tour       = vrTour;
    vrAdmin.Tour.Scene = vrScene;

    w.vrAdmin = vrAdmin;

    $(document).ready(function () {
        w.vrAdmin.hooks();
    });

})(window, jQuery);
