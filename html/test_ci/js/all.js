var IMGS = null;
var YTPLR=null;
var DB=null;
function initMaps(container) {
	if(typeof(container)=="string") container=document.getElementById(container);
	var center=new google.maps.LatLng(-34.397, 150.644);
	var zoom=10;
	var mapOptions = {
		center: center, 
		zoom: zoom
	};
	var map = new google.maps.Map(document.getElementById(container),
	mapOptions);
	var marker=new google.maps.Marker({
		position: center,
		map: map,
		draggable: true,
		title: "Drag me to the offer location",
	});
	google.maps.event.addListener(marker, 'dragend', function(e){
		var gCoder = new google.maps.Geocoder();
		gCoder.geocode({'latLng':e.latLng}, function(r, s){
			if(s=='OK')
			{
				var str = r[0].formatted_address;
				if(str.length>20) str=str.substring(0,17)+'...';
				$('#ofrLocTxt').text(str);
			}
			console.log(s);
			console.log(r);
		});
		console.log(e);
	});
}

function loadScript(url, callback)
{
    // Adding the script tag to the head as suggested before
    var head = document.getElementsByTagName('head')[0];
    var script = document.createElement('script');
    script.type = 'text/javascript';
    script.src = url;

    // Then bind the event to the callback function.
    // There are several events for cross browser compatibility.
    script.onreadystatechange = callback;
    script.onload = callback;

    // Fire the loading
    head.appendChild(script);
};

var offerEl=function(props)
{
	var myhtml = ['',
'<div class="offer">',
'	<div class="imageWrap">',
'		<img src="'+props['image']+'" alt="image" height="180" width="180">',
'	</div>',
'	<div class="ofrAttrs">',
'		<div class="ofrLoc">',
'			<div>'+props['type']+': '+props['loc']+'</div>',
'		</div>',
'		<div class="ofrBrief">',
'			<p>'+props['brief']+'',
'			</p>',
'		</div>',
'		<div class="ofrProps">',
'			<div class="ofrBottom">',
'				<div class="ofrBottomProps">',
'					<div class="ofrCurrentProp"><b>$</b>'+props['price']+'</div>',
'					',
'					<div class="ofrCurrentProp">'+props['area']+'m<sup>;2</sup>;</div>',
'				</div>',
'				<div class="ofrAgencyLogo">',
'					<img src="http://imoti.net/agency/sp_agency/?id='+props['agency']+'" alt="company" height="50" width="150">',
'				</div>',
'			</div>',
'		</div>',
'	</div>',
'</div>',
''].join(' ');
	this.el=document.createElement('div');
	$(this.el).html(myhtml);
	this.props=props;
};

