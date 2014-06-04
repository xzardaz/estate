<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class FAQ extends CI_Controller {

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
	
	//TODO: make better id generation (with string check)
	private function genId($string)
	{
		return str_replace(' ', '-', preg_replace(
						'/[^a-z\ ]/i',
						'',
						strtolower($string)));
	}

	public function index()
	{
		$jOut=array_key_exists('json', $_POST);
		$out="";
		if(!$jOut)
			$out.=$this->load->view("head");
		$this->load->model('faq_mdl');



		$faqs=$this->faq_mdl->fetch();
		$dataArr=array();
		foreach($faqs as $current)
		{
			#$arr[]="hi";
			$dataArr[]=array(
				"id"	   =>$this->genId($current->question),
				"question" =>		  $current->question,
				"answer"   =>		  $current->answer
			);
		}
		$out.=$this->load->view("faq", array("data"=>$dataArr), $jOut);
		#foreach($faqs as $current) $this->load->view("questionLink", array("q" =>$current->question,
		#								   "id"=>$this->genId($current->question)));
		#foreach($faqs as $current) $this->load->view("faqAnswer", array("id"        =>$this->genId($current->question),
		#						 		"ansHead"   =>$current->question,
		#						 		"ansContent"=>$current->answer));
		if(!$jOut)
			$out.=$this->load->view("foot");
		if($jOut)
		{
			echo json_encode(array("str"=>$out));
		};
	}
}

/* End of file faq.php */
/* Location: ./application/controllers/faq.php */
