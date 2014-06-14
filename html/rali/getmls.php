<?php


include("./shtmld.php");
ini_set('max_execution_time', 300);

// create a new cURL resource
$ch = curl_init();

$cookie_str='__utma=106444544.221060058.1399636040.1399636040.1399636040.1; __utmb=106444544.16.10.1399636040; __utmz=106444544.1399636040.1.1.utmcsr=aop.bg|utmccn=(referral)|utmcmd=referral|utmcct=/; aop_usr=testxz; ORACLE_SMP_CHRONOS_GL=154:1399636108:960006; portal=9.0.3+en-us+us+AMERICA+F8F7792046F6E763E040A8C00C0A01A2+067B3553AEEE52E5C642E8C9E615E3416FDB78388A8C7A3105530BCC3845AE471AA3B6585B04023CD670EA77447EAFC4A389BCF7F94CE0E83A9A97A41BB89E0A8656CAF3F334352E89D5A0714073D938B2BFF3B944DAB031; __utmc=106444544; portal_url=aHR0cDovL3JvcDMtYXBwMS5hb3AuYmc6Nzc3OC9wb3J0YWwvcGFnZT9fcGFnZWlkPTkzLDEmX2RhZD1wb3J0YWwmX3NjaGVtYT1QT1JUQUw=';

$fh=fopen("ids", "r");
$fm=fopen("mm", "r+");

for($i=68002;$i<75200;$i++)
//if(true)
{
	$pid=fread($fh, 7);
	//$i=0;
	$pid=trim($pid);
	$pgNum=$i+1;
	// set URL and other appropriate options
	curl_setopt($ch, CURLOPT_URL, "http://www.aop.bg/case2.php?mode=show_doc&doc_id=".$pid."&newver=2");

	curl_setopt($ch, CURLOPT_COOKIEFILE, "cc.txt");
	//curl_setopt( $ch, CURLOPT_COOKIE, $cookie_str);
	//curl_setopt( $ch, CURLOPT_POST, 1 ); 
	curl_setopt($ch, CURLOPT_COOKIEJAR, "cc2"); # SAME cookiefile 
	//curl_setopt($ch, CURLOPT_POSTFIELDS, $data2);


	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_VERBOSE, 0);
	curl_setopt($ch, CURLOPT_HEADER, 1);

	curl_setopt($ch, CURLOPT_HEADER, 0);

	$response=curl_exec($ch);

	// Then, after your curl_exec call:
	$header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
	//$header = substr($response, 0, $header_size);
	//$body = substr($response, $header_size);
	$body=$response;
	
	
	//$arrMails=array();
	preg_match_all('/([_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3}))/i', $body, $matches);
	foreach($matches[0] as $mail)
	{
		//$arrMails[]=$mail;
		fwrite($fm, $mail."\n");
		//echo $mail;
	}
		//var_dump($matches);

	//$body=file_get_contents("sample.html");
	// Create DOM from URL or file
	//$html =  str_get_html($body);

	//echo "<hr><hr>page $pgNum count is $cc";
	echo("pr ".$i."\n");
	//echo("		id: ".$pid."\n");
	//echo $body;
	flush();

	//echo $body;
};
	//echo($i."<br>");

fclose($fh);
fclose($fm);

curl_close($ch);





?>
