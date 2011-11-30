/*

eL-CID  -  e-Learning by Communicating Iterative Development

Copyright (C) Anglia Polytechnic University, 2003-04 - Charles Boisvert 2004-09.


Licensing conditions
====================

This file is part of eL-CID.

eL-CID is free software; you can redistribute it and modify it
under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the license, or
(at your option) any later version.

eL-CID is distributed in the hope that it will be useful, but
WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
General Public License for details.

You should have received a copy of the GNU General Public Licence
along with eL-CID; if not, write to

    the Free Software Foundation, Inc.,
    59 Temple Place,
    Suite 330,
    Boston, MA 02111-1307 USA

or to Charles Boisvert: charles@boisvert.me.uk


Using with new iterative development tutorials
==============================================

    Linking eL-CID statically or dynamically with other modules is making a
    combined work based on eL-CID.  Thus, the terms and conditions of the GNU
    General Public License cover the whole combination.

    As a special exception, the copyright holders of eL-CID give you
    permission to link eL-CID with independent modules that communicate with
    eL-CID solely through the "XML Iterative Development Data API", regardless of
    the license terms of these independent modules, and to copy and distribute
    the resulting combined work under terms of your choice, provided that
    every copy of the combined work is accompanied by a complete copy of
    the source code of eL-CID (the version of eL-CID used to produce the
    combined work), being distributed under the terms of the GNU General
    Public License plus this exception.  An independent module is a module
    which is not derived from or based on eL-CID.

    Note that people who make modified versions of eL-CID are not obligated
    to grant this special exception for their modified versions; it is
    their choice whether to do so.  The GNU General Public License gives
    permission to release a modified version without this exception; this
    exception also makes it possible to release a modified version which
    carries forward this exception.

*/

var AnimationsFolder = 'samples/';
    // This folder is where XML data is loaded from by default

var monitorImg= cgiURL + 'monitor_usage.php';
    // The eL-CID icon is a link to my database
    // to find out how eL-CID is being used. Replace it if you hate the idea.

var fileUseID = '';
    // Used for monitoring

var qString = new queryString();
    // To hold the query string. See the queryString function for details
   
var xmlDoc = new XMLdocument();
    // The xmlDoc is how eL-CID stores the iterative development example

xmlDoc.onload = parse;
    // Call the parse function when it is loaded.

var steps = new Array();
    // Parse will store the iterative changes in an array of steps

var current_step=0;
    // current step

var temp_chars='';

var auto_on=false;
    // Whether the animation is running on its own
    // Changes when the user presses the play button

var browser=getBrowser();
    // for browser-dependent display details like font sizes 
	// other javascript compatibility problems are dealt with by testing functionality

var controls;
    // the control panel

var area;
    // the code area

var displaying = true;
    // Whether the display should be refreshed with every step played
    // False when the user presses rewind/fast forward

var clipboard = new textpad();
    // Test clipboard to simulate copy/paste

var source = new Array();
    // Initial source code of example - an array of string (one per line)

var postit;
    // Yellow comment box

var codeToRun='';
var folderToRun='';
    // codeToRun and folderToRun are filled in when the user presses the run button

	
/***********************

Object initialisations:
   queryString
   XMLdocument
   parse
   make_step
   step_move
   step_select
   step_copy
   step_paste
   step_insert
   step_delete
   step_doh
   extend_area
   textpad
   extendPost

***********************/

// Query string parsing

function queryString() {
   this.full = URLDecode(location.search);
   this.pairs = new Array();
   this.parsed = false;

   if (this.full.indexOf('?')==0)
      this.full = this.full.substring(1, this.full.length);

   this.parse = function() {
      if (!this.parsed) {
         var parts = new Array();
         var bits = new Array();
         parts = this.full.split('&');
         for (var i in parts) {
            bits = parts[i].split('=');
            this.pairs[bits[0]] = bits[1];
         }
         this.parsed=true;
      }
   }

   this.value = function(feature) {
      if (!this.parsed) this.parse()
      var val = ''
      for (var i in this.pairs)
         if (i==feature)
            val=this.pairs[i]
      return val
   }
}

