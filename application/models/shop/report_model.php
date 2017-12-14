<?php
class Report_model extends CI_Model
{
	public function __construct()
	{
		parent:: __construct();
	}

	public function get_sell_data($from, $to, $id_emp = '')
	{
		if( $id_emp != '')
		{
			$qr  = "SELECT od.*, o.id_employee, p.pay_by FROM tbl_order_detail AS od ";
			$qr .= "JOIN tbl_order AS o ON od.id_order = o.id_order ";
			$qr .= "JOIN tbl_payment AS p ON od.id_order = p.id_order ";
			$qr .= "WHERE od.valid = 1 ";
			$qr .= "AND o.id_employee = '".$id_emp."' ";
			$qr .= "AND od.date_upd >= '".$from."' AND od.date_upd <= '".$to."' ";

			$rs = $this->db->query($qr);
		}
		else
		{
			$qr  = "SELECT od.*, o.id_employee, p.pay_by FROM tbl_order_detail AS od ";
			$qr .= "JOIN tbl_order AS o ON od.id_order = o.id_order ";
			$qr .= "JOIN tbl_payment AS p ON od.id_order = p.id_order ";
			$qr .= "WHERE od.valid = 1 ";
			$qr .= "AND od.date_upd >= '".$from."' AND od.date_upd <= '".$to."' ";

			$rs = $this->db->query($qr);
		}

		if($rs->num_rows() > 0 )
		{
			return $rs->result();
		}
		else
		{
			return false;
		}
	}

	public function getTotalSellQty($from, $to, $id_emp ='')
	{
		if( $id_emp != '')
		{
			$qr  = "SELECT SUM(od.qty) AS qty FROM tbl_order_detail AS od ";
			$qr .= "JOIN tbl_order AS o ON od.id_order = o.id_order ";
			$qr .= "WHERE od.valid = 1 ";
			$qr .= "AND o.id_employee = '".$id_emp."' ";
			$qr .= "AND od.date_upd >= '".$from."' AND od.date_upd <= '".$to."' ";
		}
		else
		{
			$qr  = "SELECT SUM(od.qty) AS qty FROM tbl_order_detail AS od ";
			$qr .= "JOIN tbl_order AS o ON od.id_order = o.id_order ";
			$qr .= "WHERE od.valid = 1 ";
			$qr .= "AND od.date_upd >= '".$from."' AND od.date_upd <= '".$to."' ";
		}

		$rs = $this->db->query($qr);
		return $rs->row()->qty;
	}

	public function getTotalSellAmount($from, $to, $id_emp ='')
	{
		if($id_emp != '')
		{
			$rs = $this->db->select_sum('order_amount')->where('id_employee = ', $id_emp)->where('date_upd >=', $from)->where('date_upd <=', $to)->get('tbl_payment');
		}
		else
		{
			$rs = $this->db->select_sum('order_amount')->where('date_upd >=', $from)->where('date_upd <=', $to)->get('tbl_payment');
		}

		return $rs->row()->order_amount;
	}

	public function getTotalSellCash($from, $to, $id_emp ='')
	{
		if($id_emp != '')
		{
			$rs = $this->db->select_sum('order_amount')->where('id_employee = ', $id_emp)->where('pay_by', 'cash')->where('date_upd >=', $from)->where('date_upd <=', $to)->get('tbl_payment');
		}
		else
		{
			$rs = $this->db->select_sum('order_amount')->where('pay_by', 'cash')->where('date_upd >=', $from)->where('date_upd <=', $to)->get('tbl_payment');
		}

		return $rs->row()->order_amount;
	}

	public function getTotalSellCard($from, $to, $id_emp='')
	{
		if( $id_emp != '')
		{
			$rs = $this->db->select_sum('order_amount')->where('id_employee = ', $id_emp)->where('pay_by', 'credit_card')->where('date_upd >=', $from)->where('date_upd <=', $to)->get('tbl_payment');
		}
		else
		{
			$rs = $this->db->select_sum('order_amount')->where('pay_by', 'credit_card')->where('date_upd >=', $from)->where('date_upd <=', $to)->get('tbl_payment');
		}

		return $rs->row()->order_amount;
	}


}// end class

?>
