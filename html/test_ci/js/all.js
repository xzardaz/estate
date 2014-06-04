var IMGS = null;
function initMaps() {
	var mapOptions = {
	center: new google.maps.LatLng(-34.397, 150.644),
	zoom: 8
	};
	var map = new google.maps.Map(document.getElementById("map-canvas"),
	mapOptions);
}
//google.maps.event.addDomListener(window, 'load', initiMaps);

	var photoImgEl = function(url, type)
	{
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
		this.delEl.innerHTML="X";
		
		this.tEl=document.createElement('select');
		$(this.tEl).addClass('ofrBoxImgType');
		var optLbl="";
		for(var i=0;i<3;i++)
		{
			if(i==0) optLbl="Interior"
			else if(i==1) optLbl="Exterior"
			else if(i==2) optLbl="Scheme"
			
			var option = document.createElement("option");
			option.setAttribute("value", i+'');
			option.innerHTML = optLbl;
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
		
		this.show = function(cntId)
		{
			if(!this.visible) document.getElementById(cntId).appendChild(this.wrapEl);
			this.visible = true;
		};
		
		this.hide = function(cntId)
		{
			if(this.visible)
			{
				this.wrapEl.remove();
			};
			this.visible = false;
		};
		
		this.del = function()
		{
			this.remove();
			delete this.imgEl;
			delete this.wrapEl;
			delete this.url;
			delete this.type;
		};

		this.setInd=function(isInd)
		{
			$(this.indEl).attr("class", (isInd?"ofrBoxImgIndex":"ofrBoxImgNotInd")+" ofrBoxImgIndEl");
			this.indEl.innerHTML=isInd?"Front":"Not front"
		};
	};

	imgArr = function()
	{
		this.imgs = new Array();
		this.indexId = null;
		this.showType=0;
		this.cntId='imgBoxContainer_0';
		this.dspList=true;
		
		this.addImg = function(url, type)
		{
			var il = this.imgs.length;
			if(this.imgs.length==0) this.indexId=0;
			this.imgs.push(new photoImgEl(url, type));
			if(type==this.showType)
			{
				this.imgs[il].append(this.cntId);
			};
			if(this.showType==type||this.showType==0) this.imgs[il].show(this.cntId);
			$(this.imgs[this.imgs.length-1].delEl).click({id: this.imgs.length-1, obj: this},function(dta){
				dta.data.obj.removeImg(dta.data.id);
			});
			//*/
			$(this.imgs[il].indEl).click({imgar: this, id: il}, function(dta)
			{
				dta.data.imgar.chIndex(dta.data.id);
			});
			//*/
			return this.imgs.length;
		};
		
		this.removeImg = function(id)
		{
			this.imgs[id].hide();
			//this.imgs[id].del();
			console.log("de "+id);
			this.imgs.splice(id, 1);
		};
		
		//change display type
		//0=all types
		this.chDspType = function(to)
		{
			if(this.showType == to) return;
			for(var i=0; i<this.imgs.length; i++)
			{
				if(this.imgs[i].type==to||to==0) this.imgs[i].show(this.cntId);
				else this.imgs[i].hide(this.cntId);
			};
			this.showType = to;
		};
		
		this.chIndex = function(indId)
		{
			this.imgs[this.indexId].setInd(false);
			this.indexId = parseInt(indId);
			console.log(this.imgs[this.indexId].url);
			$('#addOfrFrontImg').attr("src", this.imgs[indId].url);
			this.imgs[this.indexId].setInd(true);
			//addOfrFrontImg
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
$(document).ready(function(){
	IMGS = new imgArr();
	for(var i=0;i<15;i++) IMGS.addImg("https://i1.ytimg.com/vi/vzTsEJjFL0k/default.jpg", 1);
	//initMaps();
});
