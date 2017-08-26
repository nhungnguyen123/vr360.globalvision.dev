(function(w, $){

    var vrProgress = {
        $this: this,
        update: function(percent)
        {
            $('.progress').removeClass('hide');
            $('.progress .progress-bar').attr('style', 'width: ' + percent + '%');
            $('.progress .sr-only').html(percent + '% completed');
        }
    }

    w.vrProgress = vrProgress;
})(window, jQuery);