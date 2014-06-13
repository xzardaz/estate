<?php

$ch = curl_init();
include('shtmld.php');

curl_setopt($ch, CURLOPT_VERBOSE, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);


for($i=1;$i<=42;$i++)
{
curl_setopt($ch, CURLOPT_URL, "http://imoti.net/?&page=$i&address=agency/agencies/&city_id=0&name=&addres=&tel=&broker_name=&Submit2=%D2%FA%F0%F1%E8&special=");
$response=curl_exec($ch);
$html = str_get_html($response);
foreach($html->find('td.agency') as $element)
{
	$a=$element->find('a', 0);
	preg_match('/[0-9]+$/', $a->href, $matches);
	//var_dump($matches[0]);
	//echo "\n\n";
	echo $matches[0].", ".$element->plaintext."\n\n";
};
	flush();
}
curl_close($ch);








?>
