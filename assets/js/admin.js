(function (w, $) {

    var vrAdmin = {

        $this: this,
        formTemplate: $('#divMain').html(),

        addNew: function () {
            var index = $('#divMain .pano').length;
            var template = vrAdmin.formTemplate.replace(new RegExp('{{imgId}}', 'g'), index++)
            $('#divMain').append(template);
        },
        removePano: function(el)
        {
            if ($('#divMain .pano').length == 1)
            {

            }
            else
            {
                $(el).parent().remove();
            }

        },
        createTour: function()
        {
            vrLog.reset();
            vrLog.append('Collecting data ..')
        },
        init: function () {
            // Reset
            $('#divMain').html('');
            vrAdmin.addNew();
        }
    }

    $(document).ready(function () {
        vrAdmin.init();
    })
    w.vrAdmin = vrAdmin;
})(window, jQuery);