function ValidateArray () {
	this.username = 0;
	this.email = 1;
}
 
 Val = new ValidateArray();
 
 
function Ajax(file, data, div, method)
{
     $.ajax({
        type: method,
        url: file,
        data: data,
        cache: false,
        success: function(html){
        	document.getElementById(div).innerHTML = html;
        //  $("#"+div).html(html);
         valid = html;
        } 
     });
}

function AjaxMap(file, data, div)
{     $.ajax({
        type: "GET",
        url: file,
        data: data,
        cache: false,
        success: function(html){
        	document.getElementById(div).innerHTML = html;
        //  $("#"+div).html(html);
         valid = html;
        } 
     });
}

function AjaxT(file, data, div)
{
     $.ajax({
        type: "POST",
        url: file,
        data: data,
        cache: false,
        success: function(html){
          $("#"+div).html(html);
        } 
     });
}

function AjaxT2(file, data, div)
{

     $.ajax({
        type: "POST",
        url: file,
        data: data,
        cache: false,
        success: function(html){
           $("#"+div).html(html);
        } 
     });
}

function AjaxMyOffers(file, data, div)
{
	 $("#img_loading").css("display", "block");
     $.ajax({
        type: "POST",
        url: file,
        data: data,
        cache: false,
        success: function(html){
          $("#"+div).html(html);
		  $("#img_loading").css("display", "none");
        } 
     });
}




function AjaxUplodify(field, content, div) {
	$("#ajax_loader").show();
     $.ajax({	
        type: "GET",        
        url: field+content,
        cache: false,
        success: function(html){
        	string=html.split("[DELIMITER]");
        	$("#"+div).html(string[0]);  
        	$("#contentLeft ul").sortable({ opacity: 0.6, cursor: 'move', update: function() {
				var order = $(this).sortable("serialize") + '&action=updateRecordsListings';
				$.post("/ajaxtabs/saveImageOrder/0/5", order, function(theResponse){
					$("#contentRight").html(theResponse);
				});
			}
			}); 
        	
        	
        	$("#ajax_loader").hide();
        //eval("Val."+field +"= string[1]");   
        } 	     
     });  
} 



function AjaxTranslate(file, data, div, img)
{
	$("#"+img).css("display", "block");
	$.ajax({
        type: "POST",
        url: file,
        data: data,
        cache: false,
        success: function(html){
			var inst =  tinyMCE.get(div);
			inst.setContent(html);
			$("#"+img).css("display", "none");
        } 
     });
}
function startTranslation(fromlang, tolang, sourceText, toId, img){

	
	$.translate(sourceText, fromlang, tolang, {

          start:          function() { 
          	$("#"+img).css("display", "block");
           },

          complete:    function(translation) {      	
          	var inst =  tinyMCE.get(toId);
			inst.setContent(translation);
			$("#"+img).css("display", "none");
          
           },

          error:         function() { 
          $("#"+img).css("display", "none");
          alert('error');
          }

     });
}


function validate(field, content, div, success, error) {
	   $.ajax({
        type: "GET",
        url: '/validations/field/'+field+'/'+content+'/'+success+'/'+error,
        cache: false,
        success: function(html){
        //string=html.split("[DELIMITER]");
        //$("#"+div).html(string[0]);
   
        //eval("Val."+field +"= string[1]");
   		$("#"+field+"_hidden").val(html);
   		alert(html);
        }
        
     });

}

function validateUser(field, content, div) {
	   $.ajax({
        type: "GET",
        url: '/validations/validUser/?u='+content,
        cache: false,
        success: function(html){
        //string=html.split("[DELIMITER]");
        // $("#"+div).html(string[0]);
   
        // eval("Val."+field +"= string[1]");
   		$("#"+field+"_hidden").val(html);
   		$("#"+field+"_hidden").blur();

        }
        
     });

}

function validatePhone(field, content, div) {
	   $.ajax({
        type: "GET",
        url: '/validations/validPhone/?u='+content,
        cache: false,
        success: function(html){
   		$("#"+field+"_hidden").val(html);
   		$("#"+field+"_hidden").blur();

        }
        
     });

}

function validateEmail(field, content, div) {
     
     $.ajax({
        type: "GET",
        url: '/validations/validEmail/'+content,
        cache: false,
        success: function(html){
        string=html.split("[DELIMITER]");
        $("#"+div).html(string[0]);
   
        eval("Val."+field +"= string[1]");
   
        }
        
     });
}

