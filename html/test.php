<?php



include("./test_ci/application/libraries/Offers_fetch.php");

$sarr=array(
	"encryption"=>false,
	"seed"=>1234572,
	"engine"=>"innodb",
	"cache"=>"files",
	"tablesMap"=>array
	(
		"offers"=>array
		(
			"id"=>array
			(
				"fType"  => "int",
				"size"   => 3,
				"range"  => false,
				"index" => "primary"
			),
			"photo"=>array
			(
				"fType"  => "int",
				"size"   => 3,
				"range"  => false,
				"index" => "unique"
			),
			"agency"=>array
			(
				"fType"  => "int",
				"size"   => 2,
				"range"  => false,
				"index" => "unique"
			),
			"price"=>array
			(
				"fType"  => "int",
				"size"   => 4,
				"index"  => false,
				"range"  => true
			),
			"area"=>array
			(
				"fType"  => "int",
				"size"   => 3,
				"index"  => false,
				"range"  => true
			),
			"date"=>array
			(
				"fType"  => "int",
				"size"   => 3,
				"index"  => false,
				"range"  => true
			),
			"rooms"=>array
			(
				"fType"  => "int",
				"size"   => 1,
				"index"  => false,
				"range"  => true
			)
		),
		"agencies"=>array
		(
			"id"=>array
			(
				"fType"  => "int",
				"size"   => 2,
				"index"  => "primary",
				"range"  => false
			),
			"name"=>array
			(
				"fType"  => "varchar",
				"size"   => 255,
				"index"  => false, 
				"range"  => false
			),
			"mail"=>array
			(
				"fType"  => "email",
				"size"   => 1,
				"index"  => false,
				"range"  => false
			),
			"banner"=>array
			(
				"fType"  => "varchar",
				"size"   => 2,
				"index"  => false,
				"range"  => false
			)
		),
		"photo"=>array
		(
			"id"=>array
			(
				"fType"  => "int",
				"size"   => 2,
				"index"  => "primary",
				"range"  => false
			),
			"offer"=>array
			(
				"fType"  => "int",
				"size"   => 4,
				"index"  => false, 
				"range"  => false
			),
			"type"=>array
			(
				"fType"  => "int",
				"size"   => 1,
				"index"  => false,
				"range"  => false
			),
			"path"=>array
			(
				"fType"  => "varchar",
				"size"   => 40,
				"index"  => false,
				"range"  => false
			)
		)
	)
);


file_put_contents("sfile", serialize($sarr));





$cc=new Offers_fetch("sfile");
