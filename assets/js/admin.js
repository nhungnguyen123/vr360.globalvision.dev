(function (w, $) {

    var vrAdmin = {
        /**
         *
         */
        addPano: function () {
            $('#divMain').append($('#divMain .pano').html());
        },

        removePano: function (el) {
            if ($('#divMain .pano').length == 1) {

            }
            else {
                $(el).parent().remove();
            }

        },

        hookFormCreateTour: function () {
            $('#createTour').on('submit', function (e) {
                var formData = new FormData(this);
                formData.append('task', 'createTour');
                formData.append('step', 'upload');
                $.ajax({
                    url: 'index.php',
                    type: 'POST',
                    data: formData,
                    async: false,

                    cache: false,
                    contentType: false,
                    processData: false
                })
                    .done(function (data, textStatus, jqXHR) {

                    })

                e.preventDefault();
                return false;
            })
        }

    }

    w.vrAdmin = vrAdmin;
})(window, jQuery);