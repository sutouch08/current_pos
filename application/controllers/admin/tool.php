<?php
class Tool extends CI_Controller
{
	public function __construct()
	{
		parent:: __construct();
	}
	
		
	public function set_rows()
	{
		$cookie = array("name"=>"row", "value"=>$this->input->post('rows'), "expire"=>"865000000");
		$this->input->set_cookie($cookie);
		if($this->input->cookie('row'))
		{
			echo "success";
		}
		else
		{
			echo "fail";
		}
		
	}
	
}/// endclass


?>