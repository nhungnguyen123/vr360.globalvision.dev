#!/bin/bash
#//maintainer nhan@globalvision.ch

Timeout=300 #5m ongoing limited
start_time=`date +%s`

PANAROMA_ARR_str="{{PANAROMA_ARR_str}}"
xUID="{{UID}}"
TOKEN="{{TOKEN}}"
TOUR_URL="{{TOUR_URL}}"
VTOUR_NAME="{{VTOUR_NAME}}"
USER_FULL_NAME="{{USER_FULL_NAME}}"
EMAIL_DEMO="{{EMAIL_DEMO}}"
KR_CONF_NOMA="./assets/krpano/krpanotools-1.16.8-win64-working-only/templates/vtour-normal.config"
#begin krpano processing

    ../assets/krpano/krpanotools-1.16.8-win64-working-only/kmakemultires -config=$KR_CONF_NOMA $PANAROMA_ARR_str

end_time=`date +%s`
echo _________________________________________________________________________________________________________________
echo kr execution time was `expr $end_time - $start_time` s.
echo _________________________________________________________________________________________________________________
############################################# rm old xml and repalce by created xml ##################################
    chmod -Rf a+rw ./_/$xUID/*
    rm ./_/$xUID/vtour/tour.xml
    mv ./_/$xUID/t.xml ./_/$xUID/vtour/tour.xml
############################################# rm old skin and repalce by  new skin  ##################################

############################################# rm old html and repalce by  new html  ##################################

#############################################  Mail rdy   ############################################################
#     echo -e <<EOF | mail -a="Content-type: text/html" -s "Panorama creation completed" $EMAIL_DEMO -bc nhan@globalvision.ch -- -F'globalvision<info@globalvision.ch>' -t
#     Hello,
#     Thanks for uploading your pano on our platform!
#     Your vTour: \"$VTOUR_NAME\" is ready.
#     Have a look here: http://vr360.globalvision.ch/preview.php?t=$xUID
#     Best Regards,
#
#     <b>global<font color="#3333ff">vision</font></b>
# EOF

    #run a php to update Database and send email
    #timeout 10 php change.status.php -k "ThisisSecreatTOKEN_Kkjsdk^&#^jhbdjnJHDASjajsdoKSDJkjwdasJKASJ@HSDjasdbncxvloas" -u $TOUR_URL -t $EMAIL_DEMO -v "$VTOUR_NAME" -n "$USER_FULL_NAME" > ./lastlog 2>&1
    
sleep 120

    curl http://vr360.globalvision.ch/change.status.php?u={{UID}} -u "glovi:N=,1TFy*uUSHtt&&&" > /home/gvch/subdomains/vr360.globalvision.ch/lastcurllog1
    #curl http://vr360.globalvision.ch/change.status.php?u={{UID}} -u "glovi:N=,1TFy*uUSHtt&&&" -o /home/gvch/subdomains/vr360.globalvision.ch/lastcurllog2
end_time=`date +%s`
echo _________________________________________________________________________________________________________________
echo total execution time was `expr $end_time - $start_time` s.
echo _________________________________________________________________________________________________________________
############################################# Clean zone  ############################################################
	sleep 5
    //chmod -Rf 644 ./_/$xUID/*
    chmod -Rf 777 ./_/$xUID
    #selft detroy
        #rm -Rf "$0"
        #rm ./_/$xUID/mail.cvs
