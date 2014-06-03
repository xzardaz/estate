<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Addoffer extends CI_Controller {

	public function index()
	{
		$out="";
		$jOut=array_key_exists('json', $_POST);
		if(!$jOut)
			$out.=$this->load->view("head");

		$out.=$this->load->view("offer_add", '', $jOut);

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
