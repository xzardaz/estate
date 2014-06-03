<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends CI_Controller {

	private function genId($string)
	{
		return str_replace(' ', '-', preg_replace(
						'/[^a-z\ ]/i',
						'',
						strtolower($string)));
	}


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
		$this->load->view('head');
		$faqs=$this->faq_mdl->fetch();
                $dataArr=array();
                foreach($faqs as $current)
                {
                        #$arr[]="hi";
                        $dataArr[]=array(
                                "id"       =>$this->genId($current->mdId),
                                "question" =>             $current->question,
                                "answer"   =>             $current->answer,
                        );
                }
                 $this->load->view("faq", array("data"=>$dataArr));

		//$this->load->view();
		//echo "rewrite";
		$this->load->view('foot');
	}


	public function faq()
	{
		$this->load->view('head');
		$this->load->model('faq_mdl');
		$faqs=$this->faq_mdl->fetch();
                $dataArr=array();
                foreach($faqs as $current)
                {
                        #$arr[]="hi";
                        $dataArr[]=array(
                                "id"       =>$this->genId($current->question),
                                "mdId" =>                 $current->mdId,
                                "question" =>             $current->question,
                                "answer"   =>             $current->answer
                        );
                }
                $this->load->view("faqa", array("data"=>$dataArr));
		$this->load->view('foot');
	}


	public function mtwo($p1, $p2)
	{
		echo "hello $p1, $p2";
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
