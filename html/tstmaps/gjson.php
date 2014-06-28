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
<script id="shader-fs" type="x-shader/x-fragment">
	precision mediump float;
	varying vec4 vColor;
	void main(void) {
		gl_FragColor = vColor;
	}
</script>

<script id="shader-vs" type="x-shader/x-vertex">
	attribute vec3 aVertexPosition;
	attribute vec4 aVertexColor;
	uniform mat4 uMVMatrix;
	uniform mat4 uPMatrix;
	varying vec4 vColor;
	void main(void) {
		gl_Position = uPMatrix * uMVMatrix * vec4(aVertexPosition, 1.0);
		vColor = aVertexColor;
	}
</script>
	<script src="http://code.jquery.com/jquery-1.11.1.min.js" type="text/javascript"></script>
	<script src="glm.min.js" type="text/javascript"></script>
	<script src="three.js" type="text/javascript"></script>
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
<!--	<canvas style="border: 1px solid red" id="cn" width="840" height="680"></canvas>-->
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
//var ch=$("#cn").height();
//var cw=$("#cn").width();

var ch=480;
var cw=640;

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

function drawLines(crds, type)
{
	//ctx.beginPath();
	//ptm=Conv.ll2m(crds[0][0], crds[0][1]);
	//pt=cvt(ptm.x, ptm.y);
	GL.addLine(crds, type);
	//ctx.moveTo(pt.x, pt.y);
	//for(var i=0;i<crds.length;i++)
	//{
		//ptm=Conv.ll2m(crds[i][0], crds[i][1]);
		//pt=cvt(ptm.x, ptm.y);
		/*/
		if(pt.x>maxX) maxX=pt.x;
		if(pt.x<minX&&pt.x!=0) minX=pt.x;
		if(pt.y>maxY) maxY=pt.y;
		if(pt.y<minY&&pt.y!=0) minY=pt.y;
		//*/
		//ctx.lineTo(pt.x, pt.y);
		//ctx.fillRect(pt.x, pt.y, 4, 4);
	//};
	//ctx.stroke();
}
var color="#AAAAAA";


var renderer=null;
var scene=null;
var camera=null;
/*/
$(document).ready(function(){
renderer = new THREE.WebGLRenderer({ antialias: true });
renderer.setClearColor(new THREE.Color( 0xff0000 ));
renderer.setSize(640, 480);
document.body.appendChild(renderer.domElement);

var material = new THREE.LineBasicMaterial({
        color: 0x0000ff
    });
var geometry = new THREE.Geometry();
    geometry.vertices.push(new THREE.Vector3(0, 0, 0));
    geometry.vertices.push(new THREE.Vector3(1, 2, 0));
    geometry.vertices.push(new THREE.Vector3(2, 4, 0));
    geometry.vertices.push(new THREE.Vector3(3, 8, 0));
    geometry.vertices.push(new THREE.Vector3(4, 12, 0));

var line = new THREE.Line(geometry, material);

 
scene = new THREE.Scene;

var cubeGeometry = new THREE.BoxGeometry(100, 100, 100);
var cubeMaterial = new THREE.MeshLambertMaterial({ color: 0xaaaaaa });
var cube = new THREE.Mesh(cubeGeometry, cubeMaterial);
 
cube.rotation.y = Math.PI * 45 / 180;
 
//scene.add(cube);
scene.add(line);

//var camera = new THREE.PerspectiveCamera(45, 640 / 480, 0.1, 10000);
//var camera = new THREE.PerspectiveCamera(0, 0, 640, 480, 0.1, 10000);

camera = new THREE.PerspectiveCamera(45, window.innerWidth / window.innerHeight, 1, 500);
camera.position.set(0, 0, 100);
camera.lookAt(new THREE.Vector3(0, 0, 0));

renderer.render(scene, camera);
});
//*/