// This XMLdocument object wraps the Netscape and IE versions.
// Also extends them with load and onload methods.
// Thanks to Peter-Paul Koch for his excellent tutorial.
// August 2005: the technique is spreading now. Search for "AJAX"
function XMLdocument () {

   this.onload = function() {return true;}
   this.load = function() {return true;}
   this.setXML = function() {return true;}
   this.ready = function() {return true;}
   
   this.url = '';
   this.parseError = '';

   if (window.ActiveXObject) {
      this.main = new ActiveXObject("Microsoft.XMLDOM");
      with (this) {
         main.onreadystatechange = function() {
            if (main.readyState == 4) {
                 ready();
            }
         }
         load = function(address) {
            url = address;
            main.load(url);
         }
		 setXML = function(text) {
		    main.async = "false";
			main.loadXML(text);
			setTimeout("xmlDoc.ready();", 100);
		 }
		 ready = function() {
		    parseError = main.parseError;
			onload();
		 }
		 
      }
   }
   else if (window.XMLHttpRequest) {
      this.main = null;
      this.request = new window.XMLHttpRequest();
      with (this) {
         request.onreadystatechange = function() {
			if (request.readyState == 4) {
                 main = request.responseXML;
                 ready(); 
            }
         }
         load = function(address) {
             url = address;
             request.open("GET",url+"?random="+Math.random(),true);
                                       	         // random number so the xml file is not loaded from the cache
             request.send(null);
         }
		 setXML = function(text) {
		    var parser = new DOMParser();
			main = parser.parseFromString(text,"text/xml");
			setTimeout("xmlDoc.ready();", 100);
		 }
		 ready = function() {
		    parseError = new checkForParseError(main.documentElement); 
			onload();
		 }
      }
   }
   else {
      alert('Your browser can\'t handle this script');
         // Kludge - should raise an exception
   }
}

function checkForParseError (documentElement) {
   // This error checker for XMLDocument comes from FAQTS.
   // The structure of the parseError object emulates IE's XMLDOM object.
   var errorNamespace = 'http://www.mozilla.org/newlayout/xml/parsererror.xml';
   this.errorCode = 0;
   if (documentElement.nodeName == 'parsererror' && documentElement.namespaceURI == errorNamespace)
   {
      this.errorCode = 1;
      var sourceText = documentElement.getElementsByTagNameNS(errorNamespace, 'sourcetext')[0];
      if (sourceText != null) {
         this.srcText = sourceText.firstChild.data
      }
      this.reason = documentElement.firstChild.data;
   }
}

function parse()
{
// also with help from PPK
// This parsing algorithm and its functions on validate XML syntax.
// Check your XML thoroughly if you find unexpected animation results.

   if (xmlDoc.parseError.errorCode!=0) alert(xmlDoc.parseError.reason);

   steps.length=0;
   var x = xmlDoc.main.getElementsByTagName('source')[0];

   if (x.firstChild)
      source = x.firstChild.nodeValue.split('\n')
   else
      source[0] = '';

   x = xmlDoc.main.getElementsByTagName('iteration')[0];

   var j=0;
   for (var i=0;i<x.childNodes.length;i++)
   {
      if (x.childNodes[i].nodeType != 1) continue;
      steps[j] = make_step(x.childNodes[i]);
      j++;
   }

   document.getElementById("pb").innerHTML = progressBar();
   if (editorIsOn) showSourceXml();
   rewind();
   var step=qString.value('step');
   if (step != '') getTo(+step);
}

function progressBar() {
// Make progressBar object
   var pb = "";
   for (var i=0; i<=steps.length; i++)
      pb += generateProgBar0ption(i);
   return pb;
}

function generateProgBar0ption(i) {
// Make progressBar object
// Add a radio, set all radios as new progBar
   return '<input type="radio" name="progressbar" onClick="getTo('+i+');" >';
 }

function make_step (node) {
  var step;
  if (node.nodeName=="move") step = new step_move();
  else if (node.nodeName=="insert") step = new step_insert()
  else if (node.nodeName=="select") step = new step_select()
  else if (node.nodeName=="delete") step = new step_delete()
  else if (node.nodeName=="cut") step = new step_cut()
  else if (node.nodeName=="copy") step = new step_copy()
  else if (node.nodeName=="paste") step = new step_paste()
  else  step = new step_doh();  // in case

  step.parse(node)
  var element = node.getElementsByTagName('comment');
  if (element.length==1) {
     step.comment = (element[0].firstChild==null)?'':element[0].firstChild.nodeValue;
  }
  return step;
}

// The "steps" are a good example of polymorphism in Javascript.
// Each step is different but defines debug and forward functions
// that are called during the animation.

function step_move () {
   this.type = 'Move';
   this.line = 0;
   this.col = 0;
   this.debug = function() {window.status = "move"}
   this.forward = function() {elcid_move(this)}
   this.show = function() {return showMove(this)}
   this.parse = function(node) {parseMove(node,this)}
   this.generate = function() {return genMove(this)}
}

function parseMove(node,step) {
   var element
   element = node.getElementsByTagName('linenumber')
   if (element.length==1)
      step.line = parseInt(element[0].firstChild.nodeValue)
   element = node.getElementsByTagName('colnumber')
   if (element.length==1)
      step.col = parseInt(element[0].firstChild.nodeValue)
}