//google.maps.event.addDomListener(window, 'load', initiMaps);

	var photoImgEl = function(url, type)
	{
		"use strict"
		this.url=url;
		this.type=type;
		this.visible=false;

		this.dscrEl=document.createElement('input');
		$(this.dscrEl).attr("type", "text").val("image description").focus(function(){
			if($(this).val()=="image description")
			{
				$(this).val("").removeClass("imgAddTextDefDescr");
			};
		}).addClass("imgAddTextDefDescr").addClass("imgAddTextDescr");;
		//$();
		
		this.imgEl = new Image();
		this.imgEl.src = url;
				
		this.indEl=document.createElement('div');
		this.indEl.innerHTML="Not in front";
		$(this.indEl).attr("class", "ofrBoxImgNotInd ofrBoxImgIndEl");

		this.delEl=document.createElement('div');
		$(this.delEl).addClass('ofrBoxImgDel');
		this.delEl.innerHTML=" ";
		
		this.tEl=document.createElement('select');
		$(this.tEl).addClass('ofrBoxImgType');
		var optLbl="";
		for(var i=1;i<4;i++)
		{
			if(i==1) optLbl="Interior"
			else if(i==2) optLbl="Exterior"
			else if(i==3) optLbl="Scheme"
			
			var option = document.createElement("option");
			option.setAttribute("value", i+'');
			option.innerHTML = optLbl;
			if(i==type) option.setAttribute('selected', 1);
			this.tEl.appendChild(option);
		};
		
		this.wrapEl = document.createElement('div');
		$(this.wrapEl).addClass('ofrBoxImg');
		//this.wrapEl.appendChild(this.indEl);
		this.wrapEl.appendChild(this.dscrEl);
		this.wrapEl.appendChild(this.delEl);
		this.wrapEl.appendChild(document.createElement('br'));
		this.wrapEl.appendChild(this.imgEl);
		this.wrapEl.appendChild(document.createElement('br'));
		this.wrapEl.appendChild(this.tEl);
		
		this.show = function(cntEl)
		{
			if(!this.visible) cntEl.appendChild(this.wrapEl);
			this.visible = true;
		};
		
		this.hide = function(cntEl)
		{
			if(this.visible)
			{
				this.wrapEl.remove();
			};
			this.visible = false;
		};
		
		this.del = function()
		{
			this.hide();
			delete this.imgEl;
			delete this.wrapEl;
			delete this.url;
			delete this.type;
			this.show=function(){};
			this.hide=function(){};
		};

		this.setInd=function(isInd)
		{
			//$(this.indEl).attr("class", (isInd?"ofrBoxImgIndex":"ofrBoxImgNotInd")+" ofrBoxImgIndEl");
			//this.indEl.innerHTML=isInd?"Front":"Not front"
			if(isInd) $(this.wrapEl).addClass("chosenImage")
			else $(this.wrapEl).removeClass("chosenImage");
		};
	};

	imgArr = function(args)
	{
		"use strict"
		if(args==undefined)
		{
			args={};
		};
		this.imgs = new Array();
		this.indexId = null;
		this.showType=0;
		this.cntId='imgBoxContainer_0';
		this.dspList=true;
		this.cntEl=document.createElement('div');
		$(this.cntEl).attr('class', 'imgBoxContainerCl');
		this.wrapIn=$((typeof args.wrapIn!="string")?'body':args.wrapIn);

		this.addImg = function(url, type)
		{
			var il = this.imgs.length;
			this.imgs.push(new photoImgEl(url, type));
			if(this.showType==type||this.showType==0) this.imgs[il].show(this.cntEl);
			$(this.imgs[this.imgs.length-1].delEl).click({id: this.imgs.length-1, obj: this},function(dta){
				dta.data.obj.removeImg(dta.data.id);
			});
			//*/
			$(this.imgs[il].imgEl).click({imgar: this, id: il}, function(dta)
			{
				dta.data.imgar.chIndex(dta.data.id);
			});
			//*/
			$(this.imgs[il].tEl).change({imgar: this, id: il}, function(dta){
				var inp=dta.data;
				var val=parseInt(this.value);
				inp.imgar.imgs[inp.id].type=this.value;
				if(inp.imgar.showType!=val&&inp.imgar.showType!=0) inp.imgar.imgs[inp.id].hide();
			});

			if(this.imgs.length==1)
			{
				this.indexId=0;
				this.chIndex(0);
				this.wrapIn.append(this.cntEl);
			};
			return this.imgs.length;
		};
		
		this.removeImg = function(id)
		{
			this.imgs[id].del();
			if(id==this.indexId)
			{
				this.chIndex(0);
			};
		};
		
		//change display type
		//0=all types
		this.chDspType = function(to)
		{
			if(this.showType == to) return;
			for(var i=0; i<this.imgs.length; i++)
			{
				if(this.imgs[i].type==to||to==0) this.imgs[i].show(this.cntEl);
				else this.imgs[i].hide(this.cntEl);
			};
			this.showType = to;
		};
		
		this.chIndex = function(indId)
		{
			this.imgs[this.indexId].setInd(false);
			this.indexId = parseInt(indId);
			$('#addOfrFrontImg').attr("src", this.imgs[indId].url);
			this.imgs[this.indexId].setInd(true);
		};

	}
	


