<?php 

class Model_reports extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	/*getting the total months*/
	private function months()
	{
		return array('01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12');
	}

	/* getting the year of the orders */
	public function getOrderYear()
	{
		$sql = "SELECT * FROM orders WHERE paid_status = ?";
		$query = $this->db->query($sql, array(1));
		$result = $query->result_array();
		
		$return_data = array();
		foreach ($result as $k => $v) {
			$date = date('Y', $v['date_time']);
			$return_data[] = $date;
		}

		$return_data = array_unique($return_data);

		return $return_data;
	}

	// getting the order reports based on the year and months
	public function getOrderData($year)
	{	
		if($year) {
			$months = $this->months();
			
			$sql = "SELECT * FROM orders WHERE paid_status = ?";
			$query = $this->db->query($sql, array(1));
			$result = $query->result_array();

			$final_data = array();
			foreach ($months as $month_k => $month_y) {
				$get_mon_year = $year.'-'.$month_y;	

				$final_data[$get_mon_year][] = '';
				foreach ($result as $k => $v) {
					$month_year = date('Y-m', $v['date_time']);

					if($get_mon_year == $month_year) {
						$final_data[$get_mon_year][] = $v;
					}
				}
			}	

			return $final_data;
		}
	}

	public function getStoreWiseOrderData($year, $store)
	{
		if($year && $store) {
			$months = $this->months();
			
			$sql = "SELECT * FROM orders WHERE paid_status = ? AND payment_method = ?";
			$query = $this->db->query($sql, array(1, $store));
			$result = $query->result_array();

			$final_data = array();
			foreach ($months as $month_k => $month_y) {
				$get_mon_year = $year.'-'.$month_y;	

				$final_data[$get_mon_year][] = '';
				foreach ($result as $k => $v) {
					$month_year = date('Y-m', $v['date_time']);

					if($get_mon_year == $month_year) {
						$final_data[$get_mon_year][] = $v;
					}
				}
			}	
			
			return $final_data;
		}
	}

	public function getPaymentMethodWiseOrderData($year, $payment_type_id)
	{
		if($year && $payment_type_id) {
			$months = $this->months();
			
			$sql = "SELECT * FROM orders WHERE paid_status = ? AND payment_method = ?";
			$query = $this->db->query($sql, array(1, $payment_type_id));
			$result = $query->result_array();
			
			$final_data = array();
			foreach ($months as $month_k => $month_y) {
				$get_mon_year = $year.'-'.$month_y;	

				$final_data[$get_mon_year][] = '';
				foreach ($result as $k => $v) {
					$month_year = date('Y-m', $v['date_time']);

					if($get_mon_year == $month_year) {
						$final_data[$get_mon_year][] = $v;
					}
				}
			}	
			
			return $final_data;
		}
	}

	public function getPaymentTypes($id = null)
	{

		if($id) {
			$sql = "SELECT * FROM payment_type WHERE id = ?";
			$query = $this->db->query($sql, array($id));
			return $query->row_array();
		}

		$sql = "SELECT * FROM payment_type ORDER BY id ASC";
		$query = $this->db->query($sql);
		return $query->result_array();
	}
}