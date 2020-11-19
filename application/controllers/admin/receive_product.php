<?php
/**
 *
 */
class Receive_product extends CI_Controller
{

  public $id_menu = 5;
  public $home;
  public $layout = "include/template";
  public $title = "รับสินค้า";
  public $import_path;

  public function __construct()
  {
  	parent:: __construct();
  	$this->home = base_url()."admin/promotion";
  	$this->load->model('admin/promotion_model');
  	$this->load->model("admin/product_model");
  	$this->csv_path = "images/csv";
  }
}

 ?>
