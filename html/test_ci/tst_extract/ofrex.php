<?php


//http://imoti.net/search/results/?personal=&ad_type_id=2&save_search=1&search_form=prodajbi&lang=bg&new_search=n&price_from=&price_to=&currency_id=4&price_kv_from=&price_kv_to=&currency_kv_id=4&surface_from=&surface_to=&floor_from=&floor_to=&nomer=&keyword=&new_building_yes=on&city_id=&ads_type=all&order=1&show_all=1&x=73&y=1&tab=tab1&page=0
function file_get_contents_utf8($fn) {
     $content = file_get_contents($fn);
      return mb_convert_encoding(
		$content,
		//'UTF-8',
		'UTF-8',
          	//mb_detect_encoding($content, 'UTF-8, Windows-1251, ISO-8859-1', true)
          	'Windows-1251'
	);	
};
include('shtmld.php');
for($pg=1;$pg<81;$pg++)
{
$ftext=file_get_contents_utf8("http://imoti.net/search/results/?personal=&ad_type_id=2&save_search=1&search_form=prodajbi&lang=bg&new_search=n&x=0&y=0&price_from=&price_to=&currency_id=4&price_kv_from=&price_kv_to=&currency_kv_id=4&surface_from=&surface_to=&floor_from=&floor_to=&nomer=&keyword=&new_building_yes=on&city_id=&ads_type=all&order=1&show_all=on&tab=tab1&page=$pg");



$html = str_get_html($ftext);
$el=$html->find('.item');
foreach($el as $subel)
{
	$price=$subel->find('.price', 0);
	$prstr=preg_replace('/[^0-9]/', '', $price->plaintext);
	$lists=$subel->find('li>span[class=sd]');
	$arstr="0";
	$agstr="0";
	$currency=0;
	foreach($lists as $ll)
	{
		//echo ($ll->plaintext)."\n";
		//if(strpos($ll->plaintext, '.')!=false)
		if(preg_match('/^[0-9]+\s.*[.].*$/', $ll->plaintext)!=false)
		{
			$arstr=preg_replace('/[^0-9]/', '', $ll->plaintext);
		};
	};
	$agtry=$subel->find("ul.sc>li[class=rf]>a");
	foreach($agtry as $all)
	{
		$href = $all->href;
		if(stristr($href, "agency/show")!=false)
		{
			preg_match('/[0-9]+$/', $href, $matches);
			$agstr=$matches[0];
		};
	};

	$imgtry=$subel->find('img.thumb', 0);
	$imgstr=$imgtry->src;

	$addrtry=$subel->find('div>h2', 0);
	$addrstr=trim($addrtry->plaintext);

	$typetry=explode(', ', $addrstr);
	$type=0;
	switch($typetry[0])
	{
		case 'Къща': $type=5; break;
		case 'Магазин': $type=2; break;
		case 'Мезонет': $type=1; break;
		case 'Парцел': $type=4; break;
		case 'Гараж': $type=3; break;
		default:
			$type=0;
	};
	if(stristr($typetry[0], "апартамент")!=false) $type=1;
	unset($typetry[0]);
	$addrstr=implode(', ', $typetry);
	$typestr=$type;
	$q="insert into \"offers\" (price, area, agency, imgpath, type, address) values ($prstr, $arstr, $agstr, \"http://imoti.net$imgstr\", $typestr, \"$addrstr\");";
	//echo "$prstr, $arstr, $agstr, $imgstr, $typestr, $addrstr\n";
	echo $q."\n";
};
flush();
};








?>
