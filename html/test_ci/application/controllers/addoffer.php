<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Addoffer extends CI_Controller {

	public function index()
	{
		$types=array
		(
			array(
				'name'=>'Flat',
				'icon'=>base_url().'imgs/icons/'.'flat.png',
				'id'=>1
			),
			array(
				'name'=>'House',
				'icon'=>base_url().'imgs/icons/'.'house.png',
				'id'=>2
			),
			array(
				'name'=>'Field',
				'icon'=>base_url().'imgs/icons/'.'field.png',
				'id'=>3
			),
			array(
				'name'=>'Garage',
				'icon'=>base_url().'imgs/icons/'.'garage.png',
				'id'=>4
			)
		);
		$out="";
		$jOut=array_key_exists('json', $_POST);
		if(!$jOut)
			$out.=$this->load->view("head");

		$out.=$this->load->view("offer_add", Array('types'=>$types), $jOut);

		if(!$jOut)
			$out.=$this->load->view("foot");
		if($jOut)
		{
			echo json_encode(array("str"=>$out));
		};
	}


}

/* End of file browse.php */
/* Location: ./application/controllers/browse.php */
