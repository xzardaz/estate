var IMGS = null;
var YTPLR=null;
function initMaps() {
	var center=new google.maps.LatLng(-34.397, 150.644);
	var zoom=10;
	var mapOptions = {
		center: center, 
		zoom: zoom
	};
	var map = new google.maps.Map(document.getElementById("map-canvas"),
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

//google.maps.event.addDomListener(window, 'load', initiMaps);

	var photoImgEl = function(url, type)
	{
		"use strict"
		this.url=url;
		this.type=type;
		this.visible=false;
		
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
		this.wrapEl.appendChild(this.indEl);
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
			$(this.indEl).attr("class", (isInd?"ofrBoxImgIndex":"ofrBoxImgNotInd")+" ofrBoxImgIndEl");
			this.indEl.innerHTML=isInd?"Front":"Not front"
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
			$(this.imgs[il].indEl).click({imgar: this, id: il}, function(dta)
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

var KNOWN_PAGES=new Array();
KNOWN_PAGES["faq"]=function()
{
	//var data=;
};
var PAGES_CACHE=new Array();
var BASE_URL="/localhost/test_ci/";

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
		elHead.html("<input type='text' value='"+headVal+"'></input>");
		elText.html("<textarea style='width:"+txtWidth+";height:"+txtHeight+"' >"+textVal+"</textarea>");
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
		alert("del");
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

function rdy(elem)
{
	if(elem==undefined) elem=$("html");
	//elem.find('.moveUpFAQ').click(faqMvUp);
	//elem.find('.moveDownFAQ').click(faqMvDown);
	//elem.find('.removeFAQ').click(faqDel);
	//elem.find('.editFAQ').click(faqEdit);

	PAGES_CACHE[window.location.pathname]=$("#pbody").html();

	window.addEventListener("popstate", function(e) {
		loadPage(window.location.pathname);
	});
	
	var link=elem.find('a');
	$.each(link, function(key, val){
		//console.log(link[key]);
		$(link[key]).click(function(e)
		{
			e.preventDefault();
			var href=$(this).attr("href");
			loadPage(href);
		});
});



	//var arr1=Array(2);
	//arr1[0]="hi";
	//arr1[1]="hihi";
	//jQuery.getJSON("/test_ci/query/faq", arr1, function(data){
		//console.log(data);
	//});
}

$(document).ready(rdy);
function onYouTubePlayerReady(playerId) {
	YTPLR = document.getElementById("addVidWrapper");
	//console.log(YTPLR.loadVideoById)
	//alert('ld');
};

function addOfferRdy()
{
	$('#addVidTxtBoxId').focusin(function(){
		$(this).removeClass('grayBoldText').val('');
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
	initMaps();
}

$(document).ready(function(){
	addOfferRdy();
});