function sendQuery(url, data, callback)
{
	$.ajax({
        	url: url,
        	type: "POST",
        	data: data,
        	dataType: "json",
        	success: function (result) {
			callback(result);
        	},
        	error: function (xhr, ajaxOptions, thrownError) {
        		alert(xhr.status);
        		alert(thrownError);
        	}
	});
};

function loadPage(url)
{
		if(PAGES_CACHE[url]!=undefined)
		{
			$("#pbody").html(PAGES_CACHE[url]);
			return;
		};
		var href=url;
		var hrefArr=href.split("/");
		var ctr=hrefArr[2];
		var fn=hrefArr[3];
		function cb(r)
		{
			$("#pbody").html(r.str);
			rdy($("#pbody"));
			PAGES_CACHE[url]=r.str;
		};
		sendQuery(href, {json: true}, cb);
		history.pushState({}, "title", url);
};

var THIS_PAGE=
{
	url: 'blabla',
	element: null
};
var KNOWN_PAGES=new Array();
KNOWN_PAGES["faq"]=
{
	attach: function(){
		$(THIS_PAGE.element).remove();
		
	}
	//var data=;
};
var PAGES_CACHE=new Array();
var BASE_URL="/test_ci/";

var GL_SSOCKET;

function faqEdit(){
		//($(this).parent().parent().parent().attr("id"));
		var elem=$(this).parent().parent().parent();
		var elHead=elem.find(".faqAnswerHead>div");
		var elText=elem.find(".faqAnswerContent");
		var headVal=elHead.html();
		var textVal=elText.html();
		var txtWidth=elText.width();
		var txtHeight=elText.height();
		$(elHead).html("<input type='text' value='"+headVal+"'></input>");
		$(elText).html("<textarea style='width:"+txtWidth+";height:"+txtHeight+"' >"+textVal+"</textarea><br><input type=\"button\" value=\"done\" style=\"width:100%\"></input>");
		$(elText).find('input').click({txt: $(elText), head: $(elHead)}, function(e){
			var txt=e.data.txt.find('textarea').val();
			var head=e.data.head.find('input').val();
			//do some with the text and the heading
			e.data.txt.html(txt);
			e.data.head.html(head);
		});
	}


function faqMvUp(){
		//($(this).parent().parent().parent().attr("id"));
		var mdId=$(this).parent().parent().parent().attr("id");
		var mdIdNext=$(this).parent().parent().parent().prev().attr("id");
		if(mdIdNext=="faqContents")return;//TODO: handle
		var params=
		{
			action: "move",
			direction: "up",
			mdId: mdId
		};
		var cb=function(rData)
		{
			//$("#faqs").html(rData.faqs);
			//window.location.reload();
			if(!rData.error)
			{
				//alert (rData.query);
				var thisQuestion=$("#"+mdId);
				var tMdId=thisQuestion.attr("id");
				var nextQuestion=$("#"+mdIdNext);
				var nMdId=nextQuestion.attr("id");
				var tmpHTML=thisQuestion.html();

				thisQuestion.html(nextQuestion.html()).attr("id", nMdId);
				nextQuestion.html(tmpHTML).attr("id", tMdId);
				thisQuestion.find('.moveUpFAQ').click(faqMvUp);
				nextQuestion.find('.moveUpFAQ').click(faqMvUp);
				thisQuestion.find('.moveDownFAQ').click(faqMvDown);
				nextQuestion.find('.moveDownFAQ').click(faqMvDown);
			};
		};
		sendQuery('/test_ci/query/faq', params, cb);
	};



