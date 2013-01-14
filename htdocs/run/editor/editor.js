/* Editor functionality */

var updateDisplayThread;  // used to delay the display changes while editing
var currentCartridge; // records which "cartridge" is being shown
var fullXML; // the final XML file to produce a tutorial
var user = getCookie("user");
var notSaved = false;
var currentTab = 1;

function askSave() {
   // alert("checking: "+notSaved);
   var keepChanges = confirm("This development is not saved. Save recovery file?");
   if (keepChanges) {
	   generateXML();
      // alert(fullXML);
      uploadTempXML();
      // alert("temp save is done");
   }
}

function warnNotSaved() {
   notSaved = true;
   window.onbeforeunload = askSave;
}

function noWarnSaved() {
   notSaved = false;
   window.onbeforeunload = "";
}

function viewXML() {
   monitorUsage('viewXML');
   document.images.XMLTab.src=XMLTabOn.src;
   document.images.formTab.src=formTabOff.src;
   // document.images.fileTab.src=fileTabOff.src;
   if (currentTab==1) generateXML(); // from form entry to editing XML file
   document.getElementById("elcidTutorial").src="show_xml.html";
   currentTab = 2;
}

function generateXML() {
   fullXML = "";
   fullXML += "<elcid>\n\n";
   fullXML += genSource();
   fullXML += "<iteration>\n\n";
   for (var i=0; i<steps.length; i++) {
      fullXML += steps[i].generate();
   }
   fullXML += "</iteration>\n\n";
   fullXML += "</elcid>\n";
}

function formEditor() {
   monitorUsage('EditXML');
   document.images.XMLTab.src=XMLTabOff.src;
   document.images.formTab.src=formTabOn.src;
   document.getElementById("elcidTutorial").src="xmlsource.html";
   if (currentTab==2) xmlDoc.setXML(fullXML); // from editing XML file to form entry
   setTimeout("showSourceXml(); updateDisplay();",300); // should do only if XML is well-formed.
   currentTab = 1;
}

function fileManager() {
   monitorUsage('FileManager');
   
   var winloc="file_manager.php";
   var winprop = 'height=700,width=750,location=no,scrollbars=yes,menu=no,toolbar=no,status=no,resizable=yes';

   var w = window.open(winloc, 'file', winprop);
   w.focus();

}

function delayUpdateDisplay() {
   updateDisplayThread=setTimeout('updateDisplay();',2000);
}

function updateDisplay() {
   currentCartridge = -1;
   var tempCurrentStep=current_step;
   displaying = false;
   rewind();
   fast_to(tempCurrentStep);
   displaying = true;
   update();
}

function highlightCartridge(num) {
   if (currentCartridge!=num) {
      changeCartridge(num);
      if (num==0) elcidTutorial.document.source.text.focus();
      else {
         elcidTutorial.document.forms[num].commentButton.focus();
         elcidTutorial.document.forms[num].step.focus();
      }
   }
}

function clickOnCartridge(num) {
   if (currentCartridge!=num) {
      changeCartridge(num);
      getTo(num);
   }
}

function changeCartridge(num) {
   //alert("Changing to "+num);
   setCartridgeColour(currentCartridge,"white");
   currentCartridge=num;
   setCartridgeColour(num,"#ccffff");
}

function setCartridgeColour(num,colour) {
  //alert ("Colouring step: "+ num);
	if (num>0)
	   elcidTutorial.document.getElementById("editStep"+(num-1)).style.background=colour;
	else
	   elcidTutorial.document.source.text.style.backgroundColor=colour;
}

function updateNewStep(newType) {
   warnNotSaved();
   var num = current_step-1;
   var step;

   if (newType=="move") {
      step = new step_move();
      step.col = area.x;
      step.line = area.y;
   }
   else if (newType=="insert") step = new step_insert();
   else if (newType=="select") step = new step_select();
   else if (newType=="delete") step = new step_delete();
   else if (newType=="cut") step = new step_cut();
   else if (newType=="copy") step = new step_copy();
   else if (newType=="paste") step = new step_paste();
   else step = new step_doh(); // in case

   step.comment = steps[num].comment
   steps[num] = step
   elcidTutorial.document.getElementById("step"+num).innerHTML = step.show()
   delayUpdateDisplay()
}

function deleteStep() {
   warnNotSaved();
   if (current_step>0) {
      var num = current_step-1;
      steps.splice(num,1);
	  current_step--;
      document.getElementById("pb").innerHTML = progressBar();
      updateDisplay();
      showHistory();
      setCartridgeColour(current_step,"#ccffff");
   }
}