var GL=
{
	renderer: null,
	scene: null,
	camera: null,
	streetMaterial: null,
	sStreetMaterial: null,
	waterMaterial: null,
	buildingMaterial: null,
	lines: [],
	w: 640,
	h: 480,
	scale: 1,
	isDragged: false,
	dragStart:
	{
		x: 0,
		y: 0
	},
	init: function()
	{
		this.renderer = new THREE.WebGLRenderer({ antialias: true });
		this.renderer.setClearColor(new THREE.Color( 0xffffff ));
		this.renderer.setSize(640, 480);
		document.body.appendChild(this.renderer.domElement);
		this.renderer.domElement.onmousedown=function(e){
			GL.isDragged=true;
			GL.dragStart.x=e.layerX;
			GL.dragStart.y=e.layerY;
		};
		this.renderer.domElement.onmouseup=function(e){
			GL.isDragged=false;
		};
		this.renderer.domElement.onmouseleave=function(e){
			GL.isDragged=false;
		};
		this.renderer.domElement.onmousemove=function(e){
			if(GL.isDragged&&e.buttons==1)
			{
				//console.log(e)
				console.log(e.layerX-GL.dragStart.x, e.layerY-GL.dragStart.y);
				GL.pan({x: e.layerX-GL.dragStart.x, y: e.layerY-GL.dragStart.y});
				GL.render();
				GL.dragStart.x=e.layerX;
				GL.dragStart.y=e.layerY;
			};
		};
		$(this.renderer.domElement).bind("DOMMouseScroll mousewheel", function(e){
			e.preventDefault();
			console.log(e.originalEvent.detail)
			var factor=e.originalEvent.detail;
			var c=factor>0?factor:-1/factor;
			GL.zoom(Math.sqrt(c));
			GL.render();
		});
		
		this.scene = new THREE.Scene;
		
		this.streetMaterial= new THREE.LineBasicMaterial({
			color: 0xaaaaaa,
			linewidth: 4
		});

		this.sStreetMaterial= new THREE.LineBasicMaterial({
			color: 0xaaaaaa,
			linewidth: 2
		});

		this.waterMaterial= new THREE.MeshBasicMaterial({
			color: 0x8888ff
		});

		this.buildingMaterial= new THREE.MeshBasicMaterial({
			color: 0xdddddd
		});
		
		this.camera = new THREE.OrthographicCamera(-this.w/2,  this.w/2, this.h/2, -this.h/2, 0.1, 500);
		//this.camera = new THREE.PerspectiveCamera(45, window.innerWidth / window.innerHeight, 1, 500);
		this.camera.position.set(0, 0, 100);
		//this.camera.lookAt(new THREE.Vector3(0, 0, 0));
		this.renderer.render(this.scene, this.camera);
		this.addLine([[-122.27116,37.83685], [0000000000, 00000000]]);
	},
	addLine: function(crds, type)
	{
		if(typeof type != "number")
		{
			console.log("notype");
			type=0;
		}
		var geometry = new THREE.Geometry();
		for(var i=0;i<crds.length;i++)
		{
			var ptm=Conv.ll2m(crds[i][0], crds[i][1]);
			var pt=cvt(ptm.x, ptm.y);
			//console.log(((bbox.cy-ptm.y)/bbox.sh)*this.h);
			//console.log(((bbox.cx-ptm.x)/bbox.sw)*this.w);
			//geometry.vertices.push(new THREE.Vector3(LLCrds[i].lat, LLCrds[i].lon, 0));
			//console.log(pt.x);
			geometry.vertices.push(new THREE.Vector3(pt.x, pt.y, 01));
		};
		var line=null;
		if(type==0) line=new THREE.Line(geometry, this.streetMaterial);
		else if(type==1) line=new THREE.Line(geometry, this.sStreetMaterial);
		//var line=new THREE.Line(geometry, new THREE.LineBasicMaterial({
		//	color: Math.floor(Math.random()*0xaaaaaa),
		//	linewidth: 1
		//}));

		this.scene.add(line);
		this.lines.push(line);
	},
	addWater: function(crds)
	{
		var sh = new THREE.Shape();
		var ptm=Conv.ll2m(crds[0][0], crds[0][1]);
		var pt=cvt(ptm.x, ptm.y);
		sh.moveTo(pt.x, pt.y, 1);
		for(var i=1;i<crds.length;i++)
		{
			ptm=Conv.ll2m(crds[i][0], crds[i][1]);
			pt=cvt(ptm.x, ptm.y);
			sh.lineTo(pt.x, pt.y);
		};
		var geometry=sh.makeGeometry();
		var lake=new THREE.Mesh(geometry, this.waterMaterial);
		this.scene.add(lake);
	},
	addBuilding: function(crds)
	{
		var sh = new THREE.Shape();
		var ptm=Conv.ll2m(crds[0][0], crds[0][1]);
		var pt=cvt(ptm.x, ptm.y);
		sh.moveTo(pt.x, pt.y, -1);
		for(var i=1;i<crds.length;i++)
		{
			ptm=Conv.ll2m(crds[i][0], crds[i][1]);
			pt=cvt(ptm.x, ptm.y);
			sh.lineTo(pt.x, pt.y, -1);
		};
		var geometry=sh.makeGeometry();
		var lake=new THREE.Mesh(geometry, this.buildingMaterial);
		this.scene.add(lake);
	},
	render: function()
	{
		var then=performance.now();
		this.renderer.render(this.scene, this.camera);
		var now=performance.now();
		//console.log(this.lines.length/((then-now)/1000));
		//console.log(then-now);
	},
	panLeft: function(x)
	{
		this.camera.translateX(x);
		this.render();
	},
	pan: function(objXY)
	{
		//this.camera.translateX(objXY.x);
		//this.camera.translateY(objXY.y);
		this.camera.top+=objXY.y*this.scale;
		this.camera.bottom+=objXY.y*this.scale;
		this.camera.left-=objXY.x*this.scale;
		this.camera.right-=objXY.x*this.scale;
		this.camera.updateProjectionMatrix();
		this.render();
	},
	zoom: function(scale)
	{
		if(typeof scale != "number")
		{
			throw "scale must b a num";
			return;
		};
		this.scale*=scale;
		this.camera.top=this.camera.top*scale;
		this.camera.bottom=this.camera.bottom*scale;
		this.camera.left=this.camera.left*scale;
		this.camera.right=this.camera.right*scale;
		this.camera.updateProjectionMatrix();
		this.render();
	}
}