function faqMvDown(){
		//($(this).parent().parent().parent().attr("id"));
		var mdId=$(this).parent().parent().parent().attr("id");
		var mdIdNext=$(this).parent().parent().parent().next().attr("id");
		if($("#"+mdIdNext).attr("class")!="faqAnswer")return;//TODO: handle
		var params=
		{
			action: "move",
			direction: "down",
			mdId: mdId
		};
		var cb=function(rData)
		{
			//$("#faqs").html(rData.faqs);
			//window.location.reload();
			if(!rData.error)
			{
				//alert (rData.query);
				var thisQuestion=$("#"+mdId);
				var tMdId=thisQuestion.attr("id");
				var nextQuestion=$("#"+mdIdNext);
				var nMdId=nextQuestion.attr("id");
				var tmpHTML=thisQuestion.html();

				thisQuestion.html(nextQuestion.html()).attr("id", nMdId);
				nextQuestion.html(tmpHTML).attr("id", tMdId);
				thisQuestion.find('.moveUpFAQ').click(faqMvUp);
				nextQuestion.find('.moveUpFAQ').click(faqMvUp);
				thisQuestion.find('.moveDownFAQ').click(faqMvDown);
				nextQuestion.find('.moveDownFAQ').click(faqMvDown);
			};
		};
		sendQuery('/test_ci/query/faq', params, cb);
	};


function faqDel(){
		//($(this).parent().parent().parent().attr("id"));
		var mdId=$(this).parent().parent().parent().attr("id");
		var params=
		{
			action: "delete",
			mdId: mdId
		};
		var cb=function(rData)
		{
			//$("#faqs").html(rData.faqs);
			//window.location.reload();
			if(!rData.error)
			{
				//alert (rData.query);
				var thisQuestion=$("#"+mdId);
				thisQuestion.remove();
			};
		};
		sendQuery('/test_ci/query/faq', params, cb);
	};

function adminFaqCache(elem)
{
	if(elem==undefined) elem=$("body");
	$(elem.find('.moveUpFAQ')).click(faqMvUp);
	$(elem.find('.moveDownFAQ')).click(faqMvDown);
	$(elem.find('.removeFAQ')).click(faqDel);
	$(elem.find('.editFAQ')).click(faqEdit);

	PAGES_CACHE[window.location.pathname]=$("#pbody").html();

	//window.addEventListener("popstate", function(e) {
	//	loadPage(window.location.pathname);
	//});
	
	var link=elem.find('a');
	$.each(link, function(key, val){
		//console.log(link[key]);
		$(link[key]).click(function(e)
		{
			//e.preventDefault();
			//var href=$(this).attr("href");
			//loadPage(href);
		});
});



	//var arr1=Array(2);
	//arr1[0]="hi";
	//arr1[1]="hihi";
	//jQuery.getJSON("/test_ci/query/faq", arr1, function(data){
		//console.log(data);
	//});
}

$(document).ready(adminFaqCache);
function onYouTubePlayerReady(playerId) {
	YTPLR = document.getElementById("addVidWrapper");
	//console.log(YTPLR.loadVideoById)
	//alert('ld');
};

var stmt;
function initSQL()
{
	DBCTRL.DB = new SQL.Database();
	var init=$.ajax({url: '/estate2.sql'}).done(function(e){
		DBCTRL.DB.run(e);
		//stmt = DB.prepare("SELECT * FROM agencies WHERE 1");
	});
	//console.log(init);
}

