
function adminObj()
{
	var thisAlias = this;

	this.statusCode = {};
	this.statusCode[0] = {}; // in processing
		this.statusCode[0]['text'] = 'Processing';
		this.statusCode[0]['image'] = './../ajax-loader.gif';
		this.statusCode[0]['color'] = '';
	this.statusCode[1] = {}; // ready
		this.statusCode[1]['text'] = 'Ready';
		this.statusCode[1]['image'] = './../done.png';
		this.statusCode[1]['color'] = '';
	this.statusCode[2] = {}; // removed
		this.statusCode[2]['text'] = 'Removed';
		this.statusCode[2]['image'] = './../trash.png';
		this.statusCode[2]['color'] = 'trRed';
		
	this.getAllTmp = function ()
	{
		thisAlias.tblPanoList = $('#tblPanoList > tbody').html(); // only 1 row
		thisAlias.tblUserList = $('#tblUserList > tbody').html(); // only 1 row
		
		// clean after get tmp
			$('#tblPanoList').html('');
			$('#tblUserList').html('');
		// add titles for table;
	}
	this.setAllTmpVal = function (tmpStrIn, data)
	{
		tmpStr = tmpStrIn.replace(/{{init}}/, ''); // 'init' may have | not have, just for a copy text.
		for ( tmpVar in data )
		{
			regObj  = new RegExp("{{" + tmpVar + "}}", "g");
			tmpStr = tmpStr.replace(regObj, data[tmpVar]);
//			console.log(tmpVar);
		}
		return tmpStr;
	}

	this.getUserListHeader = function ()
	{
		// create table User header
		var userTblHeaderData = {
				userId: "ID",
				userFullName: "Full Name",
				userEmail: "Email",
				loginName: "UserName",
				actionTh : "Actions"
		};
		
		var userTblHeader = thisAlias.tblUserList.replace(/td\>/g, 'th>');
		    userTblHeader = userTblHeader.replace(/\<button/, '{{actionTh}}<button');
		    userTblHeader = userTblHeader.replace(/\"\>\{\{userFullName\}\}\<\/p\>/, ' pHidden">{{userFullName}}</p>{{userFullName}}');
		    userTblHeader = userTblHeader.replace(/\"\>\{\{userEmail\}\}\<\/p\>/, ' pHidden">{{userEmail}}</p>{{userEmail}}');
		    userTblHeader = userTblHeader.replace(/\"\>\{\{loginName\}\}\<\/p\>/, ' pHidden">{{loginName}}</p>{{loginName}}');
		    userTblHeader = userTblHeader.replace(/\<button/g, '<button style="display: none;"');
		    userTblHeader = thisAlias.setAllTmpVal(userTblHeader, userTblHeaderData);		    
		// end header
		
		return userTblHeader;
	}
	this.getPanoListHeader = function ()
	{
		// create table PanoList header
		var panoTblHeaderData = {
			id: "ID",
			stt_text: "Status",
			tour_des: "vTour Name",
			date: "Creation day",
			user_id_trans_to_name: "UserName"
		};
		
		var panoTblHeader = thisAlias.tblPanoList.replace(/td\>/g, 'th>');
		panoTblHeader = panoTblHeader.replace(/\<img\s/, '<img style="display: none;" ');
		panoTblHeader = panoTblHeader.replace(/\"\>\{\{tour\_des\}\}\<\/a\>/, '" style="display: none;">{{tour_des}}</a>{{tour_des}}');
		panoTblHeader = thisAlias.setAllTmpVal(panoTblHeader, panoTblHeaderData);		    
		// end header		
		return panoTblHeader;
	}
	this.getAllData = function ()
	{
		thisAlias.userData = {};
		
		loader.show();
		
		$.getJSON("admin.ajax.php/AllUser", function(data){
			var htmlOut = thisAlias.getUserListHeader();
			
			for ( var user in data )
			{
				htmlOut = htmlOut + thisAlias.setAllTmpVal(thisAlias.tblUserList, data[user]);
//				console.info(data[user]);
				thisAlias.userData[data[user]['userId']] = data[user];
			}
			$('#tblUserList').append(htmlOut);
			
			thisAlias.getAllPano();
		});
	}

	this.getAllPano = function ()
	{
		$.getJSON("admin.ajax.php/AllPano", function(pdata){
			var htmlOut = thisAlias.getPanoListHeader();
			for ( var pano in pdata )
			{

				pdata[pano]['user_id_trans_to_name'] = thisAlias.userData[pdata[pano]['user_id']]['loginName'];
				
				pdata[pano]['stt_text']       = thisAlias.statusCode[pdata[pano]['status']].text;
				pdata[pano]['sst_image']      = 'src="' + thisAlias.statusCode[pdata[pano]['status']].image +'"';
				pdata[pano]['stt_text_color'] = thisAlias.statusCode[pdata[pano]['status']].color;
				
				htmlOut = htmlOut + thisAlias.setAllTmpVal(thisAlias.tblPanoList, pdata[pano]);
			}
			$('#tblPanoList').append(htmlOut);
			
			loader.hide();
			
			thisAlias.panoData = pdata;
		});
	}

	this.getAllPanioByUser = function (userId)
	{
		loader.show();
		$.getJSON("admin.ajax.php/AllPanoByUser/" + userId, function(pdata){
			
			errorHander.raise(pdata);
			
			var htmlOut = thisAlias.getPanoListHeader();
			for ( var pano in pdata )
			{
				//console.info(pdata[pano]['user_id']);
				pdata[pano]['user_id_trans_to_name'] = thisAlias.userData[pdata[pano]['user_id']]['loginName'];
				
				pdata[pano]['stt_text']       = thisAlias.statusCode[pdata[pano]['status']].text;
				pdata[pano]['sst_image']      = 'src="' + thisAlias.statusCode[pdata[pano]['status']].image +'"';
				pdata[pano]['stt_text_color'] = thisAlias.statusCode[pdata[pano]['status']].color;
				
				htmlOut = htmlOut + thisAlias.setAllTmpVal(thisAlias.tblPanoList, pdata[pano]);
			}
			$('#tbluserPanoListDialog').html(htmlOut);
			$('#userPanoListDialog').dialog({
	              width: 950,
	              height: 600,
	              modal: true,
	              closeOnEscape: false,
	              position: "top",
	              buttons: 
	               {
		                Ok: function(){$( this ).dialog( "close" );}
	               }
	            });
			
			thisAlias.userPanoData = pdata;
			
			loader.hide();
		});
	}
	
	this.changeUserPass = function ( userId )
	{
		//userNewPass get from input dialog
		var newPass = '';
		while ( newPass == '' || newPass.length < 4 ) 
			{
				newPass=prompt("Enter new Password for " + thisAlias.userData[userId]['loginName']);
				if( newPass == null ) return false;
			}
		loader.show();
		sdata = btoa('{"userId": "'+userId+'", "newPass": "'+newPass+'"}');
		$.getJSON('admin.ajax.php/ChangeUserPass/'+sdata, function(data){
			errorHander.raise(data);
			loader.hide();
		});
	}
	
	this.changeUserEmail = function ( userId )
	{
		//userNewPass get from input dialog
		var newEmail = '';
		while ( newEmail == '' || !newEmail.match(/[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+/) ) 
		{
			newEmail=prompt("Enter new Email for " + thisAlias.userData[userId]['loginName']);
			if( newEmail == null ) return false;
		}
		loader.show();
		sdata = btoa('{"userId": "'+userId+'", "newEmail": "'+newEmail+'"}');
		$.getJSON('admin.ajax.php/ChangeUserEmail/'+sdata, function(data){
			errorHander.raise(data);
			location.reload();
		});
	}
	
	this.changeUserFullName = function ( userId ) // not finish
	{
		//userNewPass get from input dialog
		var newUserFullname = '';
		while ( newUserFullname == '' ) 
		{
			newUserFullname=prompt("Enter new Full name for " + thisAlias.userData[userId]['loginName']);
			if( newUserFullname == null ) return false;
		}
		loader.show();
		sdata = btoa('{"userId": "'+userId+'", "newUserFullname": "'+newUserFullname+'"}');
		$.getJSON('admin.ajax.php/ChangeUserFullName/'+sdata, function(data){
			errorHander.raise(data);
			location.reload();
		});
	}
	
	this.addNewUser = function ()
	{
		var newUser = {};
		newUser.Name     = $('#newUserName').val();
		newUser.Pass     = $('#newUserPass').val();
		newUser.Email    = $('#userUserEmail').val();
		newUser.FullName = $('#userUserFullName').val();
		
		for( dataFeild in newUser ) //check empty
		{
			if( newUser[dataFeild] == '' ) 
			{
				alert("New User " + dataFeild + " cant be empty.");
				return false;
			}
		}
		
		if( !newUser.Email.match(/[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+/) )
		{
			alert("Bad Email format.");
			return false;
		}
		
		dataString       = btoa(JSON.stringify(newUser));
		
		loader.show();
		$.getJSON('admin.ajax.php/addNewUser/'+dataString, function(data){
			errorHander.raise(data);
			
			if( data == '1' ) alert('User added succesfull');
			
			location.reload();
			
//			$('#newUserName').val('');
//			$('#newUserPass').val('');
//			$('#userUserEmail').val('');
//			$('#userUserFullName').val('');
//			loader.hide();
		});
	}
}

function loading()
{
	this.show = function ()
	{
		$('#loading').dialog({modal: true, closeOnEscape: false});
	}
	
	this.hide = function ()
	{
		$('#loading').dialog("close");
	}
}

var loader = new loading();

function errorObj ()
{
	var thisError = this;
	this.raise = function (data)
	{
		if(typeof data.error != 'undefined')
		{
			if(data.error == "401") thisError.e401();
		}
	}
	this.e401 = function (){location.reload();}
}
var errorHander = new errorObj();