function step_select () {
   this.type = 'Select';
   this.lines = 0;
   this.chars = 0;
   this.debug = function() {window.status = "select"}
   this.forward = function() {elcid_select(this)}
   this.show = function() {return showSelect(this)}
   this.parse = function(node) {parseSelect(node,this)}
   this.generate = function() {return genSelect(this)}
}

function parseSelect(node,step) {
   var element
   element = node.getElementsByTagName('lines')
   if (element.length==1)
      step.lines = parseInt(element[0].firstChild.nodeValue)
   element = node.getElementsByTagName('chars')
   if (element.length==1)
      step.chars = parseInt(element[0].firstChild.nodeValue)
}

function step_copy () {
   this.type = 'Copy'
   this.debug = function() { window.status = "copy";}
   this.forward = function() { elcid_copy(this); }
   this.show = function() { return showCopy(this); }
   this.parse = function(node) {}
   this.generate = function() { return genCopy(this); }

}

function step_paste() {
   this.type = 'Paste'
   this.debug = function() {window.status = "paste"}
   this.forward = function() {elcid_paste(this)}
   this.show = function() {return showPaste(this)}
   this.parse = function(node) {}
   this.generate = function() {return genPaste(this)}
}

function step_insert() {
   this.type = 'Insert'
   this.lines = new Array()
   this.lines.length = 0
   this.chars = ''
   this.debug = function() {window.status = "insert lines and chars"}
   this.forward =  function() {elcid_insert(this)}
   this.show = function() {return showInsert(this)}
   this.parse = function(node) {parseInsert(node,this)}
   this.generate = function() {return genInsert(this)}

}

function parseInsert(node,step) {
   var len
   var element
   element = node.getElementsByTagName('chars')
   if (element.length==1)
      step.chars = element[0].firstChild.nodeValue
   element = node.getElementsByTagName('line')
   step.lines.length = element.length
   for (var i=0; i<element.length; i++) {
      if (element[i].childNodes.length==1)
         step.lines[i]=element[i].firstChild.nodeValue
      else
         step.lines[i]=''
   }
}

function step_delete() {
   this.type = 'Delete'
   this.debug = function() {window.status = "delete"}
   this.forward =  function() {elcid_delete(this)}
   this.show = function() {return showDelete(this)}
   this.parse = function(node) {}
   this.generate = function() {return genDelete(this)}
}

function step_doh() {
   this.type = 'Unknown'
   this.debug = function() {window.status = "doh?"}
   this.forward =  function() {}
   this.doh = function() {return showDoh(this)}
   this.parse = function(node) {}
   this.generate = function() {return genDoh(this)}
}

function extend_area() {
   area = code.document.getElementById('area');
   area.fontratio = 53/100;
   area.fontSize=0;
   area.numbers = code.document.getElementById('numbers');
   area.lines = new Array();
   var fontsize=qString.value('fontsize');
   if (fontsize=='') {
      fontsize=getCookie("font");
      if (fontsize=='') fontsize=13;
   } else
      setCookie("font",fontsize,14);
   setFontSize(fontsize);
}

function textpad() {
   this.lines = new Array()
   this.lines.length=0
   this.chars = ""
}

// postit object

function extendPost(id) {
   var post;
   post=document.getElementById(id);
   post.beingDragged=false;
   post.offsetX=0;
   post.offsetY=0;
   post.X = 150;
   post.Y = 150;
   return post;
}

function movePost(post,X,Y) {
   if (post.beingDragged) {
      post.X=X-post.offsetX;
      post.Y=Y-post.offsetY; 
      post.style.left = post.X+'px'; 
      post.style.top = post.Y+'px';
   }
}

function showPost(post) {
   post.style.visibility='visible';
   post.style.cursor='move';
}

function hidePost(post) {
   post.style.visibility='hidden';
}

function startDrag(post,X,Y){
   post.offsetX=X-post.X;
   post.offsetY=Y-post.Y;
   post.beingDragged=true;
}

function fillPost(post,comment) {
   if (displaying) {
      comment = '<b><pre><i>'+comment+'</i></pre>'+'<div align="center" onClick="hidePost(postit);" style="cursor:hand">[ok]</div></b>';
      post.innerHTML = comment;
      post.Y=postHeight();
      post.style.top=post.Y;
      showPost(post);
   }
}

function postHeight() {
   var height=85+getLineSpace()*(2+area.y+Math.max(0,area.selection_lines-1))-YScroll(code);

      // This empirical formula works for IE
      // - do it again for Netscape, Safari, (opera, Konqueror?)
      // - work out a better formula

      // This does not work:
      //    alert(document.getElementById('codePanel').style.top);

   return height;

}

