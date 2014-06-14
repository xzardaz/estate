<?php


$fh = @fopen('ff.text', "r") ;

while (!feof($fh))
{
    // Get the current line that the file is reading
    $currentLine = fgets($fh) ;
    //$currentLine = explode('        ',$currentLine) ;
	$els=explode(',', $currentLine);
	$els[1]=trim($els[1]);
	$q="INSERT INTO agencies (name, photo) VALUES ('".sqlite_escape_string($els[1])."', 'http://imoti.net/agency/sp_agency?id=".$els[0]."');\n";
    echo($q) ;
} 
//echo addslashes('hello"there \' \\ \ / // comment`');








?>
