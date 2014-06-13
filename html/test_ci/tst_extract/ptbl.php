<?php


$fh = @fopen('ff.txt', "r") ;

while (!feof($fh))
{
    // Get the current line that the file is reading
    $currentLine = fgets($fh) ;
    //$currentLine = explode('        ',$currentLine) ;
	$els=explode(',', $currentLine);
	$els[1]=trim($els[1]);
	$q="INSERT INTO `estate`.`agencies` (id, name, photo) VALUES (NULL, '".$els[1]."', 'http://imoti.net/agency/sp_agency?id=".$els[0]."')\n";
    echo($q) ;
} 









?>
