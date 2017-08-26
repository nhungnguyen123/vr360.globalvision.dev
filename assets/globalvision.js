$(function () {
    $("#tabs").tabs();
});
function inf(data) {
    console.info(data);
}
function maxKeyInObj(Obj) {
    var narr = [];
    for (var i in Obj)
    {
        narr.push(i);
    }
    return Math.max.apply(null, narr);// narr;
}
Object.size = function (obj) {
    var size = 0, key;
    for (key in obj) {
        if (obj.hasOwnProperty(key))
            size++;
    }
    return size;
};

function makeItRED(fId)
{
    $('#' + fId).addClass('red-border');
}

function chGUIObj(manager)
{
    this.tmp = {};
    var thisAlias = this;
    this.imgId = 0;
    this.panoList = {};
    this.manager = manager;
    this.tmp.panoUploadWrap = $('#divMain').html();
    $('#divMain').html('');
    $('#addButton').click(function () {
        thisAlias.add();
    });
    $('#submitButton').click(function () {
        thisAlias.manager.app.formUpload.sendData();
    });

    this.add = function ()
    {
        thisAlias.imgId++;
        var currentPanoWrap = thisAlias.tmp.panoUploadWrap.replace(/{{imgId}}/g, thisAlias.imgId);
        currentPanoWrap = currentPanoWrap.replace(/{{manager\.chGUI}}/g, thisAlias.manager.getAppName('chGUI'));
        $('#divMain').append(currentPanoWrap);
        thisAlias.panoList[thisAlias.imgId] = {};
    }
    this.rm = function (imgId)
    {
        delete thisAlias.panoList[imgId];
        $('#fieldset' + imgId).remove();
        if (Object.size(thisAlias.panoList) === 0) {
            thisAlias.add();
        }
    }
}

