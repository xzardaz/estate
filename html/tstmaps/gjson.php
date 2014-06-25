<!DOCTYPE html>
<html>
  <head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <meta charset="utf-8">
	<link rel="stylesheet" href="http://ol3js.org/en/master/css/ol.css" type="text/css">
	 <script src="http://ol3js.org/en/master/build/ol.js" type="text/javascript"></script>
    <title>Simple Polylines</title>
    <style>
      html, body, #map-canvas {
        height: 100%;
        margin: 0px;
        padding: 0px
      }
    </style>
	<script src="http://code.jquery.com/jquery-1.11.1.min.js" type="text/javascript"></script>
    <!--<script src="https://maps.googleapis.com/maps/api/js?v=3.exp"></script>-->
<script>
	var data=<?=file_get_contents("g2.json");?>;
	var dataS=<?=file_get_contents("g.json");?>;
	var dataW=<?=file_get_contents("http://tile.openstreetmap.us/vectiles-water-areas/12/656/1582.json");?>;
	var Conv=({
	r_major:6378137.0,//Equatorial Radius, WGS84
	r_minor:6356752.314245179,//defined as constant
	f:298.257223563,//1/f=(a-b)/a , a=r_major, b=r_minor
	deg2rad:function(d)
	{
		var r=d*(Math.PI/180.0);
		return r;
	},
	rad2deg:function(r)
	{
		var d=r/(Math.PI/180.0);
		return d;
	},
	ll2m:function(lon,lat) //lat lon to mercator
	{
		//lat, lon in rad
		var x=this.r_major * this.deg2rad(lon);
 
		if (lat > 89.5) lat = 89.5;
		if (lat < -89.5) lat = -89.5;
 
 
		var temp = this.r_minor / this.r_major;
		var es = 1.0 - (temp * temp);
		var eccent = Math.sqrt(es);
 
		var phi = this.deg2rad(lat);
 
		var sinphi = Math.sin(phi);
 
		var con = eccent * sinphi;
		var com = .5 * eccent;
		var con2 = Math.pow((1.0-con)/(1.0+con), com);
		var ts = Math.tan(.5 * (Math.PI*0.5 - phi))/con2;
		var y = 0 - this.r_major * Math.log(ts);
		var ret={'x':x,'y':y};
		return ret;
	},
	m2ll:function(x,y) //mercator to lat lon
	{
		var lon=this.rad2deg((x/this.r_major));
 
		var temp = this.r_minor / this.r_major;
		var e = Math.sqrt(1.0 - (temp * temp));
		var lat=this.rad2deg(this.pj_phi2( Math.exp( 0-(y/this.r_major)), e));
 
		var ret={'lon':lon,'lat':lat};
		return ret;
	},
	pj_phi2:function(ts, e) 
	{
		var N_ITER=15;
		var HALFPI=Math.PI/2;
 
 
		var TOL=0.0000000001;
		var eccnth, Phi, con, dphi;
		var i;
		var eccnth = .5 * e;
		Phi = HALFPI - 2. * Math.atan (ts);
		i = N_ITER;
		do 
		{
			con = e * Math.sin (Phi);
			dphi = HALFPI - 2. * Math.atan (ts * Math.pow((1. - con) / (1. + con), eccnth)) - Phi;
			Phi += dphi;
 
		} 
		while ( Math.abs(dphi)>TOL && --i);
		return Phi;
	}
	});
	//usage
	//var mercator=Conv.ll2m(47.6035525, 9.770602);//output mercator.x, mercator.y
	//var latlon=Conv.m2ll(5299424.36041, 1085840.05328);//output latlon.lat, latlon.lon
</script>
  </head>
  <body>
    <!--<div id="map-canvas"></div>-->
	<input id="target" type="text" value="Hello there">
	<canvas style="border: 1px solid red" id="cn" width="840" height="680"></canvas>
    <script>
var MAP;
var cr=data.features[0].geometry.coordinates;

