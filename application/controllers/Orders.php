<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Orders extends Admin_Controller
{
	var $currency_code = '';

	public function __construct()
	{
		parent::__construct();

		// $this->not_logged_in();

		$this->data['page_title'] = 'Orders';

		$this->load->model('model_orders');
		$this->load->model('model_tables');
		$this->load->model('model_products');
		$this->load->model('model_category');
		$this->load->model('model_company');
		$this->load->model('model_stores');

		$this->currency_code = $this->company_currency();
	}

	/* 
	* It only redirects to the manage order page
	*/
	public function index()
	{
		if (!in_array('viewOrder', $this->permission)) {
			redirect('dashboard', 'refresh');
		}

		$this->data['page_title'] = 'Manage Orders';
		$this->render_template('orders/index', $this->data);
	}

	/*
	* Fetches the orders data from the orders table 
	* this function is called from the datatable ajax function
	*/
	public function fetchOrdersData()
	{
		if (!in_array('viewOrder', $this->permission)) {
			redirect('dashboard', 'refresh');
		}

		$result = array('data' => array());

		$data = $this->model_orders->getOrdersData();

		foreach ($data as $key => $value) {

			$store_data = $this->model_stores->getStoresData($value['store_id']);

			$count_total_item = $this->model_orders->countOrderItem($value['id']);
			$date = date('d-m-Y', $value['date_time']);
			$time = date('h:i a', $value['date_time']);

			$date_time = $date . ' ' . $time;

			// button
			$buttons = '';

			if (in_array('viewOrder', $this->permission)) {
				$buttons .= '<a target="__blank" href="' . base_url('orders/printDiv/' . $value['bill_no']) . '" class="btn btn-default"><i class="fa fa-print"></i></a>';
			}

			if (in_array('updateOrder', $this->permission)) {
				$buttons .= ' <a href="' . base_url('orders/update/' . $value['id']) . '" class="btn btn-default"><i class="fa fa-pencil"></i></a>';
			}

			if (in_array('deleteOrder', $this->permission)) {
				$buttons .= ' <button type="button" class="btn btn-default" onclick="removeFunc(' . $value['id'] . ')" data-toggle="modal" data-target="#removeModal"><i class="fa fa-trash"></i></button>';
			}

			if ($value['paid_status'] == 1) {
				$paid_status = '<span class="label label-success">Paid</span>';
			} else {
				$paid_status = '<span class="label label-warning">Not Paid</span>';
			}

			$result['data'][$key] = array(
				$date_time,
				$value['bill_no'],
				$store_data['name'],
				$count_total_item,
				$value['net_amount'],
				$paid_status,
				$buttons
			);
		} // /foreach

		echo json_encode($result);
	}

	//Get products based on the category selected
	public function getOrderProductDataByCategory($category)
	{
		$result = array('data' => array());

		$data = $this->model_products->getProductData();
		$company_info = $this->model_company->getCompanyData(1);

		foreach ($data as $key => $value) {

			// card
			$card = '<div class="container no-space">
			
				<div class="col s12 no-space">
					<div class="card horizontal no-space">
						<div class="card-image"><img src="' . base_url($value['image']) . '"></div>
						<div class="card-stacked">
							<div class="card-content">
								<h4>' . $value['name'] . '</h4>
								<h5>' . $this->currency_code . ' ' . $value['price'] . '</h5>
							</div>
						</div>
					</div>
			
			</div>
		</div>';

			//quantity
			$quantity = '<input class="col s12" disabled type="number" name="qty[]" id="qty_' . $value['id'] . '" class="form-control" onchange="getTotal(' . $value['id'] . ',' . $value['price'] . ')">';

			//checkbox
			$checkbox = '<p class="col s12"><label><input data-row-id="row_' . $value['id'] . '" value=' . $value['id'] . ' quantity-id="qty_' . $value['id'] . '" price=' . $value['price'] . ' id="product_' . $value['id'] . '" name="product[]" type="checkbox" class="filled-in order-check" onclick="getProductData(' . $value['id'] . ',' . $value['price'] . ')"><span></span></label> </p>';

			$result['data'][$key] = array(
				$card,
				$quantity,
				$checkbox
			);
		} // /foreach

		echo json_encode($result);
	}



	public function getOrderProductData()
	{
		$result = array('data' => array());

		$data = $this->model_products->getProductData();
		$company_info = $this->model_company->getCompanyData(1);

		foreach ($data as $key => $value) {

			$healthy = ($value['is_healthy'] == 1) ? '<span class="label label-success">Healthy</span>' : '<span class="label label-warning">Regular</span>';

			$veg = ($value['is_veg'] == 1) ? '<span class="label label-success">Vegetarian</span>' : '<span class="label label-warning">Non-Veg</span>';


			// card
			$card = '<div class="container no-space">
			
				<div class="col s12 no-space">
					<div class="card horizontal no-space">
						<div class="card-image"><img src="' . base_url($value['image']) . '"></div>
						<div class="card-stacked">
							<div class="card-content">
								<h4>' . $value['name'] . '</h4>
								<h5>' . $this->currency_code . ' ' . $value['price'] . '</h5>
								<p>' . $healthy . '</p><br>
								<p>' . $veg . '</p>
							</div>
						</div>
					</div>
			
			</div>
		</div>';

			//quantity
			$quantity = '<input class="col s12" disabled type="number" name="qty[]" id="qty_' . $value['id'] . '" class="form-control" onchange="getTotal(' . $value['id'] . ',' . $value['price'] . ')">';

			//checkbox
			$checkbox = '<p class="col s12"><label><input data-row-id="row_' . $value['id'] . '" value=' . $value['id'] . ' quantity-id="qty_' . $value['id'] . '" price=' . $value['price'] . ' id="product_' . $value['id'] . '" name="product[]" type="checkbox" class="filled-in order-check" onclick="getProductData(' . $value['id'] . ',' . $value['price'] . ')"><span></span></label> </p>';

			$result['data'][$key] = array(
				$card,
				$quantity,
				$checkbox,
			);
		} // /foreach

		echo json_encode($result);
	}
	/**
	 * This method gets the Customer data based on the phone number entered
	 */
	public function getCustomerData()
	{
		$phoneNumber = $this->input->post('phone_number');
		if ($phoneNumber) {
			$data = $this->model_orders->getCustomerData($phoneNumber);
			echo json_encode($data);
		}
	}
	/**
	 * This method inserts the customer data if the phone number doesn't exist in the DB
	 */
	public function insertNewCustomer()
	{
		$response = array();
		$this->form_validation->set_rules('customer_number', 'Customer number', 'trim|required');
		$this->form_validation->set_rules('customer_name', 'Customer name', 'trim|required');
		$this->form_validation->set_rules('customer_email', 'Customer email', 'trim|required');

		if ($this->form_validation->run() == TRUE) {
			$data = array(
				'name' => $this->input->post('customer_name'),
				'phone_number' => $this->input->post('customer_number'),
				'email_address' => $this->input->post('customer_email')
			);

			$create = $this->model_orders->createCustomer($data);
			if ($create == true) {
				$response['success'] = true;
				$response['messages'] = 'Succesfully created';
			} else {
				$response['success'] = false;
				$response['messages'] = 'Error in the database while creating the brand information';
			}
		} else {
			$response['success'] = false;
			foreach ($_POST as $key => $value) {
				$response['messages'][$key] = form_error($key);
			}
		}
		echo json_encode($response);
	}

	/*
	* If the validation is not valid, then it redirects to the create page.
	* If the validation for each input field is valid then it inserts the data into the database 
	* and it stores the operation message into the session flashdata and display on the manage group page
	*/
	public function create()
	{

		if (!in_array('createOrder', $this->permission)) {
			redirect('dashboard', 'refresh');
		}

		$this->data['page_title'] = 'Add Order';
		// $payment_type = $this->input->post('payment_type');

		$this->form_validation->set_rules('product[]', 'Product name', 'trim|required');
		if ($this->form_validation->run() == TRUE) {
			$order_id = $this->model_orders->create();

			// if ($order_id) {
			// 	$this->session->set_flashdata('success', 'Successfully created');
			// 	redirect('orders/update/' . $order_id, 'refresh');
			// } else {

			// 	$this->session->set_flashdata('errors', 'Error occurred!!');
			// 	redirect('orders/create/', 'refresh');
			// }

			if ($order_id) {
				$this->session->set_flashdata('success', 'Successfully created');

				//	return response()->json(array('message' => $schedule['formattedDate'] , 'success' => 0));
				$response = array('redirectURL' => base_url('orders/update/' . $order_id));
				//	echo json_encode(array('redirectURL' => redirect('orders/update/' . $order_id, 'refresh')));

				echo json_encode($response);
			} else {

				$this->session->set_flashdata('errors', 'Error occurred!!');
				$response = array('redirectURL' => base_url('orders/create/' . $order_id));
				echo json_encode($response);
				// echo json_encode(array('redirectURL' => redirect('orders/create/', 'refresh')));
			}
		} else {
			// false case
			echo ("<script>console.log('Create in Orders called ELSE CONDITION:');</script>");
			$this->data['table_data'] = $this->model_tables->getActiveTable();
			$this->data['category_data'] = $this->model_category->getActiveCategory();
			$company = $this->model_company->getCompanyData(1);
			$this->data['company_data'] = $company;
			$this->data['is_vat_enabled'] = ($company['vat_charge_value'] > 0) ? true : false;
			$this->data['is_service_enabled'] = ($company['service_charge_value'] > 0) ? true : false;

			$this->data['products'] = $this->model_products->getActiveProductData();

			$this->render_template('orders/create', $this->data);
		}
	}

	/*
	* It gets the product id passed from the ajax method.
	* It checks retrieves the particular product data from the product id 
	* and return the data into the json format.
	*/
	public function getProductValueById()
	{
		$product_id = $this->input->post('row_id');
		if ($product_id) {
			$product_data = $this->model_products->getProductData($product_id);
			echo json_encode($product_data);
		}
	}

	/*
	* It gets the all the active product inforamtion from the product table 
	* This function is used in the order page, for the product selection in the table
	* The response is return on the json format.
	*/
	public function getTableProductRow()
	{
		$products = $this->model_products->getProductData();
		echo json_encode($products);
	}

	/*
	* If the validation is not valid, then it redirects to the edit orders page 
	* If the validation is successfully then it updates the data into the database 
	* and it stores the operation message into the session flashdata and display on the manage group page
	*/
	public function update($id)
	{
		if (!in_array('updateOrder', $this->permission)) {
			redirect('dashboard', 'refresh');
		}

		if (!$id) {
			redirect('dashboard', 'refresh');
		}



		$this->data['page_title'] = 'Update Order';

		$this->form_validation->set_rules('product[]', 'Product name', 'trim|required');


		if ($this->form_validation->run() == TRUE) {

			$update = $this->model_orders->update($id);

			if ($update == true) {
				$this->session->set_flashdata('success', 'Successfully updated');
				redirect('orders/update/' . $id, 'refresh');
			} else {
				$this->session->set_flashdata('errors', 'Error occurred!!');
				redirect('orders/update/' . $id, 'refresh');
			}
		} else {
			// false case
			$this->data['table_data'] = $this->model_tables->getActiveTable();

			$company = $this->model_company->getCompanyData(1);
			$this->data['company_data'] = $company;
			$this->data['is_vat_enabled'] = ($company['vat_charge_value'] > 0) ? true : false;
			$this->data['is_service_enabled'] = ($company['service_charge_value'] > 0) ? true : false;

			$result = array();
			$orders_data = $this->model_orders->getOrdersData($id);

			if (empty($orders_data)) {
				$this->session->set_flashdata('errors', 'The request data does not exists');
				redirect('orders', 'refresh');
			}

			$result['order'] = $orders_data;
			$orders_item = $this->model_orders->getOrdersItemData($orders_data['id']);

			foreach ($orders_item as $k => $v) {
				$result['order_item'][] = $v;
			}

			$table_id = $result['order']['table_id'];
			$table_data = $this->model_tables->getTableData($table_id);

			$result['order_table'] = $table_data;

			$this->data['order_data'] = $result;

			$this->data['products'] = $this->model_products->getActiveProductData();



			$this->render_template('orders/edit', $this->data);
		}
	}

	/*
	* It removes the data from the database
	* and it returns the response into the json format
	*/
	public function remove()
	{
		if (!in_array('deleteOrder', $this->permission)) {
			redirect('dashboard', 'refresh');
		}

		$order_id = $this->input->post('order_id');

		$response = array();
		if ($order_id) {
			$delete = $this->model_orders->remove($order_id);
			if ($delete == true) {
				$response['success'] = true;
				$response['messages'] = "Successfully removed";
			} else {
				$response['success'] = false;
				$response['messages'] = "Error in the database while removing the product information";
			}
		} else {
			$response['success'] = false;
			$response['messages'] = "Refersh the page again!!";
		}

		echo json_encode($response);
	}

	/*
	* It gets the product id and fetches the order data. 
	* The order print logic is done here 
	*/
	public function printDiv($bill)
	{
		// if (!in_array('viewOrder', $this->permission)) {
		// 	redirect('dashboard', 'refresh');
		// }

		if ($bill) {
			$order_data = $this->model_orders->getOrdersDataByBill($bill);
		  $orders_items = $this->model_orders->getOrdersItemData($order_data['id']);
			$company_info = $this->model_company->getCompanyData(1);
			// $store_data = $this->model_stores->getStoresData($order_data['store_id']);

			$order_date = date('d/m/Y', $order_data['date_time']);
			$paid_status = ($order_data['paid_status'] == 1) ? "Paid" : "Unpaid";

			// $table_data = $this->model_tables->getTableData($order_data['table_id']);

			if ($order_data['discount'] > 0) {
				$discount = $this->currency_code . ' ' . $order_data['discount'];
			} else {
				$discount = '0.0';
			}

			$html = '<!-- Main content -->
			<!DOCTYPE html>
			<html>
			<head>
			  <meta charset="utf-8">
			  <meta http-equiv="X-UA-Compatible" content="IE=edge">
			  <title>Invoice</title>
			  <!-- Tell the browser to be responsive to screen width -->
			  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
			  <!-- Bootstrap 3.3.7 -->
			  <link rel="stylesheet" href="'.base_url('assets/bower_components/bootstrap/dist/css/bootstrap.min.css').'">
			  <!-- Font Awesome -->
			  <link rel="stylesheet" href="'.base_url('assets/bower_components/font-awesome/css/font-awesome.min.css').'">
			  <link rel="stylesheet" href="'.base_url('assets/dist/css/AdminLTE.min.css').'">
			</head>
			<body onload="window.print();">
			
			<div class="wrapper">
			  <section class="invoice">
			    <!-- title row -->
			    <div class="row">
			      <div class="col-xs-12">
			        <h2 class="page-header">
							<img src="/restaurant/assets/images/logo.jpeg"  width="350" height="200" alt="...">
			          <small class="pull-right">Date: '.$order_date.'</small>
			        </h2>
			      </div>
			      <!-- /.col -->
			    </div>
			    <!-- info row -->
			    <div class="row invoice-info">
			      
			      <div class="col-sm-4 invoice-col">
			        <b>Bill ID: </b> '.$order_data['bill_no'].'<br>
			        <b>Total items: </b> '.count($orders_items).'<br><br>
			      </div>
			      <!-- /.col -->
			    </div>
			    <!-- /.row -->

			    <!-- Table row -->
			    <div class="row">
			      <div class="col-xs-12 table-responsive">
			        <table class="table table-striped">
			          <thead>
			          <tr>
			            <th>Product name</th>
			            <th>Price</th>
			            <th>Qty</th>
			            <th>Amount</th>
			          </tr>
			          </thead>
			          <tbody>'; 

			          foreach ($orders_items as $k => $v) {

			          	$product_data = $this->model_products->getProductData($v['product_id']); 
			          	
			          	$html .= '<tr>
				            <td>'.$product_data['name'].'</td>
				            <td>'.$this->currency_code . ' ' .$v['rate'].'</td>
				            <td>'.$v['qty'].'</td>
				            <td>'.$this->currency_code . ' ' .$v['amount'].'</td>
			          	</tr>';
			          }
			          
			          $html .= '</tbody>
			        </table>
			      </div>
			      <!-- /.col -->
			    </div>
			    <!-- /.row -->

			    <div class="row">
			      
			      <div class="col-xs-8 pull-right">

			        <div class="table-responsive">
			          <table class="table">
			            <tr>
			              <th style="width:50%">Gross Amount:</th>
			              <td>'.$this->currency_code . ' ' .$order_data['gross_amount'].'</td>
			            </tr>';

			            if($order_data['service_charge_amount'] > 0) {
			            	$html .= '<tr>
				              <th>Service Charge ('.$order_data['service_charge_rate'].'%)</th>
				              <td>'.$this->currency_code .' '.$order_data['service_charge_amount'].'</td>
				            </tr>';
			            }

			            if($order_data['vat_charge_amount'] > 0) {
			            	$html .= '<tr>
				              <th>GST ('.$order_data['vat_charge_rate'].'%)</th>
				              <td>'.$this->currency_code .' '.$order_data['vat_charge_amount'].'</td>
				            </tr>';
			            }
			            
			            
			            $html .=' <tr>
			              <th>Discount:</th>
			              <td>'.$discount.'</td>
			            </tr>
			            <tr>
			              <th>Net Amount:</th>
			              <td>'.$this->currency_code . ' ' .$order_data['net_amount'].'</td>
			            </tr>
			            <tr>
			              <th>Paid Status:</th>
			              <td>'.$paid_status.'</td>
			            </tr>
			          </table>
			        </div>
			      </div>
			      <!-- /.col -->
					</div>
					<small text-align:center>FAMM Food & Beverages</small>
			    <!-- /.row -->
			  </section>
			  <!-- /.content -->
			</div>
		</body>
	</html>';

			echo $html;
		}
	}
}