function formUpload(manager)
{
    this.formData = new FormData();
    this.xhr = new XMLHttpRequest();
    this.manager = manager;
    var thisAlias = this;

    this.dataCollect = function () {

        $('input').change(function (e) {
            $('#' + e.target.id).removeClass('red-border');
        });

        var m = thisAlias.manager;
        var f = thisAlias.formData;
        var panoList = m.app.chGUI.panoList;

        var panoUploadCountrer = 0;
        var thisRegex = new RegExp(/[^Ã©Ã¨Ã Ã¡Ã‰ÃˆÃ€ÃÃªÃŠÃ§Ã‡a-zA-z0-9\s\-\.\,]+/);
        var imgListJson = '';

        for (var i in panoList)
            //loop and collect data
        {
            inf('collecting data from: ' + i);

            if (typeof document.getElementById('img' + i + '_file').files[0] != 'undefined')
            {
                if (/[(^jpg$)(^png$)(^JPG$)(^PNG$)]/i.test(document.getElementById('img' + i + '_file').files[0].name.split('.').pop()))
                    f.append('img' + i + '_file', document.getElementById('img' + i + '_file').files[0]);
                else {
                    inf('skip ' + i);
                    alert("file: #" + i + " is accepted only *.jpg or *.png files. Please fix it before continue.");
                    return 0;
                }
            } else
            {
                inf('skip ' + i);
                alert("file: #" + i + " is missing or broken. please fix it before continue.");
                return 0;
            }

            if (!thisRegex.test(document.getElementById('img' + i + '_des').value) && document.getElementById('img' + i + '_des').value !== '')
            {
                f.append('img' + i + '_des', document.getElementById('img' + i + '_des').value);
            } else
            {
                inf('skip ' + i);
                alert('Accept only Ã©Ã¨Ã Ã¡Ã‰ÃˆÃ€Ãa-zA-z0-9,. space and "-" in title of Panaroma#' + i);
                makeItRED('img' + i + '_des');
                return 0;
            }

            if (!thisRegex.test(document.getElementById('img' + i + '_des_sub').value) && document.getElementById('img' + i + '_des_sub').value !== '')
            {
                f.append('img' + i + '_des_sub', document.getElementById('img' + i + '_des_sub').value);
            } else
            {
                inf('skip ' + i);
                alert('Accept only Ã©Ã¨Ã Ã¡Ã‰ÃˆÃ€Ãa-zA-z0-9,. space and "-" in sub title of Panaroma#' + i);
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
        if (!thisRegex.test($('#tour_des').val()) && $('#tour_des').val() !== '')
        {
            f.append('tour_des', $('#tour_des').val());
        } else
        {
            inf('skip tour name');
            alert('Accept only Ã©Ã¨Ã Ã‰ÃˆÃ€a-zA-z0-9,. space and "-" in title vTour');
            makeItRED('tour_des');
            return 0;
        }
        var regex = /[!@#$%^&*`~,.<>;':"\/\[\]\|{}()=_+]/;
        if (!regex.test($('#tour_url').val()) && $('#tour_url').val() !== '')
        {
            var friendlyUrl = $('#tour_url').val();
            friendlyUrl = friendlyUrl.replace(/  +/g, '-');
            friendlyUrl = friendlyUrl.replace(" ", "-");
            friendlyUrl = friendlyUrl.split(' ').join('-');
            f.append('tour_url', friendlyUrl);
        } else
        {
            alert("Please don't use special characters on url friendly!");
            return false;
        }


        f.append('jsonData', imgListJson);
        f.append('panoUploadCountrer', panoUploadCountrer);
//         f.append('defaultScene', thisAlias.manager.app.editor.editor_data.jsonData.defaultScene);
    }
    this.sendData = function ()
    {
        $('#uploadWrap').show();
        $('#uploadPrr').html('Collecting data ..');

        thisAlias.xhr.upload.addEventListener("progress", thisAlias.progressUpdate, true);
        thisAlias.xhr.addEventListener("load", thisAlias.uploadComplete, false);

        window.setTimeout(function () {
            if (thisAlias.dataCollect() === 0) {
                $('#uploadWrap').hide();
                return 0;
            }
            thisAlias.xhr.open("POST", "./uploadHander.php");
            thisAlias.xhr.send(thisAlias.formData);
        }, 700);
    }
    this.progressUpdate = function (event)
    {
        if (event.lengthComputable) {
            var percentComplete = Math.round(event.loaded * 100 / event.total, 2);
            $('#uploadPrr').html('Uploading ... [' + percentComplete + '% completed]');
        } else {
            console.error('unable to compute %%%%%%');
        }
    }
    thisAlias.uploadComplete = function (event)
    {
        inf(event.target.responseText);

        $('#subUploadWrap').html('Your panaroma will be processing. <br />The link of your panaroma will be mail to your email when done. <br /> You will be redirect in 3 seconds');


    }
}
function vtourListObj(manager)
{
    this.manager = manager;
    var thisAlias = this;

    this.rowTmp = $('#vtourList').html();
    $('#vtourList').html('');
    this.embedTmp = '<iframe width="800px" height="400px" src="http://vr360.globalvision.ch/{{alias}}" ></iframe>';//atob($('#embedDialogText').text());
    $('#embedDialogText').text('');
    $('#embedDialog').hide();
    this.timeHander = {};

    //we make table header so remove unesscery tag.
    //var cTmp = thisAlias.rowTmp;
    var cTmp = thisAlias.rowTmp.replace("", "");
    cTmp = cTmp.replace("tr id", 'tr class="tblHeaderRow" id');
    cTmp = cTmp.replace("vtourData_{{UID}}", "_a_");
    cTmp = cTmp.replace("href=", "da=");
    cTmp = cTmp.replace("<a", "<p");
    cTmp = cTmp.replace("</a", "</p");
    cTmp = cTmp.replace(/{{UID}}/g, 'vTour UID');
    cTmp = cTmp.replace(/{{tour_des}}/g, 'vTour Name');
    cTmp = cTmp.replace(/{{url}}/g, 'Friendly URL');
    cTmp = cTmp.replace(/{{date}}/g, 'Creation day');
    cTmp = cTmp.replace(/{{status}}/g, 'vTour status');
    cTmp = cTmp.replace(/\<button/g, '<button style="display: none;"');
    cTmp = cTmp.replace('"getEmbed">', '"getEmbed"> Action');
    //inf(cTmp);
    $('#vtourList').append(cTmp);

    this.getVtourList = function (callback)
    {
        $.getJSON('./tourList.ajax.php', function (data) {
            thisAlias.vTourList = data;
            callback();
        });
    }

    this.renderVtourList = function ()
    {
//      console.log(thisAlias.vTourList);
        // inf(thisAlias.vTourList);
        for (var i in thisAlias.vTourList)
        {
            if (thisAlias.vTourList[i].alias) {
                var url = thisAlias.vTourList[i].alias;
            } else {
                var url = "";
            }
            var cTmp = thisAlias.rowTmp.replace(/{{UID}}/g, thisAlias.vTourList[i].u_id);
            cTmp = cTmp.replace(/{{tour_des}}/g, thisAlias.vTourList[i].tour_des);
            cTmp = cTmp.replace(/{{url}}/g, "<div id='url" + thisAlias.vTourList[i].id + "'><div  style='float:left; width:90%'>" + url + "</div><div style='float:left; width:10%'><a href='#' onclick='changeUrl(" + thisAlias.vTourList[i].id + ")' ><img src='edit.png' width='24px' height='24px'/></a></div></div>");
            cTmp = cTmp.replace(/{{date}}/g, thisAlias.vTourList[i].date);
            cTmp = cTmp.replace(/{{status}}/g, thisAlias.getVtourStatusCode(thisAlias.vTourList[i]['status'], thisAlias.vTourList[i].u_id));
            cTmp = cTmp.replace(/{{manager\.vtourList}}/g, thisAlias.manager.spaceName + '.app.vtourList');
            cTmp = cTmp.replace(/{{manager\.editor}}/g, thisAlias.manager.getAppName('editor'));
            //inf(cTmp);
            $('#vtourList').append(cTmp);
        }

        if (g_t)
        {
            var hash = window.location.hash.substring(1);
            thisAlias.manager.app.editor.edit("<?php echo (isset($_GET['t']) ? $_GET['t'] : 'nothing'); ?>");
            history.pushState('', document.title, '_index.php');
        }
    }
    this.getEmbed = function (UID)
    {
        var alias = thisAlias.vTourList[UID].alias;
        var cTmp = thisAlias.embedTmp.replace(/{{alias}}/g, alias);
        $('#embedDialogText').text(cTmp);
        $('#embedDialog').dialog({
            width: 600,
            height: 270,
            modal: true,
            closeOnEscape: false,
            buttons:
                {
                    Ok: function ()
                    {
                        $(this).dialog("close");
                    }
                }
        });
    }
    this.remove = function (UID)
    {
        if (!confirm("Are you sure want to remove: \"" + thisAlias.vTourList[UID].tour_des + "\""))
            return 0;
        $.getJSON('./deactive.ajax.php?UIDx=' + UID, function (data)
        {
            if (data.code == '401')
            {
                window.location = "/_index.php";
            } else
            {
                $('tr#vtourData_' + UID).hide();
            }
        });

    }
    this.getVtourStatusCode = function (status, UID)
    {
        if (status == 0)
        {
            thisAlias.watchVtourStatusCode(UID);
            return '<p id="pid_' + UID + '"><img class="ssf" src="./ajax-loader.gif" /> Processing</p>';
        } else {
            return '<p><img class="ssf2" src="./done.png" /> Ready</p>';
        }
    }
    this.watchVtourStatusCode = function (UID)
    {
        $.getJSON('./tourListRowStatus.ajax.php?UID=' + UID, function (data) {
            //inf(data); //window.setTimeout(thisAlias.watchVtourStatusCode(UID), 3000);
            if (data[UID]['status'] == 0) {
                thisAlias.timeHander[UID] = window.setTimeout(function () {
                    thisAlias.watchVtourStatusCode(UID)
                }, 10000);
            } else {
                $('#pid_' + UID).html('<img class="ssf2" src="./done.png" /> Ready');
                thisAlias.vTourList[UID].status = 1;
            }
        });
    }
    this.preview = function (UID)
    {
        if (thisAlias.vTourList[UID].status == 1)
            window.open('http://vr360.globalvision.ch/' + thisAlias.vTourList[UID].alias, '_blank');
        else
            alert('Please wait, tour review for "' + thisAlias.vTourList[UID].tour_des + '" is not ready yet.');
    }
}
function editor_GUI(editor)
{
    var thisAlias = this;
    this.data = editor.editor_data;
    this.editor = editor;
    this.tmp = {};
    this.tmp.editItem = $('#editDialog').html();
    $('#editDialog').html('');
    this.tmp.panoNew = $('#editorNewPana').html();
    $('#editorNewPana').html('');
    this.tmp.hotifr = $('#ifrDialog').html();
    $('#ifrDialog').html('');

    this.addPanaroma = function (panoDataID)
    {
        var tmp = this.tmp.editItem.replace(/{{imgId}}/g, panoDataID);
        tmp = tmp.replace(/{{img_val}}/g, thisAlias.data.panoList[panoDataID].des);
        tmp = tmp.replace(/{{img_val_sub}}/g, thisAlias.data.panoList[panoDataID].des_sub);
        tmp = tmp.replace(/{{src}}/g, 'src');
        tmp = tmp.replace(/{{manager\.editor}}/g, thisAlias.editor.manager.getAppName('editor'));

        return tmp;
    }
    this.showDialog = function ()
    {
        //fecht tmp
        // inf(thisAlias.data.panoList);
        for (var i in thisAlias.data.panoList)
        {
            var tmp = thisAlias.addPanaroma(i);
            //inf(tmp);

            tmp = tmp.replace(/{{UID}}/g, thisAlias.data.jsonData.uId);

            $('#editDialog').append(tmp);
            //inf(tmp);
        }
        $('#editDialog').prepend('<fieldset><legend>Option: </legend><input type="checkbox" id="tour_e_rotation" size="80"/><span>Check for auto rotation.</span><br><input type="checkbox" id="tour_e_social" size="80"/><span>Check for show media social button.</span></fieldset><br>');
        $('#editDialog').prepend('<input type="text" id="tour_e_des" size="50" placeholder="Name of this tour"/><br>');

        $('#editDialog').append('<button id="newPanoxyz" onclick="manager.app.editor.addNewPano()">Add 1 more panaroma</button>');
        $('#editDialog').append('<button onclick="manager.app.editor.editHotSpot(\'' + thisAlias.data.jsonData.uId + '\', \'1\')">Edit hotspots / Default view</button>');
        $('#tour_e_des').val(thisAlias.data.jsonData.tourDes);
//     console.log(thisAlias.data.jsonData.tourRotation);
        if(thisAlias.data.jsonData.tourRotation == "true"){
            $('#tour_e_rotation').prop('checked', true);
        }else{
            $('#tour_e_rotation').prop('checked', false);
        }
        if(thisAlias.data.jsonData.startSocial != "" && thisAlias.data.jsonData.endSocial != ""){
            $('#tour_e_social').prop('checked', false);
        }else{
            $('#tour_e_social').prop('checked', true);
        }
        //build ...

        $('#editDialog').dialog({
            width: 800,
            height: 500,
            modal: true,
            closeOnEscape: false,
            buttons: {
                Save: function () {
                    thisAlias.editor.save();
                },
                Cancel: function () {
                    $(this).dialog('destroy');
                    $('#editDialog').html('');
                }
            }
        });
    }
}
function editor_data()
{
    var thisAlias = this;

    this.getVtourData = function (vTourUID, callback)
    {
        $.getJSON('http://vr360.globalvision.ch/_/' + vTourUID + '/data.json?' + Math.random(), function (data) {
            thisAlias.jsonData = data;
            thisAlias.panoList = data.panoList;
            if (typeof callback == 'function')
                callback();
        });
    }
    this.collectData = function ()
    {
        $('input').change(function (e) {
            $('#' + e.target.id).removeClass('red-border');
        });

        var data = thisAlias.jsonData;
        var thisRegex = new RegExp(/[^Ã©Ã¨Ã Ã¡Ã‰ÃˆÃ€ÃÃªÃŠÃ§Ã‡a-zA-z0-9\s\-\.\,]+/);

        if (!thisRegex.test(document.getElementById('tour_e_des').value) && document.getElementById('tour_e_des').value != '')
        {
            data.tourDes = document.getElementById('tour_e_des').value;
        } else {
            alert('Accept only Ã©Ã¨Ã Ã¡Ã‰ÃˆÃ€Ã a-zA-z0-9, space and "-" in title vTour');
            makeItRED('tour_e_des');
            return false;
        }

        if ($('#tour_e_rotation').prop("checked") == true) {
            data.tourRotation = 'true';
        } else {
            data.tourRotation = 'false';
        }
        if ($('#tour_e_social').prop("checked") == true) {
            data.startSocial = "";
            data.endSocial = "";
        } else {
            data.startSocial = '<!--';
            data.endSocial = '-->';
        }
        for (var i in data.panoList)
        {
            if (!thisRegex.test(document.getElementById('img_e_' + i + '_des').value) && document.getElementById('img_e_' + i + '_des').value != '')
            {
                data.panoList[i].des = document.getElementById('img_e_' + i + '_des').value;
            } else {
                alert('Accept only Ã©Ã¨Ã Ã¡Ã‰ÃˆÃ€Ã a-zA-z0-9,. space and "-" in title of Panaroma#' + i);
                makeItRED('img_e_' + i + '_des');
                return false;
            }

            if (!thisRegex.test(document.getElementById('img_e_' + i + '_des_sub').value) && document.getElementById('img_e_' + i + '_des_sub').value != '')
            {
                data.panoList[i].des_sub = document.getElementById('img_e_' + i + '_des_sub').value;
            } else {
                alert('Accept only Ã©Ã¨Ã Ã¡Ã‰ÃˆÃ€Ã a-zA-z0-9,. space and "-" in title of Panaroma#' + i);
                makeItRED('img_e_' + i + '_des_sub');
                return false;
            }
        }

        return true;
    }
}
function editor(manager)
{
    var thisAlias = this;
    this.manager = manager;
    this.editor_data = new editor_data();
    this.editor_GUI = new editor_GUI(thisAlias);

    this.edit = function (UID)
    {
        if (thisAlias.manager.app.vtourList.vTourList[UID].status == 1)
            thisAlias.editor_data.getVtourData(UID, thisAlias.editor_GUI.showDialog);
        else
            alert('Please wait, tour edit for "' + thisAlias.manager.app.vtourList.vTourList[UID].tour_des + '" is not ready yet.');
    }
    this.save = function ()
    {
        $('input').change(function (e) {
            $('#' + e.target.id).removeClass('red-border');
        });

        $('#savingDialog').html('Collecting data ...');
        $('#savingDialog').dialog({modal: true, closeOnEscape: false, width: 350});

        if (!thisAlias.editor_data.collectData()) {
            $('#savingDialog').dialog("destroy");
            $('#savingDialog').html();
            return 0;
        }
        ;

        var formData = new FormData();
        var xhr = new XMLHttpRequest();
        var thisRegex = new RegExp(/[^Ã©Ã¨Ã Ã¡Ã‰ÃˆÃ€ÃÃªÃŠÃ§Ã‡a-zA-z0-9\s\-\.\,]+/);
        window.setTimeout(function () {

            $('#savingDialog').html('Saving data ...');
            // $('#savingDialog').dialog("refresh");

            /*................. if new pano .................*/
            if (thisAlias.editor_data.newPano == true)
            {
                formData.append('newPano', 'true');

                if (typeof document.getElementById('imgNewPano_file').files[0] != 'undefined')
                {
                    if (/[(^jpg$)(^png$)(^JPG$)(^PNG$)]/.test(document.getElementById('imgNewPano_file').files[0].name.split('.').pop()))
                        formData.append('imgNewPano_file', document.getElementById('imgNewPano_file').files[0]);
                    else {
                        inf('skip imgNewPano_file');
                        alert("new Panoram is accepted only *.jpg or *.png files. Please fix it before continue.");
                        $('#savingDialog').dialog("destroy");
                        return 0;
                    }
                } else
                {
                    alert("New pano file is is accepted only *.jpg or *.png files. Please fix it before continue.");
                    $('#savingDialog').dialog("destroy");
                    return 0;
                }

                if (!thisRegex.test(document.getElementById('imgNewPano_des').value) && document.getElementById('imgNewPano_des').value !== '')
                {
                    formData.append('imgNewPano_des', document.getElementById('imgNewPano_des').value);
                } else
                {
                    alert('Accept only Ã©Ã¨Ã Ã¡Ã‰ÃˆÃ€Ã a-zA-z0-9,. space and "-" in title of new Panorama');
                    $('#savingDialog').dialog("destroy");
                    makeItRED('imgNewPano_des');
                    return 0;
                }

                if (!thisRegex.test(document.getElementById('imgNewPano_des_sub').value) && document.getElementById('imgNewPano_des_sub').value !== '')
                {
                    formData.append('imgNewPano_des_sub', document.getElementById('imgNewPano_des_sub').value);
                } else
                {
                    alert('Accept only Ã©Ã¨Ã Ã¡Ã‰ÃˆÃ€Ã a-zA-z0-9,. space and "-" in sub title of new Panorama');
                    $('#savingDialog').dialog("destroy");
                    makeItRED('imgNewPano_des_sub');
                    return 0;
                }

                formData.append('newFName', maxKeyInObj(thisAlias.manager.app.editor.editor_data.jsonData.panoList) + 1);
            }

            /////*................. /if new pano .................*/

            xhr.addEventListener("load", function () {
                $('#savingDialog').html('Done.');
                // $('#savingDialog').dialog("refresh");

                window.setTimeout(function () {
                    window.location = "./_index.php"
                });

            }, false);

            xhr.upload.addEventListener("progress", function (event) {
                if (event.lengthComputable) {
                    var percentComplete = Math.round(event.loaded * 100 / event.total, 2);
                    if (percentComplete > 99)
                    {
                        $('#savingDialog').html('Krpano in process <img class="ssf" src="./ajax-loader.gif" />');
                    } else {
                        $('#savingDialog').html('Uploading ... [' + percentComplete + '% completed]');
                    }
                } else {
                    console.error('unable to compute %%%%%%');
                }
            }, true);

            delete thisAlias.editor_data.panoList;
            formData.append('jsonData', JSON.stringify(thisAlias.editor_data));

            xhr.open("POST", "./save.editor.php");
            xhr.send(formData);

        }, 500);
    }
    this.remove = function (panoId)
    {
        //inf(Object.size(thisAlias.editor_data.jsonData.panoList));
        if (Object.size(thisAlias.editor_data.jsonData.panoList) > 1)
        {
            delete thisAlias.editor_data.jsonData.panoList[panoId];
            $('#fieldset_e_' + panoId).remove();
        } else
        {
            alert('vTour must have at least 1 panorama.');
        }

    }
    this.makeDefaultScene = function (panoId)
    {
        thisAlias.editor_data.jsonData.defaultScene = panoId;
    }
    this.addNewPano = function ()
    {
        $('#newPanoxyz').hide();
        $('#editDialog').append(thisAlias.editor_GUI.tmp.panoNew);
        thisAlias.editor_data.newPano = true;
    }
    this.editHotSpot = function (xUID, panoId)
    {
        var tp = thisAlias.editor_GUI.tmp.hotifr.replace(/{{UID}}/g, xUID);
        tp = tp.replace(/{{pId}}/g, panoId);
        tp = tp.replace(/{{src}}/g, 'src');

        $('#ifrDialog').html(tp);
        $('#ifrDialog').dialog({
            width: 700,
            height: 600,
            modal: true,
            closeOnEscape: false,
            buttons: {
                save: function () {
                    if (!document.getElementById('if-ddhsy').contentWindow.rdy4save())
                    {
                        alert('Please finish to add hotspot before saving or click cancel');
                        return false;
                    }
                    var HotspotData = document.getElementById('if-ddhsy').contentWindow.superHotspot.getData().hotspotList;
                    var panoList = thisAlias.editor_data.jsonData.panoList;
                    for (var sceneName in HotspotData)
                    {
                        var currentWorkingScene = panoList[sceneName.replace('scene_', '')];
                        currentWorkingScene.hotspotList = HotspotData[sceneName];
                    }

                    var defaultViewData = document.getElementById('if-ddhsy').contentWindow.defaultViewList;
                    var panoList = thisAlias.editor_data.jsonData.panoList;
                    for (var defaultView in defaultViewData)
                    {
                        var currentWorkingScene = panoList[defaultView.replace('scene_', '')];
                        currentWorkingScene.defaultView = defaultViewData[defaultView];
                    }

                    $('#ifrDialog').html('');
                    $(this).dialog("destroy");
                    thisAlias.save();
                },
                cancel: function () {
                    $('#ifrDialog').html('');
                    $(this).dialog("destroy");
                }
            }
        });
    }
}
function managerObj()
{
    this.config = {};
    this.app = {};
    var thisAlias = this;
    this.spaceName = "manager";
    this.getAppName = function (appName) {
        return thisAlias.spaceName + ".app." + appName;
    }

    this.app.chGUI = new chGUIObj(thisAlias);
    this.app.chGUI.add();
    this.app.formUpload = new formUpload(thisAlias);
    this.app.vtourList = new vtourListObj(thisAlias);
    this.app.editor = new editor(thisAlias);

    this.init = function ()
    {
        thisAlias.app.vtourList.getVtourList(thisAlias.app.vtourList.renderVtourList);
    }
}
function changeUrl(id) {
    // var thisAlias = this;

    $.getJSON('./detailTour.ajax.php?id=' + id, function (data) {
        document.getElementById("url" + id).innerHTML = "<div  style='float:left; width:90%'><input id='text" + id + "' value='" + data.alias + "' style='margin:0; padding:0; width:90%'/></div><div style='float:left; width:10%'><a href='#' onclick='changeUrl(" + id + ")' ><a href='#' onclick='saveAlias(" + id + ")'>save</a></a></div>";
    });


}
function saveAlias(id) {
    var friendlyUrl = document.getElementById("text" + id).value;
    if (friendlyUrl != "") {
        friendlyUrl = friendlyUrl.replace(/  +/g, '-');
        friendlyUrl = friendlyUrl.replace(" ", "-");
        var regex = /[!@#$%^&*`~,.<>;':"\/\[\]\|{}()=_+]/;
        if (regex.test(friendlyUrl)) {
            alert("Please don't use special characters on url friendly!");
            return false;
        } else {
            $.ajax({
                type: 'POST',
                url: './update.url.php',
                dataType: 'json',
                data: {'id': id, 'friendlyUrl': friendlyUrl},
                success: function(resp) {
                    console.log(resp);
                    if (resp.status == 1) {
                        document.getElementById("url" + id).innerHTML = "<div id='url" + id + "'><div  style='float:left; width:90%'>" + friendlyUrl + "</div><div style='float:left; width:10%'><a href='#' onclick='changeUrl(" + id + ")' ><img src='edit.png' width='24px' height='24px'/></a></div></div>";
                        window.location = "./_index.php";
                    } else if (resp.status == 2) {
                        alert("['"+friendlyUrl+"']" + " had already please check again.");
                    }
                }
            });
        }
    }
}