var SRR=Conv.ll2m(23.269043, 42.779141);
var bbox=
{
/*/
	t:   37.974515,
	l: -122.428894+180,
	h:    0.25,
	w:    0.15
//*/
	cy: SRR.y,
	cx: SRR.x,
	sh: 28000,
	h:  28000.10,
	w:  28000.05
};
var ch=$("#cn").height();
var cw=$("#cn").width();

var cr=ch/cw;

var cvt=function(mx, my)
{
	var sw=bbox.sh/cr;
	var x=-cw*((bbox.cx-mx)/sw);
	var y=ch*((bbox.cy-my)/bbox.sh);
	return({x:x, y:y});
}

//console.log(cvt(cr[0][1], cr[0][0]));
//initialize();
var ptm=null;
var pt=null;

var maxX=0;
var minX=10000000000000000000000;
var maxY=0;
var minY=10000000000000000000000;

function getRandomColor() {
	var letters = '0123456789ABCDEF'.split('');
	var color = '#';
	for (var i = 0; i < 6; i++ ) {
		color += letters[Math.floor(Math.random() * 16)];
	}
	return color;
}

function drawLines(crds)
{
	ctx.beginPath();
	ptm=Conv.ll2m(crds[0][0], crds[0][1]);
	pt=cvt(ptm.x, ptm.y);
	ctx.moveTo(pt.x, pt.y);
	for(var i=0;i<crds.length;i++)
	{
		ptm=Conv.ll2m(crds[i][0], crds[i][1]);
		pt=cvt(ptm.x, ptm.y);
		/*/
		if(pt.x>maxX) maxX=pt.x;
		if(pt.x<minX&&pt.x!=0) minX=pt.x;
		if(pt.y>maxY) maxY=pt.y;
		if(pt.y<minY&&pt.y!=0) minY=pt.y;
		//*/
		ctx.lineTo(pt.x, pt.y);
		//ctx.fillRect(pt.x, pt.y, 4, 4);
	};
	ctx.stroke();
}
var color="#AAAAAA";
function drawStreeds(dta)
{
	var features=dta.features;
	ctx.strokeStyle = "#AAAAAA";
	//if(big!=true) big=false
	for(var i=0;i<features.length;i++)
	{
		/*/
		if(features[i].properties.highway=="primary")
		{
			//ctx.strokeStyle = "#AA8800";
			//ctx.strokeStyle = "#AAAAAA";
			//ctx.lineWidth=8;
		}
		else if(features[i].properties.highway=="residential")
		{
			//ctx.strokeStyle = "#222222";
			//ctx.strokeStyle = "#AAAAAA";
			//ctx.lineWidth=1;
		}
		else
		{
			//ctx.strokeStyle = "#AAAAAA";
			//ctx.lineWidth=4;
		};
		//*/
		if(features[i].geometry.type=="LineString")
		{
			//color=getRandomColor();
			//ctx.strokeStyle = color;
			//ctx.fillStyle = color;
			//console.log("stroke"+i);
			drawLines(features[i].geometry.coordinates);
		}
		else
		{
			//console.log("nstroke"+i);
		};
	};
}


function drawBuilding(crds)
{
	ctx.beginPath();
	ctx.strokeStyle = "#4444FF";
	ctx.fillStyle = "#DDDDDD";
	ctx.lineWidth=1;
	ptm=Conv.ll2m(crds[0][0], crds[0][1]);
	pt=cvt(ptm.x, ptm.y);
	ctx.moveTo(pt.x, pt.y);
	for(var i=0;i<crds.length;i++)
	{
		//console.log("hello");
		ptm=Conv.ll2m(crds[i][0], crds[i][1]);
		pt=cvt(ptm.x, ptm.y);
		ctx.lineTo(pt.x, pt.y);
	};
	ctx.closePath();
	//ctx.stroke();
	ctx.fill();
}

var Streets=
{
	data: [],
	drawStreet: function(stId){
		alert();
	},
	add: function(fObj, zl)
	{
		if(fObj.geometry.type=="LineString")
			for(var i=0;i<fObj.geometry.coordinates.length;i++)
			{
				data.push([
					fObj.id,
					zl,
					fObj.geometry.coordinates[1],
					fObj.geometry.coordinates[0]
				]);
			}
	}
}


