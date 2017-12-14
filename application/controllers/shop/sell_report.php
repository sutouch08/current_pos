<?php
	class Sell_report extends CI_Controller
	{
		public $id_menu = 6;
		public $home;
		public $layout = "include/template";
		public $title = "รายงานการขาย";

		public function __construct()
		{
			parent:: __construct();
			$this->load->model("shop/report_model");
			$this->home = base_url()."shop/sell_report";
		}

	public function index()
	{
		$data['id_menu'] 		= $this->id_menu;
		$data['view'] 			= "shop/sell_report_view";
		$data['page_title'] 		= $this->title;
		$this->load->view($this->layout, $data);
	}

	public function get_report()
	{
		if($this->input->post("from_date") && $this->input->post('to_date') )
		{
			$from = fromDate($this->input->post('from_date'));
			$to 	= toDate($this->input->post("to_date"));
			$me   = $this->input->post('onlyMe');
			$id_emp = $me == 0 ? '' : $this->input->post('id_employee');
			$empName = employee_name($id_emp);

			$rs = $this->report_model->get_sell_data($from, $to, $id_emp);
			if($rs)
			{
				$total_dis = 0;
				$total_qty = 0;
				$total_amount = 0;
				$data = array();
				foreach($rs as $ra)
				{
					$arr = array(
								"reference" => $ra->reference,
								"item_code" => $ra->item_code,
								"price" => number_format($ra->price,2),
								"discount" => number_format($ra->total_discount,2),
								"qty" => number_format($ra->qty),
								"total_amount" => number_format($ra->total_amount,2),
								"pay_by"		=> $ra->pay_by,
								"date_upd" => thaiDate($ra->date_upd, true),
								"style"			=> $ra->style,
								"brand"		=> brandName(getIdBrandByBarcode($ra->barcode)),
								"emp"			=> $id_emp == '' ? employee_name($ra->id_employee) : $empName
								);
					array_push($data, $arr);
					$total_qty += $ra->qty; $total_dis += $ra->total_discount; $total_amount += $ra->total_amount;
				}
				$arr = array(
								"discount" => number_format($total_dis,2),
								"qty" => number_format($total_qty),
								"total_amount" => number_format($total_amount,2)
								);
				array_push($data, $arr);
				echo json_encode($data);
			}
			else
			{
				echo "fail";
			}
		}
	}

public function export_report()
{
	if($this->input->post("from_date"))
	{
		$from = fromDate($this->input->post('from_date'));
		$to 	= toDate($this->input->post("to_date"));

		//---	ออกรายงานเฉพาะพนักงานที่ login หรือไม่
		$me   = $this->input->post('onlyMe');

		$id_emp = $me == 0 ? '' : $this->input->post('id_employee');

		$empName = $id_emp == '' ? 'ทั้งหมด' : employee_name($id_emp);

		$rs = $this->report_model->get_sell_data($from, $to, $id_emp);

		if($rs)
		{
			$this->load->library('excel');
			$excel = new PHPExcel();

			$excel->setActiveSheetIndex(0);
			$excel->getActiveSheet()->setTitle('รายงานการขาย');

			$excel->getActiveSheet()->setCellValue('A1', 'รายงานการขายแยกตามรายการสินค้าและเลขที่ใบเสร็จ');
			$excel->getActiveSheet()->mergeCells('A1:M1');
			$excel->getActiveSheet()->setCellValue('A2', 'วันที่ :');
			$excel->getActiveSheet()->setCellValue('B2', thaiDate($from).' ถึง '.thaiDate($to));
			$excel->getActiveSheet()->mergeCells('B2:M2');
			$excel->getActiveSheet()->setCellValue('A3', 'พนักงาน :');
			$excel->getActiveSheet()->setCellValue('B3', $empName);
			$excel->getActiveSheet()->mergeCells('B3:M3');

			$excel->getActiveSheet()->setCellValue('A4', 'ลำดับ');
			$excel->getActiveSheet()->setCellValue('B4', 'เลขที่');
			$excel->getActiveSheet()->setCellValue('C4', 'บาร์โค้ด');
			$excel->getActiveSheet()->setCellValue('D4', 'สินค้า');
			$excel->getActiveSheet()->setCellValue('E4', 'ราคา');
			$excel->getActiveSheet()->setCellValue('F4', 'ส่วนลด');
			$excel->getActiveSheet()->setCellValue('G4', 'จำนวน');
			$excel->getActiveSheet()->setCellValue('H4', 'มูลค่า');
			$excel->getActiveSheet()->setCellValue('I4', 'ชำระโดย');
			$excel->getActiveSheet()->setCellValue('J4', 'วันที่');
			$excel->getActiveSheet()->setCellValue('K4', 'รุ่นสินค้า');
			$excel->getActiveSheet()->setCellValue('L4', 'กลุ่มสินค้า');
			$excel->getActiveSheet()->setCellValue('M4', 'พนักงาน');

			$row = 5;
			$no = 1;
			foreach($rs as $ra)
			{
				$em_name = $id_emp == '' ? employee_name($ra->id_employee) : $empName;

				$excel->getActiveSheet()->setCellValue('A'.$row, $no);
				$excel->getActiveSheet()->setCellValue('B'.$row, $ra->reference);
				$excel->getActiveSheet()->setCellValue('C'.$row, $ra->barcode);
				$excel->getActiveSheet()->setCellValue('D'.$row, $ra->item_code);
				$excel->getActiveSheet()->setCellValue('E'.$row, $ra->price);
				$excel->getActiveSheet()->setCellValue('F'.$row, $ra->total_discount);
				$excel->getActiveSheet()->setCellValue('G'.$row, $ra->qty);
				$excel->getActiveSheet()->setCellValue('H'.$row, $ra->total_amount);
				$excel->getActiveSheet()->setCellValue('I'.$row, $ra->pay_by);
				$excel->getActiveSheet()->setCellValue('J'.$row, $ra->date_upd);
				$excel->getActiveSheet()->setCellValue('K'.$row, $ra->style);
				$excel->getActiveSheet()->setCellValue('L'.$row, brandName(getIdBrandByBarcode($ra->barcode)));
				$excel->getActiveSheet()->setCellValue('M'.$row, $em_name);

				$row++;
				$no++;
			}
		}

		$file_name = 'รายงานการขาย-'.date('Ymd').'.xlsx';
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); /// form excel 2007 XLSX
		header('Content-Disposition: attachment;filename="'.$file_name.'"');
		$writer = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
		$writer->save('php://output');
	}
	else
	{
		setError("ไม่มีข้อมูล");
		redirect($this->home);
	}
}

