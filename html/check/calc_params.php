<?php
require_once( './PolynomialRegression.php' ); 


  // Precision digits in BC math.
  bcscale( 10 ); 
$polynomialRegression = new PolynomialRegression( 3 ); 

$fh=fopen("data2.ssv", 'r');
$n=0;

$ex=0;
$ey=0;
$exy=0;
$ex2=0;
if ($fh) {
    while (($line = fgets($fh)) !== false) {
        // process the line read.
        $vals=explode(" ", $line);
        if(intval($vals[2])==18)
        {
			$tx=intval($vals[1]);
			$ty=intval($vals[0]);
			if($tx*$ty!=0)
			{
				$polynomialRegression->addData( $ty, $tx ); 
				//$ex=$tx;
				//$ey=$ty;
				//$exy+=$tx*$ty;
				//$ex2+=$tx*$tx;
				//$n++;
			}
		};
    }
}
else {
    // error opening the file.
}; 
//$b1=($exy-($ex*$ey)/$n)/($ex2-($ex*$ex)/2);
//$b0=($ey-$b1*$ex)/$n;
//echo $b1.":".$b0;
//echo $n;

$coefficients = $polynomialRegression->getCoefficients(); 
  $functionText = "f( x ) = ";
  foreach ( $coefficients as $power => $coefficient )
  {
    if ( $power > 0 )
      $functionText .= " + ";

    $functionText .= round( $coefficient, 100 );

    if ( $power > 0 )
    {
      $functionText .= "x";
      if ( $power > 1 )
        $functionText .= "^" . $power;
    }
  } 
  
echo($functionText);
fclose($fh);





?>
