<?php
function setError($message)
{
	$c =& get_instance();
	$c->session->set_flashdata("error", $message);
}

function setMessage($message)
{
	$c =& get_instance();
	$c->session->set_flashdata("success", $message);
}

function setInfo($message)
{
	$c =& get_instance();
	$c->session->set_flashdata("info", $message);
}

function isActived($value)
{
	$icon = "<i class='fa fa-remove' style='color:red'></i>";
	if($value == "1")
	{
		$icon = "<i class='fa fa-check' style='color:green'></i>";
	}
	return $icon;
}

function is_active_btn($value, $active)
{
	if($value == 1 && $active == 1)
	{
		return "btn-success";
	}else if( $value == 1 && $active == 0 ){
		return "";
	}else if( $value == 0 && $active == 0 ){
		return "btn-danger";
	}else if( $value == 0 && $active == 1){
		return "";
	}
}

function isChecked($val1, $val2)
{
	$value = "";
	if( $val1 == $val2 )
	{
		$value = "checked='checked'";
	}
	return $value;
}

function isSelected($val1, $val2)
{
	$value = "";
	if($val1 == $val2)
	{
		$value = "selected='selected'";
	}
	return $value;
}

function selectColorGroup($id = "")
{
	$c =& get_instance();
	$option = "<option value='0'>Choose color...</option>";
	$select = "";
	$rs = $c->color_model->get_group();
	if($rs != false)
	{
		foreach($rs as $ro)
		{
			if($ro->active != 0)
			{
				if($ro->id_color_group == $id){ $select = "selected='selected'"; }else{ $select = ""; }
				$option .= "<option value='".$ro->id_color_group."' ".$select." >".$ro->group_name."</option>";
			}
		}
	}
	return $option;
}

function selectColor($id="")
{
	$c =& get_instance();
	$option = "<option value='0'>Choose color...</option>";
	$select = "";
	$rs = $c->color_model->get_color();
	if($rs != false)
	{
		foreach($rs as $ro)
		{
			if($ro->id_color == $id){ $select = "selected='selected'"; }else{ $select = ""; }
			$option .= "<option value='".$ro->id_color."' ".$select." >".$ro->color_code." : ".$ro->color_name."</option>";
		}
	}
	return $option;	
}

function selectSize($id="")
{
	$c =& get_instance();
	$c->load->model("admin/size_model");
	$option = "<option value='0'>Choose size...</option>";
	$select = "";
	$rs = $c->size_model->get_data();
	if($rs != false)
	{
		foreach($rs as $ro)
		{
			if($ro->id_size == $id){ $select = "selected='selected'"; }else{ $select = ""; }
			$option .= "<option value='".$ro->id_size."' ".$select.">".$ro->size_code." : ".$ro->size_name."</option>";
		}
	}
	return $option;
}

function selectAttribute($id="")
{
	$c =& get_instance();
	$c->load->model("admin/attribute_model");
	$option = "<option value='0'>Choose attribute...</option>";
	$select = "";
	$rs = $c->attribute_model->get_data();
	if($rs != false)
	{
		foreach($rs as $ro)
		{
			if($ro->id_attribute == $id){ $select = "selected='selected'"; }else{ $select = ""; }
			$option .= "<option value='".$ro->id_attribute."' ".$select.">".$ro->attribute_code." : ".$ro->attribute_name."</option>";
		}
	}
	return $option;
}

function selectCategory($id="")
{
	$c =& get_instance();
	$c->load->model("admin/color_model");
	$option ="<option value='1'>HOME</option>";
	$rs = $c->db->order_by("category_name", "asc")->get_where("tbl_category", array("id_category !="=>1));
	if($rs->num_rows() >0)
	{
		foreach($rs->result() as $ro)
		{
			if($ro->id_category == $id){ $select = "selected='selected'"; }else{ $select = ""; }
			$option .= "<option value='".$ro->id_category."' ".$select.">".$ro->category_name."</option>";
		}
	}
	return $option;
}

function getColorGroup($id)
{
	$c =& get_instance();
	$value = "";
	$rs = $c->db->select("group_name")->get_where("tbl_color_group", array("id_color_group"=>$id), 1);
	if($rs->num_rows == 1)
	{
		return $rs->row()->group_name;
	}else{
		return $value;
	}
}
	

