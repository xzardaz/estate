<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Browse extends CI_Controller {

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
		if(!$jOut)
			$out.=$this->load->view("head");
		if(!$jOut) $out.=$this->load->view("filters");
		if(!$jOut) $out.=$this->load->view("beginbrowse");
		$this->load->model('offer_mdl');
		$ofrs=$this->offer_mdl->getList();
		foreach($ofrs as $offer)
		{
			$data=array('offer'=>$offer);
			$out.=$this->load->view("offer", $data, $jOut);
		};
		if(!$jOut) $out.=$this->load->view("endbrowse");
		if(!$jOut) $out.=$this->load->view("foot");
		if($jOut)
		{
			echo json_encode(array("str"=>$out));
		};
	}

	public function flats()
	{
		//$this->load->model("hello_mdl");
		$out="";
		$jOut=array_key_exists('json', $_POST);
		if(!$jOut)
			$out.=$this->load->view("head");
		$out.=$this->load->view("offer", '', $jOut);
		$out.=$this->load->view("offer", '', $jOut);
		$out.=$this->load->view("offer", '', $jOut);
		$out.=$this->load->view("offer", '', $jOut);
		if(!$jOut)
			$out.=$this->load->view("foot");
		if($jOut)
		{
			echo json_encode(array("str"=>$out));
		};
		//echo $this->hello_mdl->searchFlats()[0];
		//$this->load->view("head");	
	}

	public function mtwo($p1, $p2)
	{
		echo "hello $p1, $p2";
	}
}

/* End of file browse.php */
/* Location: ./application/controllers/browse.php */