/***********************

Display status functions:

   lineCount
   topLine
   colCount
   firstCol
   YScroll
   XScroll
   width
   height
   setFontSize

Utilities to gather information on window and charset size and position.

***********************/

function lineCount() {
   return Math.floor(height(code)/getLineSpace());
}

function topLine() {
   return Math.floor(YScroll(code)/getLineSpace());
}

function colCount() {
   return Math.floor(width(code)/(getLineSpace()*area.fontratio));
}

function firstCol() {
   return Math.floor(XScroll(code)/(getLineSpace()*area.fontratio));
}

function YScroll(win) {
   var scroll;
   if (win.pageYOffset)
      scroll = win.pageYOffset; // all except Explorer
   else if (win.document.documentElement && win.document.documentElement.scrollTop)
      scroll = win.document.documentElement.scrollTop; // Explorer 6 Strict
   else if (win.document.body)
      scroll = win.document.body.scrollTop; // all other Explorers
   return scroll;
}

function XScroll(win) {
   var scroll;
   if (win.pageXOffset)
      scroll = win.pageXOffset; // all except Explorer
   else if (win.document.documentElement && win.document.documentElement.scrollLeft)
      scroll = win.document.documentElement.scrollLeft; // Explorer 6 Strict
   else if (win.document.body)
      scroll = win.document.body.scrollLeft; // all other Explorers

   return scroll;
}

function height(win) {
   var height;
   if (win.innerHeight)
      height = win.innerHeight; // all except Explorer
   else if (win.document.documentElement && win.document.documentElement.clientHeight)
      height = win.document.documentElement.clientHeight; // Explorer 6 Strict Mode
   else if (win.document.body)
      height = win.document.body.clientHeight; // other Explorers

   return height;
}

function width(win) {
   var width=0;
   if (win.innerHeight)
      width = win.innerWidth; // all except Explorer
   else if (win.document.documentElement && win.document.documentElement.clientHeight)
      width = win.document.documentElement.clientWidth;	// Explorer 6 Strict Mode
   else if (win.document.body)
      width = win.document.body.clientWidth; // other Explorers

   return width;
}

function updateFontSize() {
   size = controls.fontsize.value;
   setCookie("font",size,14);
   setFontSize(size);
}

function setFontSize(size) {
   area.fontSize=+size;
   area.style.fontSize=size+'px';
   area.numbers.style.fontSize=size+'px';
}

function getLineSpace() {
   var lineSpace;

   if (browser=='Safari')
      lineSpace = area.fontSize+2;
   else
      lineSpace = area.fontSize+3;

   return lineSpace;
}

function getBrowser () {
   var browser = navigator.appVersion;

   if (browser.indexOf('Opera')>=0) browser='Opera'
   else if (browser.indexOf('Safari')>=0) browser='Safari'
   else if (browser.indexOf('Konqueror')>=0) browser='Konqueror'
   else if (browser.indexOf('Mozilla')>=0) browser='Mozilla'
   else if (browser.indexOf('Internet Explorer')>=0) browser='Internet Explorer';

   return browser;
}

/***********************

Animation functions:

   elcid_move
   elcid_insert
   elcid_select
   elcid_copy
   elcid_paste
   elcid_delete
   update

They modify the displayed code according to the step to make.

***********************/

function elcid_move(step) {
   area.x = step.col;
   area.y = step.line;
   area.selection_lines=0;
   area.selection_chars=0;
   update();
}

function elcid_insert(step) {

   elcid_delete(step)
   if (displaying) disablePlayer()
   with (area) {
      var num_new_lines = step.lines.length
      if (num_new_lines>0) {

         // Move the lines further down to make space
         var bound = lines.length
         for (var i=bound-1; i>=y; i--) lines[i+num_new_lines] = lines[i]

         // cut up top line
         lines[y] = lines[y].slice(0,x)+step.lines[0]

         // Insert middle lines
         for (i=1; i<num_new_lines; i++) {
            lines[y+i] = step.lines[i]
         }

         // Move the cursor
         y=y+num_new_lines

         // make bottom line (including chars)
         lines[y] = step.chars+lines[y].slice(x,lines[y].length)
         x=step.chars.length
         update();
      } else {

         // Add characters
         if (displaying) {
            temp_chars=step.chars;
            setTimeout("elcid_insert_one_char_at_a_time();",50)
         } else {
            lines[y] = lines[y].slice(0,x)+step.chars+lines[y].slice(x,lines[y].length)
            x=x+step.chars.length
            update();
         }
      }
   }
}