function getParentCategoryName($id_parent)
{
	$c =& get_instance();
	$name = "";
	$rs = $c->db->select("category_name")->get_where("tbl_category", array("id_category"=>$id_parent), 1);
	if($rs->num_rows == 1)
	{
		$name = $rs->row()->category_name;
	}
	return $name;
}

function employee_name($id_employee)
{
	$c =& get_instance();
	$name = "";
	$rs = $c->db->select("first_name, last_name")->where("id_employee", $id_employee)->get("tbl_employee");
	if($rs->num_rows() == 1 )
	{
		$name = $rs->row()->first_name." ".$rs->row()->last_name;
	}
	return $name;
}

function getEmployeeNameByIdUser($id_user)
{
	$c =& get_instance();
	$name = "";
	$rs = $c->db->select("first_name")->join("tbl_employee","tbl_employee.id_employee = tbl_user.id_employee")->get_where("tbl_user", array("id_user"=>$id_user),1);
	if($rs->num_rows() == 1)
	{
		$name = $rs->row()->first_name;
	}
	return $name;	
}

function getCategoryById($id_category)
{
	$c =& get_instance();
	$name = "";
	$rs = $c->db->select("category_name")->get_where("tbl_category", array("id_category"=>$id_category), 1);
	if($rs->num_rows() == 1)
	{
		$name = $rs->row()->category_name;
	}
	return $name;
}

function category_product_check($id_category, $category_product="")
{
	$checked = "";
	if($category_product !="")
	{
		foreach($category_product as $rs)
		{
			if($id_category == $rs->id_category)
			{
				$checked = "checked='checked'";
			}
		}
	}
		return $checked;
}

function image_path($id_image, $use_size=1)
{
	switch($use_size)
	{
		case 1 :
		$prefix = "mini_";
		break;
		case 2 :
		$prefix = "medium_";
		break;
		case 3 :
		$prefix = "large_";
		break;
		case 4 :
		$prefix = "default_";
		break;
		default :
		$prefix = "default_";
		break;
	}
	$image_path = base_url()."images/product";
	if($id_image == "")
	{ 
		$id_image = "no_image_"; 
		return $image_path."/".$id_image.$prefix.".jpg";
	}else{
		$count = strlen($id_image);
		$path = str_split($id_image);
		$n=0;
		while($n<$count)
		{
			$image_path .= "/".$path[$n];
			$n++;
		}
	}
	return $image_path."/".$prefix.$id_image.".jpg";
}

function get_cover_image($id_product, $use_size=1)
{
	switch($use_size)
	{
		case 1 :
		$prefix = "mini";
		break;
		case 2 :
		$prefix = "medium";
		break;
		case 3 :
		$prefix = "large";
		break;
		case 4 :
		$prefix = "default";
		break;
		default :
		$prefix = "default";
		break;
	}
	$image = base_url()."images/product/no_image_".$prefix.".jpg"; 
	$c =& get_instance();
	$rs = $c->db->select("id_image")->where("id_product", $id_product)->where("cover", 1)->get("tbl_image");
	if($rs->num_rows() == 1)
	{
		$image = image_path($rs->row()->id_image, $use_size);	
	}
	return $image;
}


function product_attribute_image($id_product_attribute, $use_size=1)
{
	switch($use_size)
	{
		case 1 :
		$prefix = "mini";
		break;
		case 2 :
		$prefix = "medium";
		break;
		case 3 :
		$prefix = "large";
		break;
		case 4 :
		$prefix = "default";
		break;
		default :
		$prefix = "default";
		break;
	}
	$image = base_url()."images/product/no_image_".$prefix.".jpg";	
	$c =& get_instance();
	$rs = $c->db->select("id_image")->where("id_product_attribute", $id_product_attribute)->get("tbl_product_attribute_image");
	if($rs->num_rows() >0)
	{
		$img = $rs->row()->id_image;
	}else{
		$img = false;
	}
	if($img != false)
	{
		$image = image_path($img, $use_size);
	}else{
		$id_product = $c->db->select("id_product")->where("id_product_attribute", $id_product_attribute)->get("tbl_product_attribute")->row()->id_product;
		$image = get_cover_image($id_product, $use_size);
	}
	return $image;
}