var DBCTRL=
{
	getResults: function(opts){
		switch(this.controller)
		{
			case "SQL":
				var q=["select * from offers where",
					" price > "+FILTERS.price.min+" and price < "+FILTERS.price.max,
					" and ",
					" area > "+FILTERS.area.min+" and area < "+FILTERS.area.max,
					" limit 10"
					].join("");
				var res=this.DB.exec(q)[0];
				var retVal = new Array();
				for(var i=0;i<res.values.length;i++)
				{
					var val=res.values[i];
					var props={agency: val[3], price: val[1], area: val[2], loc: 'hello', image: val[4], type: '', brief: 'brief description'};
					props.loc=val[6];
					var tType;
					switch(val[5])
					{
						case 1: tType='Апартамент'; break;
						case 2: tType='Магазин'; break;
						case 3: tType='Гараж'; break;
						case 4: tType='Парцел'; break;
						case 5: tType='Къща'; break;
						default: tType='Имот'; break;
					};
					props.type=tType;
					retVal.push(props);
				};
				console.log(retVal);
				return retVal;
			break;
			case "NoSQL":
				var tbl = new Array();
				function srt(desc,key) {
					return function(a,b){
						return desc ? ~~(key ? a[key]<b[key] : a < b) 
							: ~~(key ? a[key] > b[key] : a > b);
					};
				};
				var len=this.DB.length;
					var props={agency: 1, price: 1, area: 2, loc: 'hello', image: 4, type: '', brief: 'brief description'};
				var ofrCount=0;
				for(var i=0;i<len;i++)
				{
					if(
						FILTERS.price.min > this.DB[i][1]||
						FILTERS.price.max < this.DB[i][1]||
						FILTERS.area.min > this.DB[i][2]||
						FILTERS.area.max < this.DB[i][2]
					)
					{
						continue;
					}
					else
					{
						ofrCount++;
						tbl.push({
							price: this.DB[i][1],
							area: this.DB[i][2],
							agency: this.DB[i][3],
							image: this.DB[i][4],
							type: this.DB[i][5],
							loc: this.DB[i][6]+", "+this.DB[i][7],
							brief: "brief here"
						});
					};
				};
				var sorted="price";
				if(FILTERS.order==1) sorted = "area";
				else if(FILTERS.order==2) sorted = "agency";
				var page = 0;
				var perPage = 10;
				if(opts){
				if(opts.nopages==true)
				{
					page=0;
					perPage=ofrCount;
				}};
				var sliceBegin=page*perPage;
				return tbl.sort(srt(null, sorted)).slice(sliceBegin, sliceBegin+perPage);
			break;
		};
	},
	init: function(strController)
	{
		switch(strController)
		{
			case "SQL":
				loadScript("js/sql.js", initSQL);
				this.controller = "SQL";
			break;
			case "NoSQL":
				initNoSQL();
				this.controller = "NoSQL";
			break;
		};
	},
	DB: null,
	controller: "SQL"
};

function displayResults(results)
{
	var mainEl=$('#browseList');
	mainEl.html('');
	var fltrsOrder=["",
"<select id=\"fOrderSelect\">",
"	<option value=\"0\" "+((FILTERS.order==0)?"selected":"")+">Цена</option>",
"	<option value=\"1\" "+((FILTERS.order==1)?"selected":"")+">Площ</option>",
"	<option value=\"2\" "+((FILTERS.order==2)?"selected":"")+">Агенция</option>",
"	<option value=\"3\" "+((FILTERS.order==3)?"selected":"")+">BASIC</option>",
"</select>"].join("");
	var order=document.createElement('div');
	$(order).html(fltrsOrder);
	mainEl.append(order);
	$(order).change(function(e){
		FILTERS.order = parseInt(e.target.value);
		displayResults(DBCTRL.getResults());
		//updateFilters();
	});
	var len=results.length;
	for(var i=0;i<len;i++)
	{
		var elem=new offerEl(results[i]);
		mainEl.append(elem.el);
	};
};

function initNoSQL()
{
	jQuery.getJSON("/test_ci/query/nosql").done(function(e){
		DBCTRL.DB=e.db;
	}).fail(function(e){console.log(this)});
}

var GoStore = null;
function initIndexedDB()
{
	var request = indexedDB.open("library", 2);

	request.onupgradeneeded = function()
	{
		// The database did not previously exist, so create object stores and indexes.
		var db = request.result;
		var store = db.createObjectStore("offers", {autoIncrement: true});
		var titleIndex = store.createIndex("price", "price");
		var authorIndex = store.createIndex("area", "area");
		var authorIndex = store.createIndex("frontPhotoId", "frontPhotoId");
		var authorIndex = store.createIndex("lat", "lat");
		var authorIndex = store.createIndex("lng", "lng");

		// Populate with initial data.
		store.put({price: 2222, area: 55, frontPhotoId: 56, lat: 56.7, lng: 68.6});
	};

	request.onsuccess = function()
	{
		DB = request.result;
	};
	

}

