
var monitorRequest = false;
var fileUseID = '';

function monitor() {

   var url = cgiURL+'monitor_home.php';

   if (fileUseID=='')
      fileUseID = generateFileUseID();
		
   var params = 'command=doh&fileuseid='+fileUseID;
   monitorRequest = GETRequest(url, params);
}

function generateFileUseID() {
   var hexChars = "0123456789ABCDEF";
   var randNum = 0;
   var result = "";

   for (var i=0; i<27; i++) {
 	  randNum = Math.floor(Math.random()*16);
      result += hexChars.charAt(randNum);
   }
   return result;
}

function GETRequest(url, parameters) {

   // alert("requesting\n  " + url + "\n  " + parameters);
   var request = false;

   if (window.XMLHttpRequest) { // Mozilla, Safari, Opera

      request = new XMLHttpRequest();

      if (request.overrideMimeType) {
         // set type accordingly to anticipated content type
         // eg 'text/xml' or 'text/html'
         request.overrideMimeType('text/ascii');
      } 
   }
	else if (window.ActiveXObject) { // IE
      try {
         request = new ActiveXObject("Msxml2.XMLHTTP");
     } catch (e) {
        try {
           request = new ActiveXObject("Microsoft.XMLHTTP");
        } catch (e) {}
     }
   }
   if (!request) {
      alert('Cannot create XMLHTTP instance');
   }
   else {
      request.open('GET', url+'?'+parameters, true);
      request.setRequestHeader("Connection", "close");
      // request.onreadystatechange = alertContents;
      request.send(null);
   }
   return request;
}

function alertContents() {
   if (monitorRequest.readyState == 4) {
      if (monitorRequest.status == 200) {
         alert(monitorRequest.responseText);            
      } else {
         alert('There was a problem with the request.');
      }
   }
}