function insertStep() {
   warnNotSaved();
   steps.splice(current_step,0,new step_move());
   document.getElementById("pb").innerHTML = progressBar();
   showHistory();
   highlightCartridge(current_step++);
   updateDisplay();
   //alert ("step inserted: "+ current_step);
}

/*
Complement functions for the step objects:
   Each 'step' is given a corresponding methods.
   See visualiser for the other step methods and
   how javaScript is used for polymorphism.

- update: change object according to user entry
- gen(erate): make the XML for a given object
- show: make the "cartridge" so the object can be edited

It was fun implementing methods in a pseudo-polymorphism style with the player,
but by now I do wish javascript was object-oriented for real.

*/

function updateSource() {
   warnNotSaved();
   source = elcidTutorial.document.forms.source.text.value.split('\n');
   delayUpdateDisplay();
}

function genSource() {
   var s = "";
   s += "<source>";
   s += myXMLEncode(elcidTutorial.document.forms.source.text.value);
   s += "</source>\n\n";
   return s;
}

function updateMove() {
   warnNotSaved();
   var Num=current_step-1;
   var step=steps[Num];
   var form=elcidTutorial.document.forms[Num+1];
   step.line=+form.line.value;
   step.col=+form.col.value;
   delayUpdateDisplay();
}

function genMove(step) {
   var s = "";
   s += "<move>\n";
   s += "   <linenumber>"+myXMLEncode(step.line)+"</linenumber>\n";
   s += "   <colnumber>"+myXMLEncode(step.col)+"</colnumber>\n";
   s += genComment(step.comment);
   s += "</move>\n\n";
   return s;
}

function showMove(step) {
   var display = '';
   display += 'Line: <input name="line" onChange="parent.updateMove()" type="text" value="'+step.line+'"><br>\n';
   display += 'Column: <input name="col" onChange="parent.updateMove()" type="text" value="'+step.col+'">\n';
   return display;
}

function updateInsert() {
   warnNotSaved();
   var Num=current_step-1;
   var step=steps[Num];
   var form=elcidTutorial.document.forms[Num+1];
   if (form.linesInUse.checked) {
      form.lines.disabled = false;
      step.lines = form.lines.value.split('\n');
   } else {
      form.lines.disabled = true;
      step.lines.length = 0;
   }
   step.chars = form.chars.value;
   delayUpdateDisplay();
}

function genInsert(step) {
   var s = "";
   s += "<insert>\n";
   if (step.chars) s += "   <chars>"+myXMLEncode(step.chars)+"</chars>\n";
   if (step.lines)
      for (var i=0; i<step.lines.length; i++)
         if (step.lines[i]=="") s += "   <line />\n";
         else s += "   <line>"+myXMLEncode(step.lines[i])+"</line>\n";
   s += genComment(step.comment);
   s += "</insert>\n\n";
   return s;
}

function showInsert(step) {
   var display = '';
   var text = '';
   var numCols = 20;
   var numRows = 3;
   areaChecked='';
   areaDisabled=' disabled';
   if (step.lines.length>0) { 
      for (var i=0; i<step.lines.length; i++) {
         text += '\n'+HTMLEncode(step.lines[i]);
         if (step.lines[i].length > numCols) numCols=step.lines[i].length;
      }
      areaChecked=' checked';
      areaDisabled='';
      numRows = step.lines.length;
   }
   display += '<input type="checkbox" onChange="parent.updateInsert()" name="linesInUse"'+areaChecked+'> Lines: ';
   display += '<textarea name="lines" onChange="parent.updateInsert()" rows="'+numRows+'" cols="'+numCols+'" align="top"'+areaDisabled+'>'+text+'</textarea>';
   display += '<br>\n';
   display += 'Chars: <input name="chars" onChange="parent.updateInsert()" type="text" size="'+(step.chars.length+1)+'" value="'+HTMLEncode(step.chars)+'">\n';
   return display;
}

function updateSelect() {
   warnNotSaved();
   updateDisplayThread=null;
   var Num=current_step-1;
   var step=steps[Num];
   var form=elcidTutorial.document.forms[Num+1];
   step.lines=+form.lines.value;
   step.chars=+form.chars.value;
   delayUpdateDisplay();
}

function genSelect(step) {
   var s = "";
   s += "<select>\n";
   // some validation needed here - chars and lines are integers
   if (step.chars>0) s += "   <chars>"+step.chars+"</chars>\n";
   if (step.lines>0) s += "   <lines>"+step.lines+"</lines>\n";
   s += genComment(step.comment);
   s += "</select>\n\n";
   return s;
}