function ssss(){
	var tx = DB.transaction("offers", "readonly");
	var store = tx.objectStore("offers");
	var index = store.index("price");

	var request = index.get(2222);
	request.onsuccess = function() {
		var matching = request.result;
		if (matching !== undefined)
		{
			// A match was found.
			console.log(matching.price, matching.lat, matching.lng);
		}
		else
		{
			// No match was found.
			console.log(null);
		}
	};
};

function addOfferRdy()
{
	$('#addVidTxtBoxId').focusin(function(){
		if(typeof this.cleared=="undefined")
		{
			$(this).val('');
			$(this).removeClass('grayBoldText');
			this.cleared = true;
		}
		else 
		{
			$(this).select();
		};
	});
	$('.ofrBrief>textarea').focusin(function(){
		if(typeof $(this).cleared=="undefined")
		{
			$(this).text('');
			$(this).removeClass('grayBoldText');
			$(this).cleared = true;
		};
	});
	$('.addVidControls>form').submit(function(e){
		e.preventDefault();
		var video_id=$('#addVidTxtBoxId').val().split('v=')[1];
		var ampersandPosition = video_id.indexOf('&');
		if(ampersandPosition != -1) {
			video_id = video_id.substring(0, ampersandPosition);
		};
		if(YTPLR==null)
		{
			var url="http://www.youtube.com/v/" +
				video_id +
				"?enablejsapi=1&playerapiid=ytplayer&version=3";
			swfobject.embedSWF(
				url,
				"addVidWrapper",
				"680",
				"420",
				"8",
				null,
				null,
				{allowScriptAccess: "always"},
				{id: "addVidWrapper"}
			);
			YTPLR=document.getElementById('addVidWrapper');
		};
		YTPLR.cueVideoById(video_id);
	});
	IMGS = new imgArr({wrapIn: '#imgsin'});
	//IMGS.addImg("http://img01.imovelweb.com.br//logos/755474_imovelweblogo.jpg", 1);
	//for(var i=0;i<15;i++) IMGS.addImg("https://i1.ytimg.com/vi/vzTsEJjFL0k/default.jpg", 1);
	$('#uplImgsId').change(function(){
		"use strict"
		for(var  i=0;i<this.files.length;i++)
		{
			if(!this.files[i].type.match(/image/g)) continue;
			var type=(this.files[i].type.match(/png|svg|gif/))?3:1;
			var oFReader = new FileReader();
			oFReader.readAsDataURL(this.files[i]);
			oFReader.fType=type;
			oFReader.onload = function (e) {
				IMGS.addImg(e.target.result, this.fType);
			};
		};
	});
	$("#addOfrMapCanvas").attr("style", "background: url(\"http://maps.googleapis.com/maps/api/staticmap?size=680x480&center=Brooklyn+Bridge,New+York,NY&zoom=13&size=600x300&maptype=roadmap&markers=color:blue|label:S|40.702147,-74.015794&markers=color:green|label:G|40.711614,-74.012318&markers=color:red|label:C|40.718217,-73.998284\"); height: 480px; width: 680px;");
	//initMaps();
}

var FILTERS=
{
	price:
	{
		min: 10,
		max: 100
	},
	area:
	{
		min: 10,
		max: 100
	},
	order: 1,
	page: 0
};