function drawStreeds(dta)
{
	var features=dta.features;
	//ctx.strokeStyle = "#AAAAAA";
	//if(big!=true) big=false
	for(var i=0;i<features.length;i++)
	{
		if(typeof kinds[features[i].properties['kind']]!="number")
			kinds[features[i].properties['kind']]=0;
		else
			kinds[features[i].properties['kind']]++;

		if(typeof highw[features[i].properties['highway']]!="number")
			highw[features[i].properties['highway']]=0;
		else
			highw[features[i].properties['highway']]++;
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
			
			if(features[i].properties['kind']=="major_road")
				drawLines(features[i].geometry.coordinates, 0);
			//if(features[i].properties['kind']=="highway")
			else if(features[i].properties['kind']=="minor_road")
				drawLines(features[i].geometry.coordinates, 1);
		}
		else
		{
			//console.log("nstroke"+i);
		};
	};
}

var kinds=[];
var highw=[];


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
			//drawBuilding(df[i].geometry.coordinates[0]);
			GL.addBuilding(df[i].geometry.coordinates[0]);
		}
		/*/
		else if(df[i].geometry.type=="MultiPolygon")
		{
			for(var j=0;j<df[i].geometry.coordinates.length;j++)
				//drawBuilding(df[i].geometry.coordinates[j][0]);
				GL.addBuilding(df[i].geometry.coordinates[j][0]);
		}
		//*/
		else console.log("none");
	};
};


function drawWater(crds)
{
	GL.addWater(crds);
	//ctx.beginPath();
	//ctx.strokeStyle = "#4444FF";
	//ctx.fillStyle = "#4444FF";
	//ctx.lineWidth=1;
	//ptm=Conv.ll2m(crds[0][0], crds[0][1]);
	//pt=cvt(ptm.x, ptm.y);
	//ctx.moveTo(pt.x, pt.y);
	//for(var i=0;i<crds.length;i++)
	//{
		//console.log("hello");
		//ptm=Conv.ll2m(crds[i][0], crds[i][1]);
		//pt=cvt(ptm.x, ptm.y);
		//ctx.lineTo(pt.x, pt.y);
	//};
	//ctx.closePath();
	//ctx.stroke();
	//ctx.fill();
}
/*/
var GL=
{
	gl: null,
	shaderProgram: null,
	init: function(cId)
	{
		try {
			var canvas = document.getElementById(cId);
			this.gl = canvas.getContext("experimental-webgl");
			this.gl.viewportWidth = canvas.width;
			this.gl.viewportHeight = canvas.height;
		} catch (e) {console.log(e)};
		if (!this.gl) {
			alert("Could not initialise WebGL, sorry :-(");
		};
		this.initShaders();
		this.gl.clearColor(0.0, 0.0, 0.0, 1.0);
	},
	getShader: function(id)
	{
		var shaderScript = document.getElementById(id);
		if (!shaderScript) {
			return null;
		}
		
		var str = "";
		var k = shaderScript.firstChild;
		while (k) {
			if (k.nodeType == 3) {
				str += k.textContent;
			}
			k = k.nextSibling;
		}
		
		var shader;
		if (shaderScript.type == "x-shader/x-fragment") {
			shader = this.gl.createShader(this.gl.FRAGMENT_SHADER);
		} else if (shaderScript.type == "x-shader/x-vertex") {
			shader = this.gl.createShader(this.gl.VERTEX_SHADER);
		} else {
			return null;
		}
		
		this.gl.shaderSource(shader, str);
		this.gl.compileShader(shader);
		
		if (!this.gl.getShaderParameter(shader, this.gl.COMPILE_STATUS)) {
			alert(this.gl.getShaderInfoLog(shader));
			return null;
		}
		
		return shader;
	},
	initShaders: function()
	{
		var fragmentShader = this.getShader("shader-fs");
		var vertexShader   = this.getShader("shader-vs");
		
		this.shaderProgram = this.gl.createProgram();
		this.gl.attachShader(this.shaderProgram, vertexShader);
		this.gl.attachShader(this.shaderProgram, fragmentShader);
		this.gl.linkProgram(this.shaderProgram);
		
		if (!this.gl.getProgramParameter(this.shaderProgram, this.gl.LINK_STATUS)) {
			alert("Could not initialise shaders");
		}
		
		this.gl.useProgram(this.shaderProgram);
		
		this.shaderProgram.vertexPositionAttribute = this.gl.getAttribLocation(this.shaderProgram, "aVertexPosition");
		this.gl.enableVertexAttribArray(this.shaderProgram.vertexPositionAttribute);
		
		this.shaderProgram.vertexColorAttribute = this.gl.getAttribLocation(this.shaderProgram, "aVertexColor");
		this.gl.enableVertexAttribArray(this.shaderProgram.vertexColorAttribute);
		
		this.shaderProgram.pMatrixUniform = this.gl.getUniformLocation(this.shaderProgram, "uPMatrix");
		this.shaderProgram.mvMatrixUniform = this.gl.getUniformLocation(this.shaderProgram, "uMVMatrix");
	}
};
//*/