function showSelect(step) {
   var display = '';
   display += 'Lines: <input name="lines" onChange="parent.updateSelect()" type="text" value="'+step.lines+'"><br>\n';
   display += 'Characters: <input name="chars" onChange="parent.updateSelect()" type="text" value="'+step.chars+'">\n';
   return display;
}

function genDelete(step) {
   var s;
   if (step.comment) {
      s = "<delete>\n";
      s += genComment(step.comment);
      s += "</delete>\n\n";
   } else
      s = "<delete />\n\n";
   return s;
}

function showDelete(step) {
   var display = '';
   return display;
}

function genCopy(step) {
   var s = "";
   if (step.comment) {
      s += "<copy>\n";
      s += genComment(step.comment);
      s += "</copy>\n\n";
   } else
      s += "<copy />\n\n";
   return s;
}

function showCopy(step) {
   var display = '';
   return display;
}

function genPaste(step) {
   var s = "";
   if (step.comment) {
      s += "<paste>\n";
      s += genComment(step.comment);
      s += "</paste>\n\n";
   }
   else {
      s += "<paste />\n\n";
   }
   return s;
}

function showPaste(step) {
   var display = '';
   return display;
}

// D'oh! is a catch all case for parsing errors
function genDoh(step) {
   return "<doh />\n\n";
}

function showDoh(step) {
   var display = '';
   return display;
}

function genComment(c) {
   var s = '';
   if (c != null)
      if (c=="") s += "   <comment />\n";
      else s += "   <comment>"+myXMLEncode(c)+"</comment>\n";
   return s;
}

/*
   showSourceXML
   Displays the XML source of the example to edit
   in a convenient format.
   
   Note the use of step.show which eventually calls the appropriate show method above.
*/

function showSourceXml() {
   elcidTutorial.document.forms.source.text.value = source.join('\n');
   showHistory();
}

function showHistory() {
   var finalS = '';
   var s='';
   var step;

   for (var i=0; i<steps.length; i++) {
      step = steps[i];
      s='\n';
      s += '<div class="editStep" onClick="parent.clickOnCartridge('+(i+1)+')" id="editStep'+i+'" style="background-color:white">\n';
      s += '<form name="step" onSubmit="return false;">'+i+': '+showStepName(step.type)+'<div id="step'+i+'">'+step.show()+'</div>'+showStepComment(i)+'\n';
      s += '</form></div><br>\n';
      //alert(s);
      finalS += s;
   }
   elcidTutorial.document.getElementById("view").innerHTML = finalS;
}

/*
   showStepName
   Displays the Step's name in a drop down box
   to enable a new selection.
   
   The code generated contains some JavaScript.
   
*/

function showStepName(name) {
   var result='<select name="step" onChange="parent.updateNewStep(this.options[this.selectedIndex].value)">';
   var options=new Array('Unknown','Move','Delete','Insert','Select','Copy','Paste');
   var values=new Array('doh','move','delete','insert','select','copy','paste');
	 for (var i=0; i<options.length; i++) {
	    result += '<option value = "'+values[i]+'"';
	    result += ((options[i]==name)?' selected':'') +'>';
	    result += options[i]+'</option>';
	 }
	 result += '</select><br>\n';
	 return result;
}

/*
   showStepComment
   Show the comment for the step.
*/

function showStepComment(i) {

   var result='<br><div align="right">';
   
   var tempCheck, tempVisible;
   if (steps[i].comment == null) {
      tempCheck = '';
      tempVisible = 'hidden';
   }
   else {
      tempCheck = ' checked ';
      tempVisible = 'visible';
   }    

   result += 'Comment:<input type="checkbox" onChange="parent.setComment('+i+');" name="commentCheck"'+tempCheck+'>';
   result += '<input type="button" name="commentButton"  style= "visibility: '+tempVisible+'" value="Text..." onClick="parent.commentDialog('+i+')"></div>';
   result += '<textarea name = "commentText" style= "visibility: hidden" cols="1" rows="1"></textarea>';

   return result;

}

function setComment(i) {
   warnNotSaved();
   var formElt = elcidTutorial.document.forms[i+1];
   if (formElt.commentCheck.checked) {
      formElt.commentButton.style.visibility = 'visible';
      commentDialog(i);
   }
   else {
      commentTextOK(i);
      steps[i].comment = null;
      formElt.commentButton.style.visibility = 'hidden';
   }
}

