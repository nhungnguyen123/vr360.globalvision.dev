(function(w, $){

    var vrLog = {
        $this: this,
        reset: function()
        {
            $('#vrLogging').html('');
        },
        append: function(message)
        {
            $('#vrLogging').append('<p>' + message + '</p>');
        }
    }

    w.vrLog = vrLog;
})(window, jQuery);