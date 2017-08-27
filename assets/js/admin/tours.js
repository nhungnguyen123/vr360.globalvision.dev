(function (w, $) {

    var vrAdminTours = {

        $this: this,
        rowTemplate: $('#vtourList').html(),
        tours: [],


        /**
         * Show modal to get embed code
         *
         * @param tourId
         */
        getEmbed: function(tourId)
        {
            var tour = vrAdminTours.getTour(tourId);
            var template = '<iframe width="800px" height="400px" src="http://vr360.globalvision.ch/' + tour.alias + '" ></iframe>';

            $('#embedModal .modal-body').text(template);
            $('#embedModal').modal('show');
        },

        getTour: function(id)
        {
            return $('#vtour-' + id).data('tour');
        }

    }
    w.vrAdmin.vrTours = vrAdminTours;

})(window, jQuery);