/*
	public function export_report()
	{
		if($this->input->post("from_date"))
		{
			$from = fromDate($this->input->post('from_date'));
			$to 	= toDate($this->input->post("to_date"));
			$rs = $this->report_model->get_sell_data($from, $to);
			if($rs)
			{
				$data = array();
				$arr = array("รายงานการขาย วันที่ ".thaiDate($from)." ถึง ".thaiDate($to));
				array_push($data, $arr);
				$arr = array("เลขที่เอกสาร", "บาร์โค้ด", "รหัสสินค้า", "ราคา", "ส่วนลด", "จำนวน", "มูลค่า", "การชำระ", "วันที่", "รุ่น", "กลุ่ม", "พนักงาน");
				array_push($data, $arr);
				foreach($rs as $ra)
				{
					$arr = array(
										$ra->reference,
										$ra->barcode,
										$ra->item_code,
										$ra->price,
										$ra->total_discount,
										$ra->qty,
										$ra->total_amount,
										paymentMethod($ra->id_order),
										$ra->date_upd,
										$ra->style,
										brandName(getIdBrandByBarcode($ra->barcode)),
										employee_name(empIdByOrder($ra->id_order))
									);
					array_push($data, $arr);
				}
			}
			$this->load->library('export');
			$this->export->addArray($data);
			$this->export->excel("sell_report");
		}
		else
		{
			setError("ไม่มีข้อมูล");
			redirect($this->home);
		}
	}
*/

	public function clear_filter()
	{
		$this->session->unset_userdata("bill_search");
		$this->index();
	}



	}/// endclass


?>
