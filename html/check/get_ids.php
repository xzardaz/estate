<?php


include("./shtmld.php");

$fh=fopen("data2.ssv", 'a');


$pstDta=array(
	"act" => "3",
	"rub" => "1",
	"rub_pub_save" => "1",
	"topmenu" => "2",
	"actions" => "1",
	"f0" => "84.238.182.151",
	"f1" => "",
	"f2" => "",
	"f3" => "",
	"f4" => "1",
	"f7" => "3~",
	"f28" => "10000",
	"f29" => "",
	"f43" => "",
	"f44" => "",
	"f30" => "EUR",
	"f26" => "40",
	"f27" => "",
	"f41" => "2",
	"f31" => "",
	"f32" => "",
	"f38" => "",
	"f42" => "",
	"f39" => "",
	"f40" => "",
	"fe3" => "",
	"fe4" => "",
	"f45" => "",
	"f46" => "",
	"f54" => "Ïàíåë",
	"f51" => "",
	"f52" => "",
	"f33" => "",
	"f34" => "",
	"f35" => "",
	"f36" => "",
	"f37" => "",
	"fe2" => "1"
);

// create a new cURL resource
	$ch = curl_init();
	//curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/x-www-form-urlencoded;charset=UTF-8')); 

	// set URL and other appropriate options
	curl_setopt($ch, CURLOPT_COOKIEFILE, "cc.txt");
	
	
	//curl_setopt( $ch, CURLOPT_COOKIE, $cookie_str);
	//curl_setopt( $ch, CURLOPT_POST, 1 ); 
	curl_setopt($ch, CURLOPT_COOKIEJAR, "cc.txt"); # SAME cookiefile 
	curl_setopt($ch, CURLOPT_POSTFIELDS, $pstDta);

	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	//curl_setopt($ch, CURLOPT_VERBOSE, 1);
	curl_setopt($ch, CURLOPT_HEADER, 1);
	
$ct=array();
	
for($i=4;$i<550;$i++){
	curl_setopt($ch, CURLOPT_URL, "http://www.trulia.com/for_sale/Arlington,NJ;Carlstadt,NJ;Cliffside_Park,NJ;East_Rutherford,NJ;Edgewater,NJ;Englewood,NJ;Englewood_Cliffs,NJ;Fairview,NJ;Fort_Lee,NJ;Greenville,NJ;Guttenberg,NJ;Hoboken,NJ;Jersey_City,NJ;Kearny,NJ;Leonia,NJ;Long_Island_City,NY;Lyndhurst,NJ;Morsemere,NJ;New_York,NY;North_Bergen,NJ;Palisades_Park,NJ;Ridgefield,NJ;Roosevelt_Island,NY;Secaucus,NJ;South_Kearny,NJ;Union_City,NJ;Weehawken,NJ;West_New_York,NJ;Woodcliff,NJ/price;a_sort/CONDO,TOWNHOUSE,APARTMENT,APARTMENT|CONDO|TOWNHOUSE_type/".$i."_p");

	
	//echo gettype($pstData["f1"]);
	//continue;


	//curl_setopt($ch, CURLOPT_HEADER, 0);

	$response=curl_exec($ch);

	// Then, after your curl_exec call:
	$header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
	//$header = substr($response, 0, $header_size);
	$body = substr($response, $header_size);


	//$body=file_get_contents("sample.html");
	// Create DOM from URL or file
	$html =  str_get_html($body);
	
	//echo $body;

	// Find all images
	$cc=1;
	
	foreach($html->find('.mediaBody') as $t)
	{
	//*/
		$pr=$t->find('.listingPrice ', 0);
		$town=$t->find('span[itemprop=addressLocality] ', 0);
		if(gettype($town)=='object')
		{
			$town=$town->plaintext;
		}
		else
		{
			$town=0;
		};
		
		if(array_key_exists($town, $ct))
		{
			$town=$ct[$town];
		}
		else
		{
			$ct[$town]=count($ct);
			$town=$ct[$town];
		};
		
		if(gettype($pr)=='object')
			$pr=$pr->plaintext;
		else continue;
		$pr=intval(preg_replace('/[^0-9]/', '', $pr))/1000;
		$ar="";
		foreach($t->find('.typeTruncate div') as $fld)
		{
			if(preg_match('/.*sqft/', $fld->plaintext));
				$ar.=intval(preg_replace('/[^0-9]/', '', $fld->plaintext));
		};
	//*/
		fwrite($fh, $pr." 0".$ar." ".$town."\n");
		
		
	//	$pr=intval(preg_replace('/[^0-9]/', '', $pr));
	//	$ar=intval(preg_replace('/([0-9]+).*/', '\\1', $ar));
	//	if($ar!=0)
	//		echo ($pr/1000).",".$ar."<br>";
	};
	//ob_flush();
};
	fclose($fh);
	curl_close($ch);

//echo "град София";





?>
