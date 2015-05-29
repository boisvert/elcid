<html>

<head>

<script language="javaScript">

function php_redirect() {
   var params = "code=" + encodeURI( opener.codeToRun() );
   var postdata = "<?php echo file_get_contents("php://input"); ?>";
   if (postdata.length>0) params += '&'+postdata;
   var getdata = location.search;
   if (getdata) params += '&'+getdata.slice(1);
   // alert(params);
   fiddleRequest = POSTRequest('http://phpfiddle.org/api/run/code/json', params, phpFiddleResponse); 
}

function POSTRequest(url, parameters, responseProcess) {

   // alert('Sending to:\n\t'+url+'\n\t'+parameters);
   
   var request = false;

   if (window.XMLHttpRequest) { // Mozilla, Safari, Opera
      request = new XMLHttpRequest();
      if (request.overrideMimeType) {
         // set type accordingly to anticipated content type
         // eg 'text/xml' or 'text/html'
         request.overrideMimeType('text/ascii');
      }
   }
   else if (window.ActiveXObject) { // older IE
      try {
         request = new ActiveXObject("Msxml2.XMLHTTP");
     } catch (e) {
        try {
           request = new ActiveXObject("Microsoft.XMLHTTP");
        } catch (e) {}
     }
   }
   
   if (!request) {
      alert('Cannot create XMLHTTP instance'); // Kludge this should raise an error
   } else {
      request.open('POST', url, true);
      request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
      request.setRequestHeader("Content-length", parameters.length);
      request.setRequestHeader("Connection", "close");
      request.send(parameters);
      request.onreadystatechange = responseProcess;
   }
   return request;
}

function phpFiddleResponse() {
   if (fiddleRequest.readyState == 4) {
      if (fiddleRequest.status == 200) {
         var data = JSON.parse(fiddleRequest.responseText);
         var html = (data.result)?data.result:"Execution error:"+data.error;
         var resDiv = document.getElementById('fiddle').contentWindow.document;
         resDiv.write(html);
         resDiv.close();
      }
      else {
         alert('PHP Fiddle cannot execute the code.\nHTTP Status: '+fiddleRequest.status+'\nResponse:\n'+fiddleRequest.responseText);
      }
   }
}

</script>

</head>

<body onload="php_redirect()">

<iframe id="fiddle" width="100%" height="95%">
</iframe>

</body>

</html>
