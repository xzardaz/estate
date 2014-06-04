<?php


class Faq_mdl extends CI_Model
{
	private function genId($string)
	{
		return str_replace(' ', '-', preg_replace(
						'/[^a-z\ ]/i',
						'',
						strtolower($string)));
	}

	public function fetch ()
	{
		return $this->db->query("SELECT 
						`question`,
						`answer`,
						MD5(CONCAT(id, question)) as mdId
					 FROM
 						`faqs`
		 			 ORDER BY
				 		`nice`
					 ASC")->result();
	}

	public function add($question, $answer)
	{
		//TODO: check vals
		$res=$this->db->query("SELECT * FROM faqs where 1 ORDER BY nice DESC LIMIT")->result();
		foreach($res as $row)
		{
			if($this->genId($row->question)==$this->genId($question))
			{
				die("already have");//TODO: handle
			};
		};

		$nice=intval($res[0]->nice)+1;
		$insQ="INSERT INTO `estate`.`faqs` (
				`question` ,
				`answer` ,
				`nice`
			)
			VALUES (
				'$question', '$answer', '$nice'
			);";
		$this->db->query($insQ);
		echo $insQ;
	}

	public function __construct()
	{
		//$this->add("cmdq", "cmdVal");
	}
}