function zo()
{
	bbox.h+=0.01;
	bbox.w+=0.01;
	//ctx.clearRect(0, 0, cw, ch);
	drawStreeds(data.features);
}

function zi()
{
	bbox.h-=0.1;
	bbox.w-=0.1;
	//ctx.clearRect(0, 0, cw, ch);
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
	
	//var c = document.getElementById("cn");
	//var ctx = c.getContext("2d");
	//ctx.beginPath();
	//ctx.moveTo(0,0);
	//ctx.lineTo(200,100);
	//ctx.arc(95,50,40,0,2*Math.PI);
	//ctx.stroke();


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
		//var then=performance.now();
			//console.log(TILES.data[tz][tx][ty]);
			var ar=TILES.data[tz][tx][ty];
			drawBuildingsAll(ar[1]);
			drawWaterAll(ar[0]);
			drawStreeds(ar[2]);
		//var now=performance.now();
		//console.log(now-then, ar[2].features.length, (now-then)/ar[2].features.length);
		});
	}

	var then=0;
	var rendermap=function()
	{
	//	ctx.clearRect(0, 0, cw, ch);
		var ll=Conv.m2ll(bbox.cx, bbox.cy);
		var ll2=Conv.m2ll(bbox.cx+bbox.sh/cr, bbox.cy+bbox.sh);
		var heightDiff=-ll.lat+ll2.lat;
		var tilex=long2tile(ll.lon, tz);
		var tiley=lat2tile(ll.lat, tz);

		//then=performance.now();
		$.when(
		//drawTile(tilex, tiley, tz),
		drawTile(tilex+2, tiley+2, tz)//,
	/*/
		drawTile(tilex, tiley+1, tz),
		drawTile(tilex, tiley+2, tz),
		drawTile(tilex+1, tiley, tz),
		drawTile(tilex+1, tiley+1, tz),
		drawTile(tilex+1, tiley+2, tz),
		drawTile(tilex+2, tiley, tz),
		drawTile(tilex+2, tiley+1, tz),
		drawTile(tilex+2, tiley+2, tz)
	//*/
		)
		.then
		(function(){
		//var now=performance.now();
		//console.log("total", now-then);
		});
		//console.log(heightDiff);
	};
	

	//rotateBy40Ded();

//	tx=long2tile();

	$(document).ready(function(){
		GL.init();
		rendermap();
		$("#target").keypress(function(e){
			e.preventDefault();
			//ctx.clearRect(0, 0, cw, ch);
			console.log(e.charCode);
			switch(e.charCode)
			{
				case 105: GL.pan({x: 0  , y: 20}); break;
				case 107: GL.pan({x: 0  , y:-20}); break;
				case 106: GL.pan({x: -20 , y: 0}); break;
				case 108: GL.pan({x: 20, y: 0}); break;
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