function filtersRdy()
{
	//*/
	var priceSlider=$('#fPriceSlider').slider({
		range: true,
		min: 0,
		max: 500000,
		values: [10000, 100000],
		change: function(e, s){
			FILTERS.price.min=s.values[0];
			FILTERS.price.max=s.values[1];
			displayResults(DBCTRL.getResults());
			//updateFilters();
		}
	});

	var areaSlider=$('#fAreaSlider').slider({
		range: true,
		min: 0,
		max: 500,
		values: [10, 500],
		change: function(e, s){
			FILTERS.area.min=s.values[0];
			FILTERS.area.max=s.values[1];
			//DBCTRL.getResults();
			displayResults(DBCTRL.getResults());
			//updateFilters();
		}
	});
	//*/	

	$(".ctrlFilter.spinner").spinner({
		min: 0,
		max: 1000000,
		step: 150,
		change: function(e, s)
		{
		//	e.preventDefault();
			//$(this).val(s.value);
			FILTERS.price.min=$("#priceLower").val();
			FILTERS.price.max=$("#priceHigher").val();
			FILTERS.area.min=$("#areaLower").val();
			FILTERS.area.max=$("#areaHigher").val();
			displayResults(DBCTRL.getResults());
		}
	});

	$(".frmBrowseTabs").buttonset();
};

var MAPEL=null;
var GMAP=null;
function initSearchMap()
{
	MAPEL=document.createElement('div');
	MAPEL.height=680;
	MAPEL.width=680;
	$("#browseList").html("");
	$("#browseList").append(MAPEL);
	$(MAPEL).attr("style", "border: 2px solid green; height: 680px; width: 680px");
	var center=new google.maps.LatLng(42.692177, 23.327026);
	var zoom=14;
	var mapOptions = {
		center: center, 
		zoom: zoom
	};
	GMAP=new google.maps.Map(MAPEL, mapOptions);
};

function displayMapResults()
{
	if(MAPEL==null) initSearchMap();
	var results=DBCTRL.getResults({nopages: true});
	console.log(results);
}

function updateFilters()
{
	var mainEl=$('#browseList');
	mainEl.html('');
	var fltrsOrder=["",
"<select id=\"fOrderSelect\">",
"	<option value=\"0\">Цена</option>",
"	<option value=\"1\">AppleScript</option>",
"	<option value=\"2\">Asp</option>",
"	<option value=\"3\">BASIC</option>",
"</select>"].join("");
	var order=document.createElement('div');
	$(order).html(fltrsOrder);
	mainEl.append(order);
	$(order).change(function(e){
		FILTERS.order = parseInt(e.target.value);
		updateFilters();
	});

	var q=["select * from offers where",
		" price > "+FILTERS.price.min+" and price < "+FILTERS.price.max,
		" and ",
		" area > "+FILTERS.area.min+" and area < "+FILTERS.area.max,
		" limit 10"
		].join("");
	console.log(q);
	var res=DB.exec(q)[0];
	$("#priceLower").val(FILTERS.price.min);
	$("#priceHigher").val(FILTERS.price.max);
	$("#areaLower").val(FILTERS.area.min);
	$("#areaHigher").val(FILTERS.area.max);
	for(var i=0;i<res.values.length;i++)
	{
		var val=res.values[i];
		var props={agency: 0, price: 0, area: 0, loc: 'hello', image: '', type: '', brief: 'brief description'};
		props.price=val[1];
		props.area=val[2];
		props.loc=val[6];
		props.image=val[4];
		props.agency=val[3];
		var tType;
		switch(val[5])
		{
			case 1: tType='Апартамент'; break;
			case 2: tType='Магазин'; break;
			case 3: tType='Гараж'; break;
			case 4: tType='Парцел'; break;
			case 5: tType='Къща'; break;
			default: tType='Имот'; break;
		};
		props.type=tType;
		var elem=new offerEl(props);
		mainEl.append(elem.el);
	};
}

function makeAnchorFromString(string)
{
	return string.replace(/[^a-zA-Z0-9]+/g, "-");
};

