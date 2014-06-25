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
		$this->index_main(array());
	}

	private function index_main($filters)
	{
		if(!isset($filters)) $filters=array();
		$out="";
		$jOut=array_key_exists('json', $_GET)||array_key_exists('json', $_POST);

		if(!$jOut) $out.=$this->load->view("head");
	
		$out.=$this->load->view("filters", NULL, $jOut);
		$out.=$this->load->view("beginbrowse", NULL, $jOut);
		$this->load->model('offer_mdl');
		$ofrs=$this->offer_mdl->getList($filters);
		foreach($ofrs as $offer)
		{
			$data=array('offer'=>$offer);
			$out.=$this->load->view("offer", $data, $jOut);
		};
		$out.=$this->load->view("endbrowse", NULL, $jOut);
		if(!$jOut) $out.=$this->load->view("foot");
		if($jOut)
		{
			echo json_encode(array("str"=>$out));
			//echo (($out));
		};
	}

	public function flats()
	{
		$this->index_main(array("type" => 1));
	}

	public function stores()
	{
		$this->index_main(array("type" => 2));
	}

	public function garages()
	{
		$this->index_main(array("type" => 3));
	}

	public function field()
	{
		$this->index_main(array("type" => 4));
	}

	public function houses()
	{
		$this->index_main(array("type" => 5));
	}

	public function mtwo($p1, $p2)
	{
		echo "hello $p1, $p2";
	}
}

/* End of file browse.php */
/* Location: ./application/controllers/browse.php */
