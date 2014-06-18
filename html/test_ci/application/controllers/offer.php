<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Offer extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -  
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		$out="";
		$jOut=array_key_exists('json', $_POST);
		if(!$jOut) $out.=$this->load->view("head");
		$this->load->model('offer_mdl');
		$offer=$this->offer_mdl->getOffer(2);
		$data=array('offer'=>$offer[0]);
		$out.=$this->load->view("ofr_detail", $data, $jOut);
		if(!$jOut) $out.=$this->load->view("foot");
		if($jOut)
		{
			echo json_encode(array("str"=>$out));
		};
	}
}

/* End of file browse.php */
/* Location: ./application/controllers/browse.php */
