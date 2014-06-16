<?php

/*/
echo "var estate=
{
	tables:
	{
		agencies: new Array();
	};
}";
//*/

echo "{\"db\": [";

$handle = fopen("results.txt", "r");
if ($handle) {
    while (($line = fgets($handle)) !== false) {
	echo "[";
	$vals=explode(",", $line);
	$len=count($vals);
	for($i=0;$i<$len;$i++)
	{
		$val=$vals[$i];
		if(!preg_match('/^[0-9]+$/', $val))
		{
			$val = "\"".addslashes(trim($val))."\"";
		};
		echo "$val".(($i!=$len-1)?",":"");
	};
        // process the line read.
	echo "],";
    }
} else {
    // error opening the file.
} 
fclose($handle);

echo "[]]}";













?>