var dataT=new Array();
var dataR=new Array();
var dataL=new Array();

function drawBuildingsAll(dta)
{
	var df=dta.features;
	for(var i=0;i<df.length;i++)
	{
		//if(df[i].properties.kind=="park")
		if(df[i].geometry.type=="Polygon")
		{
			drawBuilding(df[i].geometry.coordinates[0]);
		}
		else if(df[i].geometry.type=="MultiPolygon")
		{
			for(var j=0;j<df[i].geometry.coordinates.length;j++)
				drawBuilding(df[i].geometry.coordinates[j][0]);
		}
		else console.log("none");
	};
};


function drawWater(crds)
{
	ctx.beginPath();
	ctx.strokeStyle = "#4444FF";
	ctx.fillStyle = "#4444FF";
	ctx.lineWidth=1;
	ptm=Conv.ll2m(crds[0][0], crds[0][1]);
	pt=cvt(ptm.x, ptm.y);
	ctx.moveTo(pt.x, pt.y);
	for(var i=0;i<crds.length;i++)
	{
		//console.log("hello");
		ptm=Conv.ll2m(crds[i][0], crds[i][1]);
		pt=cvt(ptm.x, ptm.y);
		ctx.lineTo(pt.x, pt.y);
	};
	ctx.closePath();
	//ctx.stroke();
	ctx.fill();
}


function zo()
{
	bbox.h+=0.01;
	bbox.w+=0.01;
	ctx.clearRect(0, 0, cw, ch);
	drawStreeds(data.features);
}

function zi()
{
	bbox.h-=0.1;
	bbox.w-=0.1;
	ctx.clearRect(0, 0, cw, ch);
	drawStreeds(data.features);
}

function drawWaterAll(dta)
{
	var df=dta.features;
	for(var i=0;i<df.length;i++)
	{
		if(df[i].geometry.type=="Polygon")
		{
			drawWater(df[i].geometry.coordinates[0]);
		}
		else if(df[i].geometry.type=="MultiPolygon")
		{
			for(var j=0;j<df[i].geometry.coordinates.length;j++)
				drawWater(df[i].geometry.coordinates[j][0]);
		}
		else console.log("none");
	};
};

