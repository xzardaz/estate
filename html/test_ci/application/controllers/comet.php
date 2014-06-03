<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Comet extends CI_Controller {

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
		//$this->load->view('welcome_message');
		$attrs=array("cspath" => file_get_contents("/var/www/html/test_ci/application/views/style.css"));
		//echo $attrs['cspath'];
		$this->load->view('head', $attrs);
		//$this->mmtwo();
		//$this->load->view();
		//echo "rewrite";
	}
	public function mmtwo($p1="default1", $p2="")
	{
		echo "hello $p1, $p2";
	}
	
	/*/
	private function get_view_path($view_name)
	{
    		$target_file=APPPATH.'views/'.$view_name.'.php';
    		if(file_exists($target_file)) return $target_file;
	}
	//*/
}

/* End of file comet.php */
/* Location: ./application/controllers/comet.php */
