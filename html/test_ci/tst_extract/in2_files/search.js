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

function Clear_Elements() {
//and city[1] and order[15] no need to clear
window.document.getElementById("extras").innerHTML=""; //0
window.document.f.ad_type_id.value=""; //0
window.document.f.nomer.value=""; //13
window.document.f.keyword.value="" //12
window.document.f.surface_to.value=""; //8
window.document.f.surface_from.value=""; //7
window.document.f.price_to.value=""; //4
window.document.f.price_from.value=""; //3
for (var i = 0; i<document.f.elements['floor_from'].length; i++) { //17
		document.f.elements['floor_from'].options[i].selected = false;
	}
for (var i = 0; i<document.f.elements['floor_to'].length; i++) { //16
		document.f.elements['floor_to'].options[i].selected = false;
	}

for (var i = 0; i<document.f.elements['currency_kv_id'].length; i++) { //5
		document.f.elements['currency_kv_id'].options[i].selected = false;
	}

for (var i = 0; i<document.f.elements['currency_id'].length; i++) { //6
		document.f.elements['currency_id'].options[i].selected = false;
	}

for (var i = 4; i < 27; i++) {
     b = document.getElementById("cat_5_"+i);
     if (b) { b.checked = false;  }
     }

for (var i = 0; i < 15; i++) {
     b = document.getElementById("bt"+i);
     if (b) { b.checked = false;  }
     }

}


// getvane na konkretno cookie i zarejdane na formata
function Get_Cookie( name ) {
	var start = document.cookie.indexOf( name + "=" );
	var len = start + name.length + 1;
	if ( ( !start ) && ( name != document.cookie.substring( 0, name.length ) ) )
	{
		return null;
	}
	if ( start == -1 ) return null;
	var end = document.cookie.indexOf( ";", len );
	if ( end == -1 ) end = document.cookie.length;
    Clear_Elements();
	var stre=unescape( document.cookie.substring( len, end ) );
	var strexp=stre.split("|||");
	for (var i = 0; i < document.f.city_id.length; i++) {
		if (document.f.city_id.options[i].value==strexp[1]) 	window.document.f.city_id.options[i].selected="true"; // city_id
	}
	
	
	
	
	

	var btype=strexp[2].split(","); //building types ids
	for (var k = 0; k < btype.length-1; k++) {
		window.document.getElementById("cat_5_" + btype[k]).checked="true";
	}

	var btype=strexp[20].split(","); //building types (stroitelstvo)
	for (var k = 0; k < btype.length-1; k++) {
		$("#bt" + btype[k]).attr("checked","true");
	}
	
	//LoadExtras('/search/load_extras/extras','extras',this.value);
	//LoadAreas_Ajax('/ajaxtabs/loadajax/','?type=kvartal1&save_search=' + strexp[19],'kvartal1',strexp[1]);
    //LoadAreas_AjaxS('/ajaxtabs/loadajax/', '?type=kvartal5&save_search=' + strexp[19],'kvartal2',strexp[1]);
    

	
	var area_ids_arr = strexp[27].split(",");
	var area_ids = '';
	for (var i = 0; i < area_ids_arr.length; i++){
		
		area_ids += '&area_ids[]=' + area_ids_arr[i];
		
	}
	
	var sub_area_ids_arr = strexp[28].split(",");

	var sub_area_ids = '';
	for (var i = 0; i < sub_area_ids_arr.length; i++){
		
		sub_area_ids += '&sub_area_ids[]=' + sub_area_ids_arr[i];
		
	}
	
	LoadExtras('/search/load_extras/extras/&?type=extras&save_search=' + strexp[10], 'extras');
	SubmitAjax('/ajaxtabs/loadajax/', '?&type=kvartal1&save_search=' + strexp[19] + '&s_id=' + strexp[1] + '&ar_part_ids=' + strexp[27], 'kvartal1');
	SubmitAjax('/ajaxtabs/loadajax/', '?&type=kvartal5&save_search=' + strexp[19] + '&s_id=' + strexp[1] + area_ids + sub_area_ids, 'kvartal2');
	
	var new_building_months_arr = strexp[29].split(",");

	for (var i = 0; i < new_building_months_arr.length; i++){
		if(window.document.getElementById("cat_3_" + new_building_months_arr[i])){
			window.document.getElementById("cat_3_" + new_building_months_arr[i]).checked = "true";
		}
	}
	
//	var area_ids = '';
//	for (var i = 0; i < area_ids_arr.length; i++){
//		
//		area_ids += '&area_ids[]='+area_ids_arr[i];
//		
//	}
//	
//    LoadExtras('/search/load_extras/extras/&?type=extras&save_search=' + strexp[10],'extras');
//    SubmitAjax('/ajaxtabs/loadajax/','?&type=kvartal1&save_search=' + strexp[19]+'&s_id=' + strexp[1]+'&ar_part_ids='+strexp[27],'kvartal1'); 
//	SubmitAjax('/ajaxtabs/loadajax/','?&type=kvartal5&save_search=' + strexp[19]+'&s_id=' + strexp[1]+'&area_ids='+area_ids,'kvartal2');
	
	if(strexp[14] == "all"){
		window.document.getElementById("ads_type_all").checked = "true";
	}
	if(strexp[14] == "private"){
		window.document.getElementById("ads_type_private").checked = "true";
	}
	
	if (strexp[17]>0) { window.document.f.floor_from.options[strexp[17]].selected="true"; } //floor_from
	if (strexp[16]>0) { window.document.f.floor_to.options[strexp[16]-1].selected="true"; } // floor_to
	if (strexp[15]>0) { window.document.f.order[strexp[15]-1].checked="true"; } //order
	window.document.f.price_from.value=strexp[3]; // price_from
	window.document.f.price_to.value=strexp[4]; // price_to
	for (var i = 0; i < document.f.currency_id.length; i++) {
		if (document.f.currency_id.options[i].value==strexp[5]) 	window.document.f.currency_id.options[i].selected="true"; // currency_id
	}
	for (var i = 0; i < document.f.currency_kv_id.length; i++) {
		if (document.f.currency_kv_id.options[i].value==strexp[6]) 	window.document.f.currency_kv_id.options[i].selected="true"; // currency_kv_id
	}
	window.document.f.surface_from.value=strexp[7]; // surface_from
	window.document.f.surface_to.value=strexp[8]; // surface_to
	window.document.f.price_kv_from.value=strexp[25];
	window.document.f.price_kv_to.value=strexp[26];
	
	 // keyword - ve4e stava s ajax
	window.document.f.nomer.value=strexp[13]; // nomer
	window.document.f.ad_type_id.value=strexp[0]; // nomer
	if (strexp[22]=="on") window.document.f.withpics.checked="true"; //withpics

	if (document.getElementById("new_building_yes") != null) {
	if (strexp[23]=="on") window.document.f.new_building_yes.checked="true"; else  window.document.f.new_building_yes.checked="";//new_building_yes
	}
	
	//
	//if (strexp[24]) {
	//	for (var i = 0; i < document.f.new_building_month.length; i++) {
	//		if (document.f.new_building_month.options[i].value==strexp[24]) window.document.f.new_building_month.options[i].selected="true"; // new_building_month
	//	}
	//}

}

