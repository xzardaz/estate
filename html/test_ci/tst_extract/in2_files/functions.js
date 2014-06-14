var basePrice;
var checkHidden = 0;
var whoami=navigator.userAgent.toLowerCase();  //use lower case name
var my_version = parseInt(navigator.appVersion); //get version
var is_ie = (whoami.indexOf("msie") != -1); //does name contain 'msie'?

function setCookie(name,value,days) {
	if (days) {
		var date = new Date();
		date.setTime(date.getTime()+(days*24*60*60*1000));
		var expires = "; expires="+date.toGMTString();
	}
	else var expires = "";
	document.cookie = name+"="+value+expires+"; path=/";
}

function getCookie(name) {
	var nameEQ = name + "=";
	var ca = document.cookie.split(';');
	for(var i=0;i < ca.length;i++) {
		var c = ca[i];
		while (c.charAt(0)==' ') c = c.substring(1,c.length);
		if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
	}
	return null;
}

function removeCookie(name) {
	createCookie(name,"",-1);
}

function checkUncheck(elements) {
	field = document.getElementsByName(elements);
	checkflag = field[0].checked;
	 
	if (checkflag == false) {
	  for (i = 0; i < field.length; i++) { 
	  	
	  	field[i].checked = true; }
	  checkflag = true;
	  }
	else {
	  for (i = 0; i < field.length; i++) {
	  	
	  field[i].checked = false; }
	  checkflag = false;
	  }
}
	  
function selectCheckbox(elements) {
	field = document.getElementsByName(elements);
	  for (i = 0; i < field.length; i++) { 
	  field[i].checked = true; }
}	  

function deselectCheckbox(elements) {
	field = document.getElementsByName(elements);
	  for (i = 0; i < field.length; i++) { 
	  field[i].checked = false; }
}

function checkUncheckIds(elements) {
	field = document.getElementById(elements[0]);
	checkflag = field.checked;
	if (checkflag == false) {
	  for (i = 0; i < elements.length; i++) { 
	  	
	  	document.getElementById(elements[i]).checked = true; }
	  checkflag = true;
	  }
	else {
	  for (i = 0; i < elements.length; i++) {
	  document.getElementById(elements[i]).checked = false; }
	  checkflag = false;
	  }
}	  
	  
	  
function showHideCheck(){
	novo = document.getElementById('stroitelstvo');
	 if ( checkHidden == 0 ) {
 
	 novo.style.visibility='visible';
	 novo.style.display='block';
 	 novo.style.height='100%';
 	 checkHidden = 1;
 	 }
 	 else {
 	 novo.style.display='none';
 	 novo.style.visibility='hidden';
 	 novo.style.height='5px';
 	 checkHidden = 0; 
     }
}

	function fixIeDropDown(select){

			var select = (typeof select == "string") ? document.getElementById(select) : select;

			// THIS FUNCTION IS ONLY CONCERNED WITH INTERNET EXPLORER NON-MULTIPLE SELECT NODES THAT HAVE A SPECIFIC WIDTH DEFINED
			if ( !is_ie) return;
			if(!select.attachEvent || navigator.userAgent.indexOf("Opera") > -1 || select.multiple || select.currentStyle.width == "auto") { return; }

			var body = document.getElementsByTagName("body").item(0);

			var si = select.selectedIndex;
			var clone = select.cloneNode(true);
			clone.style.position = "absolute";
			clone.style.visibility = "hidden";
			clone.style.width = "auto";		
			body.appendChild(clone);

			clone._initialOffsetWidth = select.offsetWidth;
			clone._initialOffsetHeight = select.offsetHeight;
			clone._autoWidth = clone.offsetWidth;

			clone = body.removeChild(clone);
			clone.style.visibility = "visible";
			clone.style.width = clone._initialOffsetWidth + "px";

			var span = document.createElement("span");
			span._isIeDropDownContainer = true;
			span.style.position = "relative";
			span.style.width = clone._initialOffsetWidth + "px";
			span.style.height = clone._initialOffsetHeight + "px";
			span.style.marginBottom = "10"; //hmm...quirky...
			span.appendChild(clone);

			if (select.parentNode._isIeDropDownContainer){
				select.parentNode.parentNode.replaceChild(span, select.parentNode);
			}else{
				select.parentNode.replaceChild(span, select);
			}
			

			if (clone._autoWidth > clone._initialOffsetWidth){
				var expand = function(){
					event.srcElement.parentNode.style.zIndex = 1;
					event.srcElement.style.width = "auto";
					if (event.srcElement.offsetWidth > event.srcElement._initialOffsetWidth){
						event.srcElement.style.width = "auto";
					}else{
						event.srcElement.style.width = event.srcElement._initialOffsetWidth + "px";
					}
				};
				var contract = function(){
					event.srcElement.parentNode.style.zIndex = 0;
					event.srcElement.style.width = event.srcElement._initialOffsetWidth + "px";

				};
				clone.attachEvent("onactivate", expand);
				clone.attachEvent("onchange", contract);
				clone.attachEvent("ondeactivate", contract);
			}
			clone.selectedIndex = si;
		}