function commentDialog(i) {

   var formElt = elcidTutorial.document.forms[i+1];
   
   if (formElt.commentButton.value == "Text...") {
   
      var comment;
      if (steps[i].comment) {
         comment = steps[i].comment;
         formElt.commentText.value = comment;
      } else {
         comment = formElt.commentText.value;
         steps[i].comment = comment;
      }

      formElt.commentButton.value = "OK";
      formElt.commentText.style.visibility = "visible";

      var c = 20;
      var commentLines = comment.split('\n');
      var r =  commentLines.length+1;
      for (var j=0; j<commentLines.length; j++)
         if (commentLines[j].length > c) c = commentLines[j].length;
      c++;
      formElt.commentText.cols = c;
      formElt.commentText.rows = r;
      formElt.commentText.focus();
   }
   else
      commentTextOK(i);
}

function commentTextOK(i) {
   var formElt = elcidTutorial.document.forms[i+1];
   var comment = formElt.commentText.value;
   formElt.commentButton.value = "Text...";
   formElt.commentText.style.visibility = "hidden";      
   formElt.commentText.cols = 1;
   formElt.commentText.rows = 1;
   steps[i].comment = allWhiteSpace(comment)?"":comment;
}

var uploadRequest;

function uploadXML() {
   if (currentTab==1) generateXML(); // from form entries to XML file
   var fileName = xmlDoc.url;
   if (!fileName) fileName = "";
   fileName = prompt("Filename: ", parseFileName(fileName));

   if (fileName) {
      var params = "filename=" + encodeURIComponent( fileName ) +
                   "&data=" + encodeURIComponent( fullXML );
      uploadRequest = POSTRequest(cgiURL+'upload.php', params, uploadResponse);
   }
}

function uploadTempXML() {
   var fileName = parseFileName(xmlDoc.url);
   // alert('Emergency save: '+fileName);
   if (fileName) {
      var params = "filename=" + encodeURIComponent(fileName ) +
                   "&data=" + encodeURIComponent( fullXML ) +
                   "&user=" + encodeURIComponent( user );
      uploadRequest = POSTRequest(cgiURL+'upload_temp.php', params, uploadResponse);
   }
}

function POSTRequest(url, parameters, responseProcess) {
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
      alert('Cannot create XMLHTTP instance'); // Kludge this should raise an error
   } else {
      request.onreadystatechange = responseProcess;
      request.open('POST', url, true);
      request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
      request.setRequestHeader("Content-length", parameters.length);
      request.setRequestHeader("Connection", "close");
      request.send(parameters);
   }
   return request;
}

function uploadResponse() {
   if (uploadRequest.readyState == 4) {
      result = uploadRequest.responseText;
      // alert("response:\n"+uploadRequest.readyState+"\nStatus: "+uploadRequest.status+"\n"+result);
      if (uploadRequest.status == 200) {
         noWarnSaved();
         var lines = result.split("\n");
         alert("File saved: \n"+result);
         // window.location = htURL+"run/editor/cid_editor.html?file="+lines[1];
      }
      else if (uploadRequest.status == 403) {
         alert("Log in has expired.\nLog in again to save the file.");
      }
      else if (uploadRequest.status == 500) {
         alert("File could not be saved due to a server error.");
      }
      else {
         alert('There was a problem saving the file.\n'+result);
      }
   }
}

// myXMLEncode
// an error in IE's XML parser causes white space to be treated as empty tags - 
// i.e. <a>  </a> is the same as <a/>
// This routine ensures that the XML is encoded in such a way it can be used.
function myXMLEncode(str) {
   if (allBlanks(str))
      str = replaceAll(str,' ','&#32;');
   else {
      str = replaceAll(str,'&','&amp;');
      str = replaceAll(str,'<','&lt;');
      str = replaceAll(str,'>','&gt;');
      str = replaceAll(str,'"','&quot;');
   }
   return str;
}

function allBlanks(str) {
   return (replaceAll(str,' ','')=='');
}

function allWhiteSpace(str) {
   var str2 = replaceAll(str,' ','');
   str2 = replaceAll(str2,'\t','');
   return (replaceAll(str2,'\n','')=='');
}

function processAll(arr,f) {
   var result= new Array();
   for (var i=0;i<a.length;i++) result[i] = f(arr[i]);
   return result;
}

function parseFileName(str) {
	var parts = str.split('/');
   name = parts[parts.length-1];
	return name.substring(0,name.length-4);	
}