function elcid_insert_one_char_at_a_time() {

   if (temp_chars!='') {

      with (area) {
         lines[y] = lines[y].slice(0,x)+temp_chars.charAt(0)+lines[y].slice(x,lines[y].length);
         x++;
         code.document.getElementById('l'+y).innerHTML=make_line(y);
      }

      temp_chars=temp_chars.slice(1,temp_chars.length);
      setTimeout("elcid_insert_one_char_at_a_time();",50);

   } else
      setPlayerButtons();
}

function elcid_select(step) {
   area.selection_lines = step.lines;
   area.selection_chars = step.chars;
   update();
}

function elcid_copy(step) {

   var selection_lines = area.selection_lines;
   var selection_chars = area.selection_chars;
   var x = area.x;
   var y = area.y;

   area.selection_lines = 0;
   area.selection_chars = 0;

   update();

   area.selection_lines = selection_lines;
   area.selection_chars = selection_chars;

   clipboard.lines.length=0;
   clipboard.chars = "";

   if (area.selection_lines>0) {
      // loop to get the lines
      clipboard.lines[0] = area.lines[y].slice(x,area.lines[y].length);
      for (var i=1; i<area.selection_lines; i++) {
			   clipboard.lines[i] = area.lines[y+i];
			}
      clipboard.chars = area.lines[y+selection_lines].slice(0,area.selection_chars);
   } else {
      clipboard.chars = area.lines[y].slice(x,area.selection_chars+x);
	 }
   setTimeout('update();',100);
}

function elcid_paste(step) {
   elcid_insert(clipboard);
}

function elcid_delete(step) {
   with (area) {
      if (selection_lines>0) {
         var last_line = y+selection_lines;
         lines[last_line] = lines[last_line].slice(selection_chars,lines[last_line].length);
         lines[y] = lines[y].slice(0,x)+lines[last_line];
         for (var i=last_line+1; i<lines.length; i++) {
            lines[i-selection_lines] = lines[i];
            lines[i] = '';
         }
         lines.length = lines.length-selection_lines;
      } else
         lines[y] = lines[y].slice(0,x) + lines[y].slice(x+selection_chars,lines[y].length);
      selection_lines = 0;
      selection_chars = 0;
   }
   update();
}

function update() {
   if (displaying) {
      var blanks=1;
      var i;
      var currentCol;
      var content_string  = '';
      var line_numbers = '';
      var current_line = '';

      if (area.y<topLine())
         code.scroll(XScroll(code),area.y*getLineSpace())
      else if (area.y+area.selection_lines>topLine()+lineCount())
         code.scroll(XScroll(code),(area.y+area.selection_lines-lineCount()+2)*getLineSpace());

      for (i=area.lines.length; i>=10; i=i%10) blanks++;
      currentCol=area.x+blanks+1;

      if (currentCol<firstCol())
         code.scroll(currentCol*getLineSpace()*area.fontratio,YScroll(code));
      else if (currentCol+area.selection_chars>firstCol()+colCount())
         code.scroll((currentCol-colCount()+1)*getLineSpace()*area.fontratio,YScroll(code));

      for (i=0; i<area.lines.length; i++) {
         if (i==area.y)
            line_numbers += '<span class="selection">'+format_num(i+1,blanks)+'</span>'
         else
            line_numbers += format_num(i+1,blanks);
         content_string  += '<span id="l'+i+'">'+make_line(i)+'</span>';
         if (i<area.lines.length-1) {
            line_numbers += '<br />\n';
            content_string  += '<br />\n';
         }  
      }
      setPlayerButtons();
      area.innerHTML = content_string;
      area.numbers.innerHTML = line_numbers;
      if (editorIsOn) highlightCartridge(current_step)
   }
}

function make_line(i) {
   with (area) {
      current_line = lines[i]+' '
      if (selection_lines>0 || selection_chars>0) {
         if (i==y) {
            if (selection_lines==0)
               current_line = insert_chars(current_line,x+selection_chars,'#rosruc##tceles#')
            else
               current_line += '#rosruc##tceles#';
            current_line = insert_chars(current_line,x,'#select##cursor#');
         } else if (selection_lines>0 && i>y && i<=y+selection_lines) {
               if (i<y+selection_lines)
                  current_line += '#tceles#'
               else if (i==y+selection_lines)
                  current_line = insert_chars(current_line,selection_chars,'#tceles#');
               current_line = '#select#'+current_line;
         }
      }
      else if (i==y) {
         current_line += '#rosruc#';
         current_line = insert_chars(current_line,x,'#cursor#');
      }
   }
   return HTMLEncode(current_line)
}

