<?php  

defined('BASEPATH') OR exit('No direct script access allowed');

class Reports extends Admin_Controller 
{	
	public function __construct()
	{
		parent::__construct();
		$this->data['page_title'] = 'Reports';
		$this->load->model('model_reports');
		$this->load->model('model_stores');
	}

	/* 
    * It redirects to the report page
    * and based on the year, all the orders data are fetch from the database.
    */
	public function index()
	{
		if(!in_array('viewReport', $this->permission)) {
            redirect('dashboard', 'refresh');
        }
		echo ("<script>console.log('Payment Report called ');</script>");
		$today_year = date('Y');

		if($this->input->post('select_year')) {
			$today_year = $this->input->post('select_year');
		}

		$order_data = $this->model_reports->getOrderData($today_year);
		$this->data['report_years'] = $this->model_reports->getOrderYear();
		

		$final_order_data = array();
		foreach ($order_data as $k => $v) {
			
			if(count($v) > 1) {
				$total_amount_earned = array();
				foreach ($v as $k2 => $v2) {
					if($v2) {
						$total_amount_earned[] = $v2['net_amount'];						
					}
				}
				$final_order_data[$k] = array_sum($total_amount_earned);	
			}
			else {
				$final_order_data[$k] = 0;	
			}
			
		}
		
		$this->data['selected_year'] = $today_year;
		$this->data['company_currency'] = $this->company_currency();
		$this->data['results'] = $final_order_data;

		$this->render_template('reports/index', $this->data);
	}
 
	// public function storewise()
	// {

	// 	if(!in_array('viewReport', $this->permission)) {
    //         redirect('dashboard', 'refresh');
    //     }
        
	// 	$today_year = date('Y');


	// 	$store_data = $this->model_stores->getStoresData();
		

	// 	$store_id = $store_data[0]['id'];

	// 	if($this->input->post('select_store')) {
	// 		$store_id = $this->input->post('select_store');
	// 	}

	// 	if($this->input->post('select_year')) {
	// 		$today_year = $this->input->post('select_year');
	// 	}

	// 	$order_data = $this->model_reports->getStoreWiseOrderData($today_year, $store_id);
	// 	$this->data['report_years'] = $this->model_reports->getOrderYear();
		

	// 	$final_parking_data = array();
	// 	foreach ($order_data as $k => $v) {
			
	// 		if(count($v) > 1) {
	// 			$total_amount_earned = array();
	// 			foreach ($v as $k2 => $v2) {
	// 				if($v2) {
	// 					$total_amount_earned[] = $v2['net_amount'];						
	// 				}
	// 			}
	// 			$final_parking_data[$k] = array_sum($total_amount_earned);	
	// 		}
	// 		else {
	// 			$final_parking_data[$k] = 0;	
	// 		}
			
	// 	}

	// 	$this->data['selected_store'] = $store_id;
	// 	$this->data['store_data'] = $store_data;
	// 	$this->data['selected_year'] = $today_year;
	// 	$this->data['company_currency'] = $this->company_currency();
	// 	$this->data['results'] = $final_parking_data;
		
	// 	$this->render_template('reports/storewise', $this->data);
	// }

	/**
	 * This method is actually sending reports based on different payment types
	 */
	function storewise()
	{
		if(!in_array('viewReport', $this->permission)) {
            redirect('dashboard', 'refresh');
		}
		$today_year = date('Y');

		$payment_type = $this->model_reports->getPaymentTypes();

		$payment_type_id = $payment_type[0]['id'];
		
		echo ("<script>console.log('Payment Report called:".$payment_type_id."');</script>");

		if($this->input->post('select_payment_type')) {
			$payment_type_id = $this->input->post('select_payment_type');
		}
		echo ("<script>console.log('Payment Report called After select:".$payment_type_id."');</script>");
		if($this->input->post('select_year')) {
			$today_year = $this->input->post('select_year');
		}

		$order_data = $this->model_reports->getPaymentMethodWiseOrderData($today_year, $payment_type_id);
		$this->data['report_years'] = $this->model_reports->getOrderYear();
		// echo ("<script>console.log('Payment Report called After getOrderData:".$order_data."');</script>");
		// die(var_dump($order_data));

		$final_parking_data = array();
		foreach ($order_data as $k => $v) {
			
			if(count($v) > 1) {
				$total_amount_earned = array();
				foreach ($v as $k2 => $v2) {
					if($v2) {
						$total_amount_earned[] = $v2['net_amount'];						
					}
				}
				$final_parking_data[$k] = array_sum($total_amount_earned);	
			}
			else {
				$final_parking_data[$k] = 0;	
			}
			
		}

		$this->data['selected_payment_type'] = $payment_type_id;
		$this->data['payment_type'] = $payment_type;
		$this->data['selected_year'] = $today_year;
		$this->data['company_currency'] = $this->company_currency();
		$this->data['results'] = $final_parking_data;
		
		$this->render_template('reports/storewise', $this->data);
	}
}	