var d = document;

function checkCat(what) {
	var mf = d.forms["search"];
	for (i = 0; i < mf.length; i++) {
		bum = mf[i].id.split("_");
		if (bum[0]+"_"+bum[1] == 'cat_'+what) {
			if (typeof(checked) == "undefined")
				var checked = !(mf[i].checked);
			mf[i].checked = checked;
		}
	}
}

function clearAll(what) {
	for (i = 0; i < d.getElementsByName(what).length; i++)
		d.getElementsByName(what)[i].checked = false;
}
function addAll(what) {
	for (i = 0; i < d.getElementsByName(what).length; i++)
		d.getElementsByName(what)[i].checked = true;
}


function expand(id) {
 elm = document.getElementById(id);
 vis = elm.style.visibility;
 if ( vis == 'hidden') {
  elm.style.visibility='visible';
  elm.style.height='100%';
  set_expand(id, 'visible', 1);
  document.getElementById(id+'_pm').innerHTML = '-';
 }
 else {
  elm.style.visibility='hidden';
  elm.style.height='1px';
    document.getElementById(id+'_pm').innerHTML = '+';
  set_expand(id, 'hidden', 1);
 }
}

function set_expand(name, vis) {
 setCookie(name, vis, 1);
}

function get_expand(name) {
 vis = getCookie(name);
 if ( vis == null ) {
  vis='hidden';
  displ='none';
 }
 
 
 document.getElementById(name).style.visibility=vis;
 
 if ( vis == 'hidden') {
  document.getElementById(name).style.height='1px';
  document.getElementById(name+'_pm').innerHTML = '+';
 }
 else {
  document.getElementById(name).style.height='100%';
  document.getElementById(name+'_pm').innerHTML = '-';
 }
 
 
}



function bashtata(name,arr_name){ 
	var str='';
	$("#"+name+" input:checked").each(function(i) {;		
			str += '&'+arr_name+'[]='+$(this).val();		
	});
	return str;
}

function bashtata2(name,arr_name){ 	
	var str='';
	$("#"+name+" input:checked").each(function(i) {;		
			str += '&'+arr_name+'[]='+$(this).val();
//			alert(str);
	});
	return str;
}


function ChangeDivStyle(count,div){
	if(count > 0){
		document.getElementById(div).style.display = "block";
	}else{
		document.getElementById(div).style.display = "none";
	}
}

function ToggleDivContent(count, div) {
    
    if(count == 1) {
        
        var status = document.getElementById(div).style.display;
        
        if(status == "block") {
            document.getElementById(div).style.display = "none";    
        }else{
            document.getElementById(div).style.display = "block";
        }
    }
}