function setPlayerButtons() {
   if (auto_on && current_step<steps.length) {
      disable(controls.rewindButton,rewDis);
      disable(controls.backButton,backDis);
      disable(controls.forwardButton,stepDis);
      disable(controls.ffdButton,ffdDis);
      enable(controls.go_playButton,stopOff);
      controls.runButton.disabled=true;
   } else {
      if (current_step>0) {
         enable(controls.rewindButton,rewOff);
         enable(controls.backButton,backOff);
      } else {
         disable(controls.rewindButton,rewDis);
         disable(controls.backButton,backDis);
      }
      if (current_step<steps.length) {
         enable(controls.forwardButton,stepOff);
         enable(controls.ffdButton,ffdOff);
      } else {
         disable(controls.forwardButton,stepDis);
         disable(controls.ffdButton,ffdDis);
      }
      controls.runButton.disabled=false;
   }
   if (steps.length>0) {
      enableRadioGroup(controls.progressbar);
      controls.progressbar[current_step].checked = true;
   }
}

function auto() {
   if (current_step<steps.length && auto_on) {
      if (temp_chars=='') forward()
      setTimeout("auto()",1500)
   } else {
	    if (auto_on) auto_on=false
      setPlayerButtons()
   }
}

function view_comments() {
   var i
   var content_string  = ''

   for (var i=0; i<steps.length ; i++)
     if (steps[i].comment) content_string+='<p>'+steps[i].comment

   area.innerHTML = content_string
   area.numbers.innerHTML = ''
}

// Change editbox size dynamically

function updateEdbox() {
   var edBox = code.document.getElementById('edbox');
   var grabSource = new Array();
   grabSource = edBox.value.split('\n');
   var rowCount = grabSource.length;
   var blanks = 0;
   for (var i=area.lines.length; i>=10; i=i%10) blanks++;
   var columns = colCount()-blanks-4;
   var lineNumbers ='';
   for (var i=0; i<rowCount; i++) {
      lineNumbers += format_num(i+1,blanks) + '<br />\n';  
      if (grabSource[i].length>columns) columns=grabSource[i].length;
   }
   lineNumbers += format_num(i+1,blanks);  
   edBox.cols = columns;
   edBox.rows = rowCount+1;
   area.numbers.innerHTML = lineNumbers;
}

function swapImg(b,img) {
   if (i=b.getElementsByTagName('img')[0]) {
      i.src = img.src;
      i.alt = (img.alt)?img.alt:'';
   }
}

function disable(b,img) {
   if (!b.disabled) {
      swapImg(b,img);
      b.disabled = true;
   }
}

function enable(b,img) {
   if (b.disabled) {
      b.disabled = false;
      swapImg(b,img);
   }
}

function fast_to(step) {
   displaying = false;
   while (current_step<step) {
      steps[current_step].forward();
      current_step++;
   }
   displaying = true;
   update();
}


/***********************

String manipulation functions:

   format_num
   HTMLEncode
   replace_once
   replaceAll
   lastIndexOf
   insert_chars

Some of these probably exist already
(Javascript has generic regexp match/replace functions)
but it was quicker to rewrite them.

***********************/

function getURL() {
   var loc = new String(window.location)
   var endURL = loc.indexOf('?')
   if (endURL==-1) endURL = loc.length
   return URLDecode(loc.substring(0,endURL))
}

function format_num(num,blanks) {
   var str=num+':';
   while (str.length<blanks+1) str = '&nbsp;'+str;
   return str;
}

function HTMLEncode(str) {

   str = replaceAll(str,'&','&amp;')
   str = replaceAll(str,'<','&lt;')
   str = replaceAll(str,'>','&gt;')
   str = replaceAll(str,' ','&nbsp;')
   str = replaceAll(str,'"','&quot;')
   str = replaceAll(str,'#select#','<span class="selection">')
   str = replaceAll(str,'#tceles#','</span>')
   str = replaceAll(str,'#cursor#','<span class="cursor" style="background-image:url(images/cursor.gif);background-repeat:repeat-y;">')
   str = replaceAll(str,'#rosruc#','</span>')

   return str
}

function replace_once(str,ch,newchars) {

   var i = str.indexOf(ch);

   if (i>=0)
      str = str.slice(0,i)+newchars+str.slice(i+ch.length,str.length);
   return str;
}

function replaceAll(str,ch,newchars) {

   str = ''+str;
   var i = str.indexOf(ch); var newStr='';

   while (i>=0) {
      newStr += str.slice(0,i)+newchars;
      str = str.slice(i+ch.length,str.length);
      i = str.indexOf(ch);
   }

   newStr +=str;   

   return newStr;
}

function lastIndexOf(ch,str) {

   var i=0; var j=0;
   while (str.indexOf(ch)>=0) {
      j = str.indexOf(ch)+ch.length;
      i += j;
      str=str.slice(j,str.length);
   }

   return i;
}

