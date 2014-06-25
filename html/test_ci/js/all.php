<?php
ob_start("ob_gzhandler");
header("Content-type: text/javascript");
echo "(function(){";
echo file_get_contents("jq.min.js");
ob_flush();
//echo file_get_contents("jq.min.js");
//ob_flush();
echo "\n\n//initJqUI\n\n";
echo file_get_contents("jq.ui.min.js");
//ob_flush();
echo file_get_contents("fotorama.js");
echo file_get_contents("swfobject.js");
echo file_get_contents("all.js");
echo "})();";
ob_flush();
//ob_end_flush();
?>