function passwordStatus(field, content, div) {
     
     $.ajax({
        type: "GET",
        url: '/validations/passwordStatus/'+content,
        cache: false,
        success: function(html){
        string=html.split("[DELIMITER]");
        $("#"+div).html(string[0]);
   
        eval("Val."+field +"= string[1]");
   
        }
        
     });
}

function checkValidate( arr ) {
	retval = true;
	for (var i = 0;i<arr.length;i++) {

			 if ( eval("Val."+arr[i]) == 0 ) {
			 	alert('Моля проверете дали всички полета са попълнени правилно!');
			 	retval = false;
	
			 }
			 
	}
	return retval;
		
}

 
function dg_keydown(event)
{
 
	val = document.getElementById('des').value;
	obj = document.getElementById('key_search');
	 if ( max_results == 0 )	 max_results = ( document.getElementById('max_results').value ) ;
//alert(elementNum);
//alert(max_results);
	if (event.keyCode == 40) { // nadol
		
		if ( elementNum >= max_results ) return;
		 
		elementNum++;
		AjaxSearch('/search/SearchAjax/'+val+'/'+elementNum,'abs','key_search','GET');
		max_results = ( document.getElementById('max_results').value ) ;

			 
	 
	}
	else if ( event.keyCode == 38 ) {
		if ( elementNum <= 1 ) return;
		 
		elementNum--;
		AjaxSearch('/search/SearchAjax/'+val+'/'+elementNum,'abs','key_search','GET');
	
 
	}
	else if ( event.keyCode == 13 ) {
		document.getElementById('des').value = document.getElementById('selected_city').value;
		document.getElementById('hidden_id').value = document.getElementById('selected_cityid').value;
		document.forms.sform.submit();
		
	}
	
	else {
		AjaxSearch('/search/SearchAjax/'+val,'abs','key_search','GET');
		document.getElementById('key_search').style.display = 'none';
	}
	 
	    document.getElementById('key_search').scrollTop = 0;

	
	 
} 

//change area on step 2 on 2
var pole;
function LoadAreas(url,type,s_id) {
 pole=type;
 req = false;
 url=url+"&s_id="+s_id;
 if (window.XMLHttpRequest) {
	try {
		req= new XMLHttpRequest();
	}
	catch (e) {
		req = false;
	}
 }
 else if(window.ActiveXObject) {
	 try {
		req = new ActiveXObject("Msxml2.XMLHTTP");
		}
		catch(e)
		{
			try
			{
				req = new ActiveXObject("Microsoft.XMLHTTP");
			}
			catch(e)
			{
				req = false;
			}
		}
 }
 if (req) {
 req.onreadystatechange=processReqChange;
 req.open("GET", url, true);
 req.send(null);
 }
}
 
 function SubmitAjax(field, content, div) {
     $.ajax({	
        type: "GET",        
        url: field+content,
        cache: false,
        success: function(html){
        	string=html.split("[DELIMITER]");
        	$("#"+div).html(string[0]);   
        //eval("Val."+field +"= string[1]");   
        }      
     });   
  
}

 
 function SubmitAjaxTst(field, content, div) {
     $.ajax({	
        type: "POST",        
        url: field+content,
        cache: false,
        success: function(html){
        	string=html.split("[DELIMITER]");
        	$("#"+div).html(string[0]);  
        	
        //eval("Val."+field +"= string[1]");   
        }      
     });  
 } 
 
 
 function AjaxRedirectRes(content, div) {
	 $('#'+div).hide();
     $.ajax({	
        type: "POST",        
        url: content,
        cache: false,
        success: function(html){
        	string=html.split("[DELIMITER]");
        	$("#"+div).html(string[0]);
        	$('#'+div).show();
        }      
     });  
 }
 
 

  
function SubmitAjaxPaging(field, content, div){	
	$.ajax({	
        type: "GET",        
        url: field+content,
        cache: false,
        success: function(html){			
        	string=html.split("[DELIMITER]");
        	$("#"+div).html(string[0]);
        	$('#paging_area').show();
			$('#loading_paging').hide();
        }      
     });   
}
 
 
 