function Get_Cookie_Serialize(cookie_name) {
	 $.getJSON("/load_save_search.php", 
	 			{ cookie_name: cookie_name }, 
	 	function(json){
	 			
	 			$("#city_id").val(json.c_id);
	 			
	 			if (json.building_type_id) {
	 				$.each(json.building_type_id, function() {
	 					$("#cat_5_" + this).attr("checked", "checked");
	 				});
	 			}
	 			
	 			
	 			
   				$("#price_from").val(json.p_fr); // no id
   				$('#price_to').val(json.p_to); // no id
   				$("#price_kv_from").val(json.p_kv_fr); // no id
   				$('#price_kv_to').val(json.p_kv_to); // no id
   				$("#currency_id").val(json.cur_id); // no id
   				$('#cur_kv_id').val(json.cur_kv_id); // no id
   				
   				$("#floor_from").val(json.f_fr); 
   				$('#floor_to').val(json.f_to);
   				
   				$("#surface_from").val(json.s_fr); 
   				$('#surface_to').val(json.s_to);
   				
   				$('#keyword').val(json.kw);
	 			$('#nomer').val(json.nomer);
   				
	 			$('#order_'+ json.order).attr("checked", "checked");
	 			
	 			if (json.con_id) {
   					$.each(json.con_id, function() {
	 					$("#bt" + this).attr("checked", "checked");
	 				});
	 			}
	 			
	 		
	 			if (json.nb_y == "on") {
	 				$("#new_building_yes").attr("checked", "checked");
	 			} else {
	 				$("#new_building_yes").attr("checked", "");
	 			}
	 			
	 			//samo sus snimka
	 			if (json.wp == "on") {
	 				$("#withpics").attr("checked", "checked");
	 			} else {
	 				$("#withpics").attr("checked", "");
	 			}
	 			
	 			//vidove oferti
	 			if (json.ads_type == 'private') {
	 				$("#ads_type_private").attr("checked", "checked");
	 				$("#ads_type_all").attr("checked", "");
	 			} else {
	 				$("#ads_type_private").attr("checked", "");
	 				$("#ads_type_all").attr("checked", "checked");
	 			}
	 			
	 			var extras_str = '';
	 			if (json.extras) {
	 				$.each(json.extras, function() {
	 					extras_str = extras_str + this + ",";
	 				});
	 			}
	 			
	 			//za lognati agencii
	 			if (json.work_with_agencies == 'on') {
	 				$("#work_with_agencies").attr("checked", "checked");
	 			}else{
	 				$("#work_with_agencies").attr("checked", "");
	 			}
	 			if (json.unpayed_agencies == 'on') {
	 				$("#unpayed_agencies").attr("checked", "checked");
	 			}else{
	 				$("#work_with_agencies").attr("checked", "");
	 			}
	 			
	 			var areas_str = '';
	 			if (json.a_id) {
	 				$.each(json.a_id, function() {
	 					areas_str = areas_str + this + ",";
	 				});
	 			}
	 			
	 			
	 			if (json.script == 'ns') {
	 				if (json.nb_m) {
	 					$.each(json.nb_m, function() {
	 						$("#cat_3_" + this).attr("checked", "checked");
	 					});
	 				}
	 			}
	 			
	 			LoadExtras('/search/load_extras/extras/&?type=extras&save_search=' + extras_str, 'extras');
	 			SubmitAjax('/ajaxtabs/loadajax/', '?&type=kvartal1&s_id=' + json.c_id, 'kvartal1');
	 			SubmitAjax('/ajaxtabs/loadajax/', '?&type=kvartal5&save_search=' + areas_str + '&s_id=' + json.c_id, 'kvartal2');
	
	
	 			
	 });
	
}


