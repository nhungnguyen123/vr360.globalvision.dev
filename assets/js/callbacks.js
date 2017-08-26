(function(w, $){

    var vrCallbacks = {

        $this: this,

        fileUploading: function (event) {
            if (event.lengthComputable) {
                var percentComplete = Math.round(event.loaded * 100 / event.total, 2);
                vrProgress.update(percentComplete);
            } else {
                vrLog.append('<p>Unable to compute %%%%%%</p>');
            }
        },

        fileUploadCompleted: function (event) {
            vrLog.append(event.target.responseText);
            vrLog.append('Your panaroma will be processing. <br />The link of your panaroma will be mail to your email when done. <br /> You will be redirect in 3 seconds');
        }
    }

    w.vrCallbacks = vrCallbacks;
})(window, jQuery);