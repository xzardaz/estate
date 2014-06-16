<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Query extends CI_Controller {


	public function faq()
	{
		$rd="";
		if($_POST["action"]=="move")
		{
			$sign="";$order="";
			if($_POST['direction']=='down')
			{
				$sign=">";
				$order="ASC";
			}
			else if($_POST['direction']=='up')
			{
				$sign="<";
				$order="DESC";
			}
			else die();

			$q="select nice, id from faqs where 1 order by nice desc limit 0,1";
			$res=$this->db->query($q)->result();
			$nicestId=intval($res[0]->id);
			$nicestNice=intval($res[0]->nice);
			$nicestPlus=$nicestNice+1;

			//get this question`s id and nice value
			$q="SELECT
				 id, nice
			    FROM
				 faqs
			    WHERE
				 MD5(CONCAT(id, question))=\"".$_POST['mdId']."\"";
			$res=$this->db->query($q)->result();
			$nice=intval($res[0]->nice);
			$id=intval($res[0]->id);


			//get next question`s id and nice value
			$q="SELECT
				 id, nice 
			    FROM faqs
			    WHERE nice $sign $nice
			    ORDER BY nice $order
			    LIMIT 0,1
				";
			$res=$this->db->query($q)->result();
			$nice2=intval($res[0]->nice);
			$id2=intval($res[0]->id);

			//TODO: one query
			$q="UPDATE `faqs` SET `nice`='$nicestPlus' WHERE `id`='$id';";
			$this->db->query($q);
			$rd.="\n$q\n";
			$q="UPDATE `faqs` SET `nice`='$nice' WHERE `id`='$id2';";
			$this->db->query($q);
			$rd.="\n$q\n";
			$q="UPDATE `faqs` SET `nice`='$nice2' WHERE `id`='$id';";
			$this->db->query($q);
			$rd.="\n$q\n";

			//echo $q;
			

			//echo $q;
		}
		else if($_POST["action"]=="list")
		{
			$q=" SELECT
				question, answer  
			     FROM
				faqs
			     WHERE
				1
			    ORDER BY nice DESC  ";
			$res=$this->db->query($q)->result();
		};
		//$res=
		//var_dump($_POST);
		$a=array("error"=>false, "query"=>"$rd", "faqs"=>"hello");
		echo json_encode($a);
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

	public function rgc()
	{
		
		echo "manyman";
	}

	public function index()
	{
		$this->load->view('welcome_message');
		//$this->load->view();
		//echo "rewrite";
	}

	public function nosql()
	{
		echo file_get_contents("dbtest.json");
	}

	public function mtwo($p1, $p2)
	{
		echo "hello $p1, $p2";
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
