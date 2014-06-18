<?php

/*
the filter structure(NULL == not set):

	filter
	{
		price='([\<\>\=]+[0-9]+)+' //sample: "<4000" (less than 4000), ">500" (greater than 500), "<4000>500" (greater than 500 AND less than 400) (can get in confusion with ">4000<500") 
		location="locname"/for now
		agency="agency.x"
		offerType=int
		date='([\<\>\=]+[0-9+])+' //the same as price, but with timestamp
		page-int
	}
	wanted="t1.f1,t2.f2"//...and so on...

sqmple query:

SELECT
	offers.area, offers.locationName, offers.brief, offers.type, offers.rooms, offers.price, offers.dateAdded,
	photos.path,
	agencies.name, agencies.banner, agencies.id
FROM 
	`offers` LEFT JOIN `photos`
		ON offers.photo = photos.id
	 				LEFT JOIN `agencies`
						ON agencies.id = offers.agency

WHERE 1
*/

class Offer_mdl extends CI_Model
{
	public function fetch ($criteria)
	{
		//$c=new $this->offers_fetch();
		//$c->price="<6000000";//criteria for now;
		//$c->offerType=2;
		//$c->wanted="*";
		$q="
SELECT
	offers.area, offers.locationName, offers.brief, offers.type, offers.rooms, offers.price, offers.dateAdded,
	photos.path,
	agencies.name, agencies.banner, agencies.id
FROM 
	`offers` LEFT JOIN `photos`
		ON offers.photo = photos.id
	 				LEFT JOIN `agencies`
						ON agencies.id = offers.agency
WHERE
";

		//$q.=($c->type)?" offer.type='".$c->type."' ":'';
		//$q.=($c->type)?" offer.type='".$c->type."' ":'';
	}

	public function getList()
	{
		$q='SELECT
			price,
			area,
			rooms,
			offer.type,
			offer.brief,
			agencies.name as agname,
			agencies.photo as aglogo,
			photos.path as photo
		    FROM 
			(
				`offer` LEFT JOIN `agencies` 
					ON
				`offer`.`agencyId` = `agencies`.`id`
			)
			LEFT JOIN photos 
				ON 
			photos.id = offer.frontPhotoId
		    WHERE 1';
		$res=$this->db->query($q)->result();
		return $res;
	}

	public function getOffer($id)
	{
		$q='SELECT
			price,
			area,
			rooms,
			offer.type,
			offer.brief,
			agencies.name as agname,
			agencies.photo as aglogo,
			photos.path as photo
		    FROM 
			(
				`offer` LEFT JOIN `agencies` 
					ON
				`offer`.`agencyId` = `agencies`.`id`
			)
			LEFT JOIN photos 
				ON 
			photos.id = offer.frontPhotoId
		    WHERE offer.id='.$id;
		$res=$this->db->query($q)->result();
		return $res;
	}

	public function addOffer($ofrProps)
	{
		$q="
	INSERT INTO
		`estate`.`offer`
		(
			`agencyId` ,
			`frontPhotoId` ,
			`area` ,
			`rooms` ,
			`price` ,
			`type` ,
			`ansoon` ,
		)
	VALUES
	(
		1,
		25,
		120,
		4,
		40000,
		4,
		'brief'
	);
		";
	}



}
