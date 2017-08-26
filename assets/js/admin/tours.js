(function (w, $) {

    var vrAdminTours = {

        $this: this,
        rowTemplate: $('#vtourList').html(),
        tours: [],

        /**
         *
         */
        getTours: function () {
            $.ajax({
                url: './index.php',
                data: {
                    task: 'getTours'
                },
                dataType: 'json'
            })
                .done(function (data, textStatus, jqXHR) {
                    vrAdminTours.tours = data;
                    vrAdminTours.renderTours();
                })
                .fail(function () {
                    // @TODO Show error message
                })
        },
        /**
         * Render all tours
         */
        renderTours: function ()
        {
            // Reset current
            $('#vtourList').html('');

            // Render each tour
            for (var i in vrAdminTours.tours) {
                vrAdminTours.renderTour(vrAdminTours.tours[i]);
            }

            // if (g_t) {
            //     var hash = window.location.hash.substring(1);
            //     thisAlias.manager.app.editor.edit("<?php echo (isset($_GET['t']) ? $_GET['t'] : 'nothing'); ?>");
            //     history.pushState('', document.title, 'index.php');
            // }
        },
        /**
         * Render a tour
         * @param tour
         */
        renderTour: function(tour)
        {
            var url = tour.alias;
            // Get template
            var rowTemplate = vrAdminTours.rowTemplate;
            var regex = '';

            // Replacing
            for (fieldName in tour)
            {
                rowTemplate = rowTemplate.replace(new RegExp('{{' + fieldName + '}}', 'g'), tour[fieldName]);


                if (tour.status == 0)
                {
                    rowTemplate = rowTemplate.replace(/{{tour_status_class}}/g, 'danger');
                    rowTemplate = rowTemplate.replace(/{{status}}/g, '<p id="pid_' + tour.u_id + '"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> Processing</p>');
                }
                else
                {
                    rowTemplate = rowTemplate.replace(/{{tour_status_class}}/g, 'success');
                    rowTemplate = rowTemplate.replace(/{{status}}/g, '<p><i class="fa fa-check" aria-hidden="true"></i> Ready</p>');
                }
            }

            // Append row to browser
            vrAdminTours.appendRow(rowTemplate);
        },
        removeTour: function (UID)
        {
            if (!confirm("Are you sure want to remove: \"" + vrAdminTours.tours[UID].tour_des + "\""))
                return 0;

            $.ajax({
                url: './index.php',
                data: {
                    task: 'unpublished',
                    UIDx: UID
                },
                dataType: 'json'
            })
                .done(function (data, textStatus, jqXHR) {
                    if (data.status) {
                        $('tr#vtourData_' + UID).fadeOut();
                    }

                });
        },
        /**
         *
         * @param row
         */
        appendRow: function(row)
        {
            $('#vtourList').append(row);
        },
        /**
         * Show modal to get embed code
         * @param UID
         */
        getEmbed: function(UID)
        {
            var template = '<iframe width="800px" height="400px" src="http://vr360.globalvision.ch/{{alias}}" ></iframe>';
            var cTmp = template.replace(/{{alias}}/g, vrAdminTours.tours[UID].alias);

            $('#embedModal .modal-body').text(cTmp);
            $('#embedModal').modal('show');
        },
        /**
         * General object init
         */
        init: function()
        {
            vrAdminTours.getTours();
        }
    }
    w.vrAdmin.vrTours = vrAdminTours;

    $(document).ready(function(){
        w.vrAdmin.vrTours.init();
    })
})(window, jQuery);