function delete_image($id_image)
{
	$img_size = array("mini_", "medium_", "large_", "default_");
	$image_path = "images/product";
	$count = strlen($id_image);
	$path = str_split($id_image);
	$n=0;
	while($n<$count)
	{
		$image_path .= "/".$path[$n];
		$n++;
	}
	foreach($img_size as $prefix)
	{
		unlink($image_path."/".$prefix.$id_image.".jpg");
	}
}

function get_color($id)
{
	$color = "";
	$c =& get_instance();
	$rs = $c->db->where("id_color", $id)->get("tbl_color",1);
	if($rs->num_rows() == 1)
	{
		$color = $rs->row()->color_name;
	}
	return $color;
}

function get_size($id)
{
	$size = "";
	$c =& get_instance();
	$rs = $c->db->where("id_size", $id)->get("tbl_size",1);
	if($rs->num_rows() == 1)
	{
		$size = $rs->row()->size_name;
	}
	return $size;
}

function get_attribute($id)
{
	$attribute = "";
	$c =& get_instance();
	$rs = $c->db->where("id_attribute", $id)->get("tbl_attribute",1);
	if($rs->num_rows() == 1)
	{
		$attribute = $rs->row()->attribute_name;
	}
	return $attribute;
}

function get_attribute_image_id($id_product_attribute)
{
	$id_image = "";
	$c =& get_instance();
	$rs = $c->db->select("id_image")->where("id_product_attribute", $id_product_attribute)->get("tbl_product_attribute_image",1);
	if($rs->num_rows == 1)
	{
		$id_image = $rs->row()->id_image;
	}
	return $id_image;
}

function get_product_code($id_product)
{
	$code = "";
	$c =& get_instance();
	$rs = $c->db->select("product_code")->where("id_product", $id_product)->get("tbl_product",1);
	if($rs->num_rows == 1)
	{
		$code = $rs->row()->product_code;
	}
	return $code;
}

function new_reference($date = "")
{
	$c =& get_instance();
	$prefix = getConfig("PREFIX_ORDER");
	if($date == ''){ $date = date("Y-m-d"); }
	$year = date("y", strtotime($date));
	$month = date("m", strtotime($date));
	$qs = $c->db->query("SELECT MAX(reference) AS reference FROM tbl_order WHERE reference LIKE '%".$prefix."-".$year.$month."%'");
	$str = $qs->row()->reference;
	if($str !="")
	{
		$ra = explode('-', $str, 2);
		$num = $ra[1];
		$run_num = $num + 1;
		$reference = $prefix."-".$run_num;		
	}else{
		$reference = $prefix."-".$year.$month."00001";
	}
	return $reference;		
}

function new_promotion_code($date = "")
{
	$c =& get_instance();
	$prefix = getConfig("PREFIX_PROMOTION");
	if($date == ''){ $date = date("Y-m-d"); }
	$year = date("y", strtotime($date));
	$month = date("m", strtotime($date));
	$qs = $c->db->query("SELECT MAX(code) AS reference FROM tbl_promotion WHERE code LIKE '%".$prefix."-".$year.$month."%'");
	$str = $qs->row()->reference;
	if($str !="")
	{
		$ra = explode('-', $str, 2);
		$num = $ra[1];
		$run_num = $num + 1;
		$reference = $prefix."-".$run_num;		
	}else{
		$reference = $prefix."-".$year.$month."00001";
	}
	return $reference;		
}

function brandName($id_b)
{
	$name = '';
	$rs = get_instance()->db->where('id_brand', $id_b)->get('tbl_brand');
	if( $rs->num_rows() == 1 )
	{
		$name = $rs->row()->name;
	}
	return $name;
}
function getIdBrandByBarcode($barcode)
{
	$rs = get_instance()->db->select('id_brand')->where('barcode', $barcode)->get('tbl_items');
	if( $rs->num_rows() == 1 )
	{
		return $rs->row()->id_brand;
	}
	else
	{
		return 0;
	}
}

?>