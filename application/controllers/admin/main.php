<?php 
class Main extends CI_Controller
{
	public $id_menu = 0;
	public $home;
	public $layout = "include/template";
	public $title = "Welcome";
	
	public function __construct()
	{
		parent:: __construct();
		$this->load->model("admin/main_model");
		$this->home = base_url()."admin/main";
	}
	public function index()
	{	
	/*	
		$this->load->library("csvreader");
		$file_path = "assets/product.csv";
		$data['data'] = $this->csvreader->parse_file($file_path);
	*/
		$data['id_menu']	= $this->id_menu;
		$data['view']			= "admin/main_view";
		$data['page_title'] 	= "Welcome";
		$this->load->view($this->layout, $data);
	}
	
	public function backup()
	{
		if($this->main_model->backup())
		{
			echo "success";
		}else{
			echo "false";
		}
	}
	
	public function export_excel()
	{
		$language = get_lang($this->session->userdata("id_user"));
		$this->lang->load($language,$language);	
		$this->load->model("admin/product_model");
		$rs = $this->product_model->get_data();
		$body = array();
		$fields = array(1=>array("ID","Code","Name","Name [English]", "Cost", "Price","Description"));
		if($rs != false)
		{
			
			foreach($rs as $ro)
			{
				$arr = array($ro->id_product, $ro->product_code, $ro->product_name, $ro->product_name_en, $ro->product_cost, $ro->product_price, $ro->description);
				array_push($body, $arr);
			}		
		}
		$this->load->library('export');
		$this->export->addArray($fields);
		$this->export->addArray($body);
		$this->export->excel(label("products"));
		
	}
	
	public function export_csv($filename="product")
	{
		$this->load->model("admin/product_model");
		$rs = $this->product_model->getdata();
		$this->load->library('export');
		$this->export->csv("product.csv",$rs, true, "windows-874");
	}
}// End class

?>