var PAGES=
{
	"faq":
	{
		load: function()
		{
			if(this.cache.qnas==null)
			{
				return $.getJSON("/test_ci/query/get_faq").done(function(data){
					var cache=PAGES['faq'].cache;
					var cntEl=$(document.createElement('div')).attr("id", "faqContents");
					cache.qnas=data;
					cache.wrapEl=$(document.createElement('div'))
						.attr("id", "faqs")
						.append($(document.createElement('h2')).html("Frequently asked questions:"))
						.append(document.createElement('br'))
						.append(document.createElement('br'))
						.append(cntEl);
					var qnaLen=data.length;
					for(var i=0; i<qnaLen;i++)
					{
						cntEl.append(
							$(document.createElement('a'))
								.attr("href", '#'+makeAnchorFromString(data[i].question))
								.addClass("faqQuestion", "hello")
								.html(data[i].question)
						).append(document.createElement('br'));
						var qna=$(document.createElement('div'))
							.addClass("faqAnswer")
							.attr("id", makeAnchorFromString(data[i].question))
							.append($(document.createElement('div')).addClass("faqAnswerHead").html(data[i].question))
							.append($(document.createElement('div')).addClass("faqAnswerContent").html(data[i].answer));
						cache.wrapEl.append(qna);
					};
				});
			};
		},
		display: function()
		{
			var pBodyEl=$("#pbody");
			if(PAGES['faq'].cache.qnas!=null) pBodyEl.html("").append(PAGES['faq'].cache.wrapEl);
			else this.load().then(this.display);
			//else $("#pbody").html("").append(this.cache.wrapEl);
			//$.when(true).then(this.load);
			//var qnaLen=this.cache.qnas.length;
		},
		cache:
		{
			qnas: null,
			wrapEl: null,
			cntEl: null,
			cnt: new Array()
		}
	},
	'browse':
	{
		load: function()
		{
			if(PAGES['browse'].cache.pg==null)
				$.getJSON("browse?json").done(function(data){
					PAGES['browse'].cache.pg=data;
					console.log("data");
					//console.log(data);
				}).fail(function(){alert()});
		},
		display: function()
		{
			if(PAGES['browse'].cache.pg!=null) $("#pbody").html(PAGES['browse'].cache.pg.str);
			else this.load().then(this.display);
		},
		cache:
		{
			pg: null
		}
	}
};
var db;
var resires;
$(document).ready(function(){
$.each(PAGES, function(i, val){
	//var page=$(this).attr("href").slice(BASE_URL.length);
	val.load();
});
$("a.knownPage").click(function(e){
	var page=$(this).attr("href").slice(BASE_URL.length);
	if(typeof PAGES[page]=="object")
	{
		e.preventDefault();
		history.pushState(null, null, $(this).attr("href"));
		PAGES[page].display();
		//PAGES[page].display();
	};
});
$('#priceLower').change(function(){
	var bound = (parseInt(this.value));
	var q="select * from offers where price > "+(bound|0)+" limit 10";
	var res=DB.exec(q)[0];
	resres=res;
	var mainEl=$('#browseList');
	mainEl.html('');
	for(var i=0;i<res.values.length;i++)
	{
		var val=res.values[i];
		var props={agency: 0, price: 0, area: 0, loc: 'hello', image: '', type: '', brief: 'brief description'};
		props.price=val[1];
		props.area=val[2];
		props.loc=val[6];
		props.image=val[4];
		props.agency=val[3];
		console.log(props);
		var tType;
		switch(val[5])
		{
			case 1: tType='Апартамент'; break;
			case 2: tType='Магазин'; break;
			case 3: tType='Гараж'; break;
			case 4: tType='Парцел'; break;
			case 5: tType='Къща'; break;
			default: tType='Имот'; break;
		};
		props.type=tType;
		var elem=new offerEl(props);
		mainEl.append(elem.el);
	};
	console.log(res);
});
$('#dbfile').change(function() {
	var f = this.files[0];
	var r = new FileReader();
	r.onload = function() {
	var Uints = new Uint8Array(r.result);
	DBCTRL.DB = new SQL.Database(Uints);
	}
	r.readAsArrayBuffer(f);
});
//filtersRdy();
addOfferRdy();
DBCTRL.init("NoSQL");
//initNoSQL();
});