//google.maps.event.addDomListener(window, 'load', initialize);
	
	var c = document.getElementById("cn");
	var ctx = c.getContext("2d");
	ctx.beginPath();
	ctx.moveTo(0,0);
	ctx.lineTo(200,100);
	//ctx.arc(95,50,40,0,2*Math.PI);
	ctx.stroke();


	function long2tile(lon,zoom) { return (Math.floor((lon+180)/360*Math.pow(2,zoom))); }
	function lat2tile(lat,zoom)  { return (Math.floor((1-Math.log(Math.tan(lat*Math.PI/180) + 1/Math.cos(lat*Math.PI/180))/Math.PI)/2 *Math.pow(2,zoom))); }
	function tile2long(x,z) {
		return (x/Math.pow(2,z)*360-180);
	}
	function tile2lat(y,z) {
	var n=Math.PI-2*Math.PI*y/Math.pow(2,z);
		return (180/Math.PI*Math.atan(0.5*(Math.exp(n)-Math.exp(-n))));
	}



	//var ty=1582;
	//var tx=656;
	var ty=395;
	var tx=164;
	var tz=12;

	var ll=Conv.m2ll(bbox.cx, bbox.cy);
	var tilex=long2tile(ll.lon, tz);
	var tiley=lat2tile(ll.lat, tz);
	tx=tilex;
	ty=tiley;

	var TILES=
	{
		data:[],
		ret: function(Z, X, Y){return TILES.data[Z][X][Z]},
		get: function(Z, X, Y)
		{
			//Curious method of AND'ing conditions without breaking
			//cases line if(data[14][12][11]) -- "err.: data[14] is `undefined`"
			if(typeof TILES.data[Z]=="object")
			{
				//console.log("here Z", Z);
				if(typeof TILES.data[Z][X]=="object")
				{
					//console.log("here X", X);
					if(typeof TILES.data[Z][X][Y]=="object")
					{
						//console.log("here Y", Y);
						return $.when(TILES.ret(Z, X, Y));
					}
				}
			}
			//else
			//{
			console.log("fetch from net");
			return $.when(
				$.post
				(
					"getUrl.php",
					{url: "http://tile.openstreetmap.us/vectiles-water-areas/"+Z+"/"+X+"/"+Y+".json"},
					function(){},
					"json"
				),
				$.post
				(
					"getUrl.php",
					{url: "http://tile.openstreetmap.us/vectiles-land-usages/"+Z+"/"+X+"/"+Y+".json"},
					function(){},
					"json"
				),
				$.post
				(
					"getUrl.php",
					{url: "http://tile.openstreetmap.us/vectiles-highroad/"+Z+"/"+X+"/"+Y+".json"},
					function(){},
					"json"
				)
			)
			.then
			(function(r1, r2, r3){
				//console.log("xyz", X, Y, Z);
				if(typeof TILES.data[Z]=="undefined")
				{
					TILES.data[Z]=[];
				}
				if(typeof TILES.data[Z][X]=="undefined")
				{
					TILES.data[Z][X]=[];
				}
				TILES.data[Z][X][Y]=[r1[0], r2[0], r3[0]];
			});
			//}
		}
	}

	function drawTile(tx, ty, tz)
	{
		//console.log(tx, ty, tz);
		TILES.get(tz, tx, ty)
		.done(function(r1, r2, r3){
		var then=performance.now();
			//console.log(TILES.data[tz][tx][ty]);
			var ar=TILES.data[tz][tx][ty];
			//drawBuildingsAll(ar[1]);
			//drawWaterAll(ar[0]);
			drawStreeds(ar[2]);
		var now=performance.now();
		console.log(now-then, ar[2].features.length, (now-then)/ar[2].features.length);
		});
	}

	var then=0;
	var rendermap=function()
	{
		ctx.clearRect(0, 0, cw, ch);
		var ll=Conv.m2ll(bbox.cx, bbox.cy);
		var ll2=Conv.m2ll(bbox.cx+bbox.sh/cr, bbox.cy+bbox.sh);
		var heightDiff=-ll.lat+ll2.lat;
		var tilex=long2tile(ll.lon, tz);
		var tiley=lat2tile(ll.lat, tz);

		then=performance.now();
		$.when(
		drawTile(tilex, tiley, tz),
		drawTile(tilex+2, tiley+2, tz),
	//*/
		drawTile(tilex, tiley+1, tz),
		drawTile(tilex, tiley+2, tz),
		drawTile(tilex+1, tiley, tz),
		drawTile(tilex+1, tiley+1, tz),
		drawTile(tilex+1, tiley+2, tz),
		drawTile(tilex+2, tiley, tz),
		drawTile(tilex+2, tiley+1, tz),
		drawTile(tilex+2, tiley+2, tz)
		)
		.then
		(function(){
		var now=performance.now();
		console.log("total", now-then);
		});
	//*/
		//console.log(heightDiff);
	};
	

	//rotateBy40Ded();

//	tx=long2tile();

	$(document).ready(function(){
		rendermap();
		$("#target").keypress(function(e){
			e.preventDefault();
			//ctx.clearRect(0, 0, cw, ch);
			//console.log(e);
			switch(e.charCode)
			{
				case 119:
					ty--;
					console.log("ty-"+ty);
					//bbox.t+=100;
					//ctx.clearRect(0, 0, cw, ch);
					//drawStreeds(data.features);
				break;
				case 115:
					ty++;
					console.log("ty+"+ty);
				break;
				case 97:
					tx--;
					console.log("tx-"+tx);
				break;
				case 100:
					tx++;
					console.log("tx+"+tx);
				break;
				case 45:
					zo();
				break;
				case 43:
					zi();
				break;
			};
			drawTile(tx, ty, tz);
		});
	});

    </script>
	<!--
    <script type="text/javascript" src="ol.js">
    </script>
	-->
  </body>
</html>


