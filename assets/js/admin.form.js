function formUpload(manager) {
    this.formData = new FormData();
    this.xhr = new XMLHttpRequest();
    this.manager = manager;
    var thisAlias = this;

    /**
     * Collecting data
     * @returns {*}
     */
    this.dataCollect = function () {

        // Reset danger
        $('input').change(function (e) {
            $('#' + e.target.id).removeClass('red-border');
        });

        var m = thisAlias.manager;
        var f = thisAlias.formData;
        var panoList = m.app.chGUI.panoList;

        var panoUploadCountrer = 0;
        var thisRegex = new RegExp(/[^éèàáÉÈÀÁêÊçÇa-zA-z0-9\s\-\.\,]+/);
        var imgListJson = '';

        for (var i in panoList)
            //loop and collect data
        {
            $('#subUploadWrap').append('<p>Collecting data from: ' + i + '</p>');

            if (typeof document.getElementById('img' + i + '_file').files[0] != 'undefined') {
                if (/[(^jpg$)(^png$)(^JPG$)(^PNG$)]/i.test(document.getElementById('img' + i + '_file').files[0].name.split('.').pop()))
                    f.append('img' + i + '_file', document.getElementById('img' + i + '_file').files[0]);
                else {
                    inf('skip ' + i);
                    alert("file: #" + i + " is accepted only *.jpg or *.png files. Please fix it before continue.");
                    return 0;
                }
            } else {
                inf('skip ' + i);
                $('#subUploadWrap').append(
                    '<p>' +
                    '<div class="alert alert-warning alert-dismissible fade in" role="alert">' +
                    '<strong>Missing fileds</strong> Pano file is missing. Please select it</div>' +
                    '</p>'
                );
                return 0;
            }

            if (!thisRegex.test(document.getElementById('img' + i + '_des').value) && document.getElementById('img' + i + '_des').value !== '') {
                f.append('img' + i + '_des', document.getElementById('img' + i + '_des').value);
            } else {
                inf('skip ' + i);
                alert('Accept only éèàáÉÈÀÁa-zA-z0-9,. space and "-" in title of Panaroma#' + i);
                makeItRED('img' + i + '_des');
                return 0;
            }

            if (!thisRegex.test(document.getElementById('img' + i + '_des_sub').value) && document.getElementById('img' + i + '_des_sub').value !== '') {
                f.append('img' + i + '_des_sub', document.getElementById('img' + i + '_des_sub').value);
            } else {
                inf('skip ' + i);
                alert('Accept only éèàáÉÈÀÁa-zA-z0-9,. space and "-" in sub title of Panaroma#' + i);
                makeItRED('img' + i + '_des_sub');
                return 0;
            }

            imgListJson = imgListJson + '"' + i + '",';
            panoUploadCountrer++;
        }
        imgListJson = imgListJson.substr(0, imgListJson.length - 1);
        imgListJson = '[' + imgListJson + ']';
        if ($('#tour_rotation').prop("checked") == true) {
            f.append('tour_rotation', 'true');
        } else {
            f.append('tour_rotation', 'false');
        }
        if ($('#tour_social').prop("checked") == true) {
            f.append('start_social', '');
            f.append('end_social', '');
        } else {
            f.append('start_social', '<!--');
            f.append('end_social', '-->');
        }

        // Validate tour title
        if (!thisRegex.test($('#tour_des').val()) && $('#tour_des').val() !== '') {
            f.append('tour_des', $('#tour_des').val());
        } else {
            inf('skip tour name');
            alert('Accept only éèàÉÈÀa-zA-z0-9,. space and "-" in title vTour');
            makeItRED('tour_des');
            return 0;
        }

        var regex = /[!@#$%^&*`~,.<>;':"\/\[\]\|{}()=_+]/;
        if (!regex.test($('#tour_url').val()) && $('#tour_url').val() !== '') {
            var friendlyUrl = $('#tour_url').val();
            friendlyUrl = friendlyUrl.replace(/  +/g, '-');
            friendlyUrl = friendlyUrl.replace(" ", "-");
            friendlyUrl = friendlyUrl.split(' ').join('-');
            f.append('tour_url', friendlyUrl);
        } else {
            alert("Please don't use special characters on url friendly!");
            return false;
        }


        f.append('jsonData', imgListJson);
        f.append('panoUploadCountrer', panoUploadCountrer);
//         f.append('defaultScene', thisAlias.manager.app.editor.editor_data.jsonData.defaultScene);
    }

    /**
     * File upload and submit
     */
    this.sendData = function () {
        vrLog.append('Collecting data ...');

        thisAlias.xhr.upload.addEventListener("progress", vrCallbacks.fileUploading, true);
        thisAlias.xhr.addEventListener("load", vrCallbacks.fileUploadCompleted, false);

        if (thisAlias.dataCollect() === 0) {
            return false;
        }
        else
        {
            thisAlias.xhr.open("POST", "./index.php");
            thisAlias.formData.append('task', 'createTour');
            thisAlias.xhr.send(thisAlias.formData);
        }
    }
}