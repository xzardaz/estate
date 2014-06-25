<?php
include "shtmld.php";
//$string=file_get_contents("magistrala2.xml");
$string=file_get_contents("m5.xml");
$struct=str_get_html($string);
//sleep(5);
//var_dump($struct);
//die("done");
//echo $struct->plaintext;
//echo "ok";
$ptsArray=array();
$wsArray=array();

//$ptsArray=unserialize(file_get_contents("pts"));
//$wsArray=unserialize(file_get_contents("ws"));

//*/
$points=$struct->find("node");
foreach($points as $point)
{
	$ptsArray[$point->id]=array(floatval($point->lat), floatval($point->lon));
	//echo "<br> hi".$point->lat;
};

$ways=$struct->find("way");
foreach($ways as $way)
{
	$wsArray[$way->id]['pts']=array();
	$wsArray[$way->id]['props']=array(
		'closed'=>false,
		'color'=>'#222222'
	);
	$wayPoints=$way->find("nd");
	foreach($wayPoints as $wp)
	{
		array_push($wsArray[$way->id]['pts'],$ptsArray[$wp->ref]);
	};
	$wayProps=$way->find("tag");
	foreach($wayProps as $wp)
	{
		if($wp->v=="water")
		{
			$wsArray[$way->id]['props']['closed']=true;
			$wsArray[$way->id]['props']['color']='#0000FF';
		};
		if($wp->k=="highway"&&$wp->v=="primary")
		{
			$wsArray[$way->id]['props']['color']='#666666';
		};
		if($wp->k=="highway"&&$wp->v=="motorway")
		{
			$wsArray[$way->id]['props']['color']='#FFFF00';
		};
		if($wp->k=="highway"&&$wp->v=="residential")
		{
			$wsArray[$way->id]['props']['color']='#00FF00';
		};
	};
	//$ptsArray[$point->id]=array($point->lat, $point->lon);
};
//*/

//var_dump($wsArray);
$i=0;
$cnt=count($wsArray);
foreach($wsArray as $k=>$way)
{
	echo "var crds$k = [\n";
	$j=0;
	$cntJ=count($way['pts']);
	foreach($way['pts'] as $pt)
	{
		echo "\nnew google.maps.LatLng(".$pt[0].", ".$pt[1].")".($j==$cntJ-1?"":",")."\n";
		$j++;
	};
	//var_dump($way['props']);
	echo "];\n";
	echo "var fp$k = new google.maps.Poly".($way['props']['closed']?'gon':'line')."({\n";
	echo "path".($way['props']['closed']?'s':'').": crds$k,\n";
	echo "strokeWeight: 1,\n";
	echo "fillColor: '".$way['props']['color']."',\n";
	echo "fillOpacity: 1.0,";
	echo "strokeColor: '".$way['props']['color']."'\n";
	//echo implode(",<br>", $way['props']);
	echo "});\n";
	echo "fp$k.setMap(MAP);\n";
	$i++;
}










?>