function sleep_mydear(milliseconds) {
	  var start = new Date().getTime();
	  for (var i = 0; i < 1e7; i++) {
	    if ((new Date().getTime() - start) > milliseconds){
	      break;
	    }
	  }
	}


function Get_Cookie_Serialize_Map(cookie_name) {
	 $.getJSON("/load_save_search.php", 
	 			{ cookie_name: cookie_name }, 
	 	function(json){
	 			
	 			$("#city_id").val(json.c_id);
	 			
	 			if (json.building_type_id) {
	 				$.each(json.building_type_id, function() {
	 					$("#cat_5_" + this).attr("checked", "checked");
	 				});
	 			}
	 			
	 			
	 			
  				$("#price_from").val(json.p_fr); // no id
  				$('#price_to').val(json.p_to); // no id
  				$("#price_kv_from").val(json.p_kv_fr); // no id
  				$('#price_kv_to').val(json.p_kv_to); // no id
  				$("#currency_id").val(json.cur_id); // no id
  				$('#cur_kv_id').val(json.cur_kv_id); // no id
  				
  				$("#floor_from").val(json.f_fr); 
  				$('#floor_to').val(json.f_to);
  				
  				$("#surface_from").val(json.s_fr); 
  				$('#surface_to').val(json.s_to);
  				
  				$('#keyword').val(json.kw);
	 			$('#nomer').val(json.nomer);
  				
	 			$('#order_'+ json.order).attr("checked", "checked");
	 			
	 			if (json.con_id) {
  					$.each(json.con_id, function() {
	 					$("#bt" + this).attr("checked", "checked");
	 				});
	 			}
	 			
	 			//za podrajoni na centar
	 			if(json.sba_id){	 				
	 				 $.ajax({	
	 			        type: "GET",        
	 			        url: '/ajaxtabs/loadajax_with_map/&type=kvartal4&s_id=ar_9&checked_ch=true',
	 			        cache: false,
	 			        success: function(html){
	 			        	string=html.split("[DELIMITER]");
	 			        	$("#kvartal4").html(string[0]);
	 			        	$.each(json.sba_id, function() {
	 		 					$("#subar_" + this).attr("checked", "checked");
	 		 				});	 			        
	 			        }      
	 			     });	 					 				
	 			}	
	 			
	 			
	 			
	 			if (json.nb_y == "on") {
	 				$("#new_building_yes").attr("checked", "checked");
	 			} else {
	 				$("#new_building_yes").attr("checked", "");
	 			}
	 			
	 			//samo sus snimka
	 			if (json.wp == "on") {
	 				$("#withpics").attr("checked", "checked");
	 			} else {
	 				$("#withpics").attr("checked", "");
	 			}
	 			
	 			//vidove oferti
	 			if (json.ads_type == 'private') {
	 				$("#ads_type_private").attr("checked", "checked");
	 				$("#ads_type_all").attr("checked", "");
	 			} else {
	 				$("#ads_type_private").attr("checked", "");
	 				$("#ads_type_all").attr("checked", "checked");
	 			}
	 			
	 			var extras_str = '';
	 			if (json.extras) {
	 				$.each(json.extras, function() {
	 					extras_str = extras_str + this + ",";
	 				});
	 			}
	 			
	 			//za lognati agencii
	 			if (json.work_with_agencies == 'on') {
	 				$("#work_with_agencies").attr("checked", "checked");
	 			}else{
	 				$("#work_with_agencies").attr("checked", "");
	 			}
	 			if (json.unpayed_agencies == 'on') {
	 				$("#unpayed_agencies").attr("checked", "checked");
	 			}else{
	 				$("#work_with_agencies").attr("checked", "");
	 			}
	 			
	 			var areas_str = '';
	 			if (json.a_id) {
	 				$.each(json.a_id, function() {
	 					areas_str = areas_str + this + ",";
	 				});
	 			}
	 			
	 			
	 			
	 			
	 			
	 			if (json.script == 'ns') {
	 				if (json.nb_m) {
	 					$.each(json.nb_m, function() {
	 						$("#cat_3_" + this).attr("checked", "checked");
	 					});
	 				}
	 			}
	 			
	 			LoadExtras('/search/load_extras/extras/&?type=extras&save_search=' + extras_str, 'extras');
	 			
	 				ChangeMap2(json.c_id, json.a_id);	 	
	 			
	 			
	 			
	 				SubmitAjax('/ajaxtabs/loadajax_with_map/','?&type=kvartal1&s_id='+json.c_id,'kvartal1');
	 				$('#loading2').show(); 
	 				$('#loading').show(); 
	 				SubmitAjax2('/ajaxtabs/loadajax_with_map/','?&type=kvartalJoro&save_search=' + areas_str + '&s_id=' +json.c_id,'kvartal2'); 	 			
	 				
	 				SubmitAjax('/ajaxtabs/load_ajaxfilter/', '?type=load&c_id='+json.c_id, 'ajax_filter_city_pole');		 		
	 });
	
}