function insert_chars (str,index,chars) {
   str =  str.slice(0,index)+(chars+str.slice(index,str.length));
   return str;
}

function URLDecode(encoded) {
   return unescape(replaceAll(encoded,'+',' '));
}

function stringRepeat(str,occurs) {
   var result='';
   for (var i=1; i<=occurs; i++) result += str;
   return result;
}

// setCookie() and getCookie() functions
// almost identical to Netscape's Client-Side JavaScript Guide versions

function getCookie(Name) {
   var search = Name + "=";
   var offset = -1;
   var end = -1;
   var result = '';
   if (document.cookie.length > 0) { // if there are any cookies
      offset = document.cookie.indexOf(search);
      if (offset != -1) { // if cookie exists
         offset += search.length; // set index of beginning of value
         end = document.cookie.indexOf(";", offset); // set index of end of cookie value
         if (end == -1) end = document.cookie.length;
         result = unescape(document.cookie.substring(offset, end));
      }
   }
   return result;
}

function setCookie(name, value, daysExpire) {
   var expireDate = ''
   if(daysExpire) {
      var expires = new Date()
      expires.setTime(expires.getTime() + 1000*60*60*24*daysExpire);
      expireDate = "expires=" + expires.toGMTString() + ";"
   }
   document.cookie = name + "=" + escape(value) + ";" + expireDate + "path=/"
}

/***********************

Control functions:

   rewind
   back
   getTo
   forward
   go_play
   fast
   onload
   loadfile

***********************/

function rewind() {
   monitorUsage('rewind');
   auto_on=false;
   area.x=0;
   area.y=0;
   area.selection_lines=0;
   area.selection_chars=0;
   for (var i=0; i<source.length; i++) area.lines[i] = source[i];
   area.lines.length = source.length;
   hidePost(postit);
   current_step=0;
   update();
}

function back() {
   monitorUsage('back');
   auto_on=false;
   if (current_step>0) {
      var step = current_step-1;
      rewind();
      fast_to(step);
   }
}

function getTo(step) {
   if (step != current_step) {
      monitorUsage('getTo');
      auto_on=false;
      displaying = false;
      if (current_step>step) rewind();
      fast_to(step);
      displaying = true;
      update();
   }
}

function forward() {
   monitorUsage('forward');
   if (current_step<steps.length) {
      var step=steps[current_step];
      current_step++;
      step.forward();
      if (step.comment) fillPost(postit,step.comment);
      if (step.comment=='') hidePost(postit);
      step.debug();
   } else
      window.status = 'Animation finished';
}

function go_play() {
   monitorUsage('go-play')
   if (auto_on) {
      auto_on=false
      swapImg(controls.go_playButton,playOn)
   } else {
      auto_on=true
      swapImg(controls.go_playButton,stopOn)
      auto()
   }
}

function fast() {
   monitorUsage('fast');
   fast_to(steps.length);
}

function changeFile() {
   monitorUsage('pickfile');
   var winprop = "";
   var winloc = "";
   if (editorIsOn) winloc="../";
   if (isServer()) {
      winloc += "server loadfile.php";
      winprop = 'height=700,width=750,location=no,scrollbars=yes,menu=no,toolbar=no,status=no,resizable=yes';
   }
   else {
      winloc += "loadfile.html";
      winprop = 'height=100,width=250,location=no,scrollbars=no,menu=no,toolbar=no,status=no,resizable=yes';
   }
   var w = window.open(winloc, 'file', winprop);
   w.focus();
}

function isServer() {
   var d=''+document.location;
   return (d.indexOf('http://')==0);
}

function loadCommandClient(filename) {
   if (filename!='') {
      filename = replaceAll(filename,'\\','/');
	   filename = filename.slice(lastIndexOf('/',filename),filename.length);
      filename = AnimationsFolder+filename;
      if (editorIsOn) filename = '../'+filename;
      location=getURL()+'?file='+escape(filename);
   }
}

function loadCommandServer(fileName) {
   if (fileName!='') {
      fileName = replaceAll(fileName,'\\','/');
      if (editorIsOn) {
         fileName = '../'+fileName;
      }
      location=getURL()+'?file='+escape(fileName);
   }
}

function loadfile() {
   monitorUsage('load');
   var filename = qString.value('file');
   if (filename == '') {
      document.el_icon.alt="Rate a tutorial (you need to load one first)";
      disablePlayer();
   }
   else {
      if (loggedin) {
	     document.el_icon.alt="Rate this tutorial";
      }
	  else {
         document.el_icon.alt="Login to rate this tutorial";
      }
      filename = replaceAll(filename,'\\','/');
      xmlDoc.load(filename);
      document.getElementById('filename').innerHTML=filename;
   }
}