function SubmitAjax2(field, content, div) {
     $.ajax({	
        type: "GET",        
        url: field+content,
        cache: false,
        success: function(html){
        	string=html.split("[DELIMITER]");
        	$("#"+div).html(string[0]);  
        	$('#loading').hide();
        	$('#loading2').hide();
        //eval("Val."+field +"= string[1]");   
        }      
     });   
  
}
 
 
 function SubmitAjaxTest(field, content, div) {
     $.ajax({	
        type: "GET",        
        url: field+content,
        cache: false,
        dataType: "json",
        success: function(html){
	    	 for (var i in html)
	    	 {
	    		 $("#"+div).append('<p>name:' + html[i].name + 'id:' + html[i].id + '</p>');
	    	 } 
        }      
     });   
  
} 
 
 
 function AjaxReloadWindow(field, content, div) {
     $.ajax({	
        type: "GET",        
        url: field+content,
        cache: false,
        success: function(html){
        	string=html.split("[DELIMITER]");
        	$("#"+div).html(string[0]);  
        	window.location.reload();
        //eval("Val."+field +"= string[1]");   
        }      
     });  
}



function SubmitAjaxLoad(field, content, div) {
	$("#load_ajax").show();
     $.ajax({	
        type: "GET",        
        url: field+content,
        cache: false,
        success: function(html){
        	string=html.split("[DELIMITER]");
        	$("#"+div).html(string[0]);   
        	$("#load_ajax").hide();
        //eval("Val."+field +"= string[1]");   
        }      
     });
    	
     
  
}


function SendMailAjax(field, content, div, cnt) {
	$.ajax({
		type: "GET",
		url: field+content,
		cache: false,
		success: function(html){
			string=html.split("[DELIMITER]");
			$("#"+div).html(string[0]);
			if(cnt > 0){
			SendMailAjax(field, content, div, cnt);				
			}
			//eval("Val."+field +"= string[1]");
		}
	});
}


function SubmitAjax_Ajax(field, content, div, txt) {	
	var name="&val=";	
	for(i=0;i< txt.length; i++) {
		name=name + txt.charCodeAt(i) + "-";
	}
	$.ajax({
		type: "GET",
		url: field+content+name,
		cache: false,
		txt: txt,
		success: function(html){
			string=html.split("[DELIMITER]");
			$("#"+div).html(string[0]);
			//eval("Val."+field +"= string[1]");
		}
	});
}
 
 
 function execute_js(file, data)
{
     $.ajax({
        type: 'post',
        url: file,
        data: data,
        cache: false,
        success: function(html){
        	eval(html);
        }
     });
}
 
 function execute_jsJ(file, data, it)
 { 
      $.ajax({
         type: 'post',
         url: file,
         data: data,
         cache: false,
         success: function(){         	
         	it.parents(".pic_wrapper").fadeOut().remove();
         }
      });
 }  
 
function win1251toUtf8(string) {
        
    string = string.replace(/\r\n/g,"\n");
    var utftext = "";

    for (var n = 0; n < string.length; n++) {

        var c = string.charCodeAt(n);

        if (c < 128) {
            utftext += String.fromCharCode(c);
        }
        else if((c > 127) && (c < 2048)) {
            utftext += String.fromCharCode((c >> 6) | 192);
            utftext += String.fromCharCode((c & 63) | 128);
        }
        else {
            utftext += String.fromCharCode((c >> 12) | 224);
            utftext += String.fromCharCode(((c >> 6) & 63) | 128);
            utftext += String.fromCharCode((c & 63) | 128);
        }

    }

    return utftext;
}

function strip_tags (str, allowed_tags) {
 
    var key = '', allowed = false;
    var matches = [];
    var allowed_array = [];
    var allowed_tag = '';
    var i = 0;
    var k = '';
    var html = '';
    var replacer = function (search, replace, str) {
    return str.split(search).join(replace);
    };
     
    // Build allowes tags associative array
    if (allowed_tags) {
    allowed_array = allowed_tags.match(/([a-zA-Z0-9]+)/gi);
    }
     
    str += '';
     
    // Match tags
    matches = str.match(/(<\/?[\S][^>]*>)/gi);
     
    // Go through all HTML tags
    for (key in matches) {
    if (isNaN(key)) {
    // IE7 Hack
    continue;
    }
     
    // Save HTML tag
    html = matches[key].toString();
     
    // Is tag not in allowed list? Remove from str!
    allowed = false;
     
    // Go through all allowed tags
    for (k in allowed_array) {
    // Init
    allowed_tag = allowed_array[k];
    i = -1;
     
    if (i != 0) { i = html.toLowerCase().indexOf('<'+allowed_tag+'>');}
    if (i != 0) { i = html.toLowerCase().indexOf('<'+allowed_tag+' ');}
    if (i != 0) { i = html.toLowerCase().indexOf('</'+allowed_tag)   ;}
     
    // Determine
    if (i == 0) {
    allowed = true;
    break;
    }
    }
     
    if (!allowed) {
    str = replacer(html, "", str); // Custom replace. No regexing
    }
    }
     
    return str;
 
}
 