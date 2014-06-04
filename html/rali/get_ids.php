<?php


include("./shtmld.php");

// create a new cURL resource
$ch = curl_init();

$cookie_str='__utma=106444544.221060058.1399636040.1399636040.1399636040.1; __utmb=106444544.16.10.1399636040; __utmz=106444544.1399636040.1.1.utmcsr=aop.bg|utmccn=(referral)|utmcmd=referral|utmcct=/; aop_usr=testxz; ORACLE_SMP_CHRONOS_GL=154:1399636108:960006; portal=9.0.3+en-us+us+AMERICA+F8F7792046F6E763E040A8C00C0A01A2+067B3553AEEE52E5C642E8C9E615E3416FDB78388A8C7A3105530BCC3845AE471AA3B6585B04023CD670EA77447EAFC4A389BCF7F94CE0E83A9A97A41BB89E0A8656CAF3F334352E89D5A0714073D938B2BFF3B944DAB031; __utmc=106444544; portal_url=aHR0cDovL3JvcDMtYXBwMS5hb3AuYmc6Nzc3OC9wb3J0YWwvcGFnZT9fcGFnZWlkPTkzLDEmX2RhZD1wb3J0YWwmX3NjaGVtYT1QT1JUQUw=';

$fh=fopen("ids", "a+");

for($i=0;$i<65;$i++)
//if(true)
{
	//$i=0;
	$pgNum=$i+1;
	// set URL and other appropriate options
	curl_setopt($ch, CURLOPT_URL, "http://www.aop.bg/esearch2.php?ss_type=2&ss_id=&ss_name=&mode=search&word=&ca=%F3%F7%E8%EB%E8%F9%E5&upi=&doc_id=&form_date_from=&form_date_to=&validated_on_from=&validated_on_to=&pn_doc_type=&legislation=&doc_type=&_page=$pgNum");

	curl_setopt($ch, CURLOPT_COOKIEFILE, "cc.txt");
	//curl_setopt( $ch, CURLOPT_COOKIE, $cookie_str);
	//curl_setopt( $ch, CURLOPT_POST, 1 ); 
	curl_setopt($ch, CURLOPT_COOKIEJAR, "cc2"); # SAME cookiefile 
	//curl_setopt($ch, CURLOPT_POSTFIELDS, $data2);


	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_VERBOSE, 1);
	curl_setopt($ch, CURLOPT_HEADER, 1);

	//curl_setopt($ch, CURLOPT_HEADER, 0);

	$response=curl_exec($ch);

	// Then, after your curl_exec call:
	$header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
	//$header = substr($response, 0, $header_size);
	$body = substr($response, $header_size);


	//$body=file_get_contents("sample.html");
	// Create DOM from URL or file
	$html =  str_get_html($body);

	// Find all images
	$cc=1;
	foreach($html->find('a') as $element)
	{
		if(preg_match('/\A[0-9]{5,7}\z/', $element->plaintext))
		{
		   //echo $element->plaintext . '<hr>';
		   fwrite($fh, $element->plaintext."\n");
		   $cc++;
		};
	};
	echo "<hr><hr>page $pgNum count is $cc";
	flush();
	ob_flush();
	//sleep ( 1 );

	//echo $body;
};

fclose($fh);

curl_close($ch);





?>
