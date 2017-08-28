(function (w, $) {

    var vrAdmin = {
        /**
         *
         */
        addPano: function () {
            $('#divMain').append($('#divMain #rootPano').html());
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
                        if (data.status == 1)
                        {
                            formData.step = 'create';
                            $.ajax({
                                url: 'index.php',
                                type: 'POST',
                                data: formData,
                                async: false,

                                cache: false,
                                contentType: false,
                                processData: false
                            })
                                .done(function (data, textStatus, jqXHR){
                                    // Last step
                                    formData.step = 'generate';
                                    $.ajax({
                                        url: 'index.php',
                                        type: 'POST',
                                        data: formData,
                                        async: false,

                                        cache: false,
                                        contentType: false,
                                        processData: false
                                    })
                                        .done(function (data, textStatus, jqXHR){
                                            // Reload
                                        })
                                })
                        }

                    })

                e.preventDefault();
            })
        }

    }

    w.vrAdmin = vrAdmin;

    $(document).ready(function(){
        w.vrAdmin.hookFormCreateTour();
    })
})(window, jQuery);