function runCode() {
   monitorUsage('run');
   if (code.document.getElementById('edbox'))
      codeToRun = code.document.getElementById('edbox').value;
   else
      codeToRun = area.lines.join('\n');
   // insert base href in the head
   var folderToRun = location.protocol + "//" + location.host + folderPart(location.pathname) + (isServer()?folderPart(xmlDoc.url):AnimationsFolder);
   var i = codeToRun.indexOf("</head>");
   if (i>=0)
      codeToRun = codeToRun.slice(0,i)+'<base href="'+folderToRun+'" >'+codeToRun.slice(i,codeToRun.length);
   else
      codeToRun = '<head><base href="'+folderToRun+'" ></head>'+codeToRun;
   var interpreter = controls.interpreter.value;
   var winprop = 'height=400,width=600,location=no,scrollbars=yes,menu=no,toolbar=no,status=yes,resizable=yes';
   var w = window.open(interpreter, 'run', winprop);
   w.focus();
}

function folderPart(str) {
   return str.slice(0,lastIndexOf("/",str));
}

function editCode() {
   monitorUsage('edit'); 
   if (!auto_on) {
      var editText = "<textarea id='edbox' style=\"font-size:"+area.fontSize+"px; font-family: inherit;\" onKeyUp=\"parent.updateEdbox();\">";
      editText += area.lines.join('\n');
      editText += "</textarea>";
      area.innerHTML = editText;
      updateEdbox();
      document.images.iterateTab.src=iterateTabOff.src;
      document.images.commentsTab.src=commentsTabOff.src;
      document.images.editTab.src=editTabOn.src;
      disablePlayer();
      controls.runButton.disabled=false;
   }
}

function options() {
   monitorUsage('options');
   var winprop = 'height=100,width=250,location=no,scrollbars=no,menu=no,toolbar=no,status=no,resizable=yes';
   var winloc = "options.html";
   var w = window.open(winloc, 'opts', winprop);
   w.focus();
}

function showComments() {
   monitorUsage('comments'); 
   if (!auto_on) {
      view_comments()
      document.images.iterateTab.src=iterateTabOff.src
      document.images.commentsTab.src=commentsTabOn.src
      document.images.editTab.src=editTabOff.src
      disablePlayer()
   }		 
}

function showIterate() {
   monitorUsage('animation');
   update();
   document.images.iterateTab.src=iterateTabOn.src;
   document.images.commentsTab.src=commentsTabOff.src;
   document.images.editTab.src=editTabOff.src;
}

function rateTutorial() {
   if (loggedin) {
	  document.getElementById("rates").style.visibility='visible';
   }
}

function rateFinish(stars) {
   var rateURL = cgiURL+'rate_file.php?file='+xmlDoc.url+'&rate='+stars;
   var rateRequest = GETRequest( rateURL, rateResponse );
   monitorUsage('rate');
}

function GETRequest(url, responseProcess) {
   var request = false;

   if (window.XMLHttpRequest) { // Mozilla, Safari, Opera
      request = new XMLHttpRequest();
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
      request.open('GET', url, true);
      request.send();
      request.onreadystatechange = responseProcess;
   }
   return request;
}

function rateResponse() {
   if (this.readyState == 4) {
      if (this.status == 200) {
	     // all good
        // alert('rating recorded:\n'+this.responseText);           
      }
      else {
         alert('Sorry rating is broken.');
      }
      document.getElementById("rates").style.visibility='hidden';
	   document.el_icon.alt="You have rated this tutorial";
   }
}

function monitorUsage(currentCommand) {
   if (document.el_icon) {
      if (fileUseID=='' || currentCommand=='load')
         fileUseID = generateFileUseID();

      var monitorString = '?command='+currentCommand+'&fileuseid='+fileUseID;
	  if (editorIsOn) monitorString += '&editor=on';
      if (qString.full!='') monitorString += '&'+qString.full;
      document.el_icon.src = monitorImg+monitorString;
   }
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

function disablePlayer() { 
   disable(controls.rewindButton,rewDis);
   disable(controls.backButton,backDis);
   disable(controls.forwardButton,stepDis);
   disable(controls.ffdButton,ffdDis);
   disableRadioGroup(controls.progressbar);
   controls.runButton.disabled = true;
}

function disableRadioGroup (radioGroup) {
   if (radioGroup)
      for (var b = 0; b < radioGroup.length; b++)
         radioGroup[b].disabled = true;
}

function enableRadioGroup (radioGroup) {
   if (radioGroup)
      for (var b = 0; b < radioGroup.length; b++)
         radioGroup[b].disabled = false;
}