function Redirect_Cookie(link,url,param) {
document.location = link + url + "/?cookie_value=" + param;
}


function DecodeUtf8(utftext) {
        var string = "";
        var i = 0;
        var c = c1 = c2 = 0;

        while ( i < utftext.length ) {

            c = utftext.charCodeAt(i);

            if (c < 128) {
                string += String.fromCharCode(c);
                i++;
            }
            else if((c > 191) && (c < 224)) {
                c2 = utftext.charCodeAt(i+1);
                string += String.fromCharCode(((c & 31) << 6) | (c2 & 63));
                i += 2;
            }
            else {
                c2 = utftext.charCodeAt(i+1);
                c3 = utftext.charCodeAt(i+2);
                string += String.fromCharCode(((c & 15) << 12) | ((c2 & 63) << 6) | (c3 & 63));
                i += 3;
            }

        }

        return string;
    }

    
function Print(url,type,txt) {
 req = false;
 var city="?city=";
 pole_input=txt;
 pole=type;
 txt=document.getElementById(txt).value;
 for(i=0;i< txt.length; i++) {
 city=city + txt.charCodeAt(i) + "-";
 }
 url=url + city;
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
 window.document.getElementById("building_type_id").style.visibility = 'hidden';
 if (req) {
 req.onreadystatechange=processReqChange2;
 req.open("GET", url, true);
 req.send(null);
 }

}

function processReqChange2()
{

	if (req.readyState == 4)
	{
		if (req.status == 200)
		{
			window.document.getElementById(pole).innerHTML = req.responseText;
			window.document.getElementById(pole).style.display = 'block';
		}
	}
}


function div_mouseout(div_id) {
	window.document.getElementById(div_id).style.display = 'none';
	window.document.getElementById("building_type_id").style.visibility = 'visible';
}


function SetCity(city_id,city,area_id,sub_area_id) {
 window.document.getElementById("city_input").value = city;
 window.document.getElementById("city_id").value = city_id;
 if (area_id) {
	 window.document.getElementById("area_id").value = area_id;
 }
  if (sub_area_id) {
	 window.document.getElementById("sub_area_id").value = sub_area_id;
 }
}

function write_tab_selected(name){
	document.cookie = "tab_selected=" + name;
	//alert(document.cookie.tab_selected);
	
}

function SortSelectMenu() {
    var value = $('#SelectMenu').val();
    
    document.location.href='?'+value;
    
}


