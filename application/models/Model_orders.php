<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Load Composer's autoloader
require 'vendor/autoload.php';

class Model_orders extends CI_Model
{
	public function __construct()
	{
		parent::__construct();

		$this->load->model('model_tables');
		$this->load->model('model_users');
	}

	/* get the orders data */
	public function getOrdersDataByBill($bill = null)
	{
		if ($bill) {
			$sql = "SELECT * FROM orders WHERE bill_no = ?";
			$query = $this->db->query($sql, array($bill));
			return $query->row_array();
		}

		$user_id = $this->session->userdata('id');
		if ($user_id == 1) {
			$sql = "SELECT * FROM orders ORDER BY id DESC";
			$query = $this->db->query($sql);
			return $query->result_array();
		} else {
			$user_data = $this->model_users->getUserData($user_id);
			$sql = "SELECT * FROM orders WHERE store_id = ? ORDER BY id DESC";
			$query = $this->db->query($sql, array($user_data['store_id']));
			return $query->result_array();
		}
	}

	/* get the orders data */
	public function getOrdersData($id = null)
	{
		if ($id) {
			$sql = "SELECT * FROM orders WHERE id = ?";
			$query = $this->db->query($sql, array($id));
			return $query->row_array();
		}

		$user_id = $this->session->userdata('id');
		if ($user_id == 1) {
			$sql = "SELECT * FROM orders ORDER BY id DESC";
			$query = $this->db->query($sql);
			return $query->result_array();
		} else {
			$user_data = $this->model_users->getUserData($user_id);
			$sql = "SELECT * FROM orders WHERE store_id = ? ORDER BY id DESC";
			$query = $this->db->query($sql, array($user_data['store_id']));
			return $query->result_array();
		}
	}

	// get the orders item data
	public function getOrdersItemData($order_id = null)
	{
		if (!$order_id) {
			return false;
		}

		$sql = "SELECT * FROM order_items WHERE order_id = ?";
		$query = $this->db->query($sql, array($order_id));
		return $query->result_array();
	}

	//get customer data
	public function getCustomerData($number = null)
	{

		$sql = "SELECT * FROM customer WHERE phone_number = ?";
		$query = $this->db->query($sql, array($number));
		return $query->result_array();
	}
	public function create()
	{
		$user_id = $this->session->userdata('id');
		// get store id from user id 
		$user_data = $this->model_users->getUserData($user_id);
		$store_id = $user_data['store_id'];

		$bill_no = 'RCA-' . strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 4));

		$data = array(
			'bill_no' => $bill_no,
			'date_time' => strtotime(date('Y-m-d h:i:s a')),
			'gross_amount' => $this->input->post('gross_amount_value'),
			'service_charge_rate' => $this->input->post('service_charge_rate'),
			'service_charge_amount' => ($this->input->post('service_charge_value') > 0) ? $this->input->post('service_charge_value') : 0,
			'vat_charge_rate' => $this->input->post('vat_charge_rate'),
			'vat_charge_amount' => ($this->input->post('vat_charge_value') > 0) ? $this->input->post('vat_charge_value') : 0,
			'net_amount' => $this->input->post('net_amount_value'),
			'discount' => $this->input->post('discount'),
			'paid_status' => 1,
			'user_id' => $user_id,
			'table_id' => $this->input->post('table_name'),
			'store_id' => $store_id,
			'payment_method' => $this->input->post('payment_type'),
			'customer_phone' => $this->input->post('customerNumberSubmit')
		);

		$insert = $this->db->insert('orders', $data);
		$order_id = $this->db->insert_id();

		// die(var_dump($this->input->post('qty')));

		$count_product = count($this->input->post('product'));
		for ($x = 0; $x < $count_product; $x++) {
			$productId = $this->input->post('product')[$x];
			$quantity = $this->input->post('qty')[$x];
			// die(var_dump($productId));
			/*$sql = "SELECT * FROM orders WHERE id = ?";
			$query = $this->db->query($sql, array($id));
			return $query->row_array();*/


			$sql = "SELECT price FROM products WHERE id =?";
			$query = $this->db->query($sql, array($productId));
			$rate = $query->row_array();
			$rate_value = (int)($rate['price']);
			$amount = $quantity * $rate_value;
			// die(var_dump($rate));
			$items = array(
				'order_id' => $order_id,
				'product_id' => $productId,
				'qty' => $quantity,
				'rate' => $rate_value,
				'amount' => $amount
			);

			$this->db->insert('order_items', $items);
		}

		//Sending PushOver notifications
		curl_setopt_array($ch = curl_init(), array(
			CURLOPT_URL => "https://api.pushover.net/1/messages.json",
			CURLOPT_POSTFIELDS => array(
				"token" => "auaew8zodo1yj7vp3fcj9n61ksrv9n",
				"user" => "u6etkur96dmhgo73dcdj34r9m1k91g",
				"message" => base_url('orders/printDiv/'.$bill_no),
			),
			CURLOPT_SAFE_UPLOAD => true,
			CURLOPT_RETURNTRANSFER => true,
		));
		curl_exec($ch);
		curl_close($ch);

		//Sending email to Customer
		// require_once "PHPMailer/PHPMailer.php";
		// require_once "PHPMailer/SMTP.php";
		// require_once "PHPMailer/Exception.php";

		$mail = new PHPMailer(true);
		//SMTP Settings
        $mail->SMTPDebug = 2; //Alternative to above constant
        // $mail->isSMTP();
        $mail->Host = 'relay-hosting.secureserver.net';
        $mail->Port = 25;
        $mail->SMTPAuth = false;
        $mail->SMTPSecure = false;
// 		$mail -> Host = "md-48.webhostbox.net";
// 		$mail -> SMTPAuth = true;
// 		$mail -> Username = "hello@rollscameraaction.com";
// 		$mail -> Password = ".Digital@1234";
// 		$mail -> Port = 587; //587
// 		$mail -> SMTPSecure = "tls"; //tls
		
		//Email Settings
		$mail -> isHTML(true);
		$mail -> setFrom("hello@rollscameraaction.com",'Rolls Camera Action');
		$mail -> addAddress($this->input->post('customerEmailSubmit'));
		$mail -> Subject = "Your Order " . $bill_no . " has been placed!";
		$mail -> Body = "<img src=" . base_url('assets/images/logo.jpeg') ."  width='350' height='200' alt='...'></br> Thank you for visiting Rolls Camera Action! Here's your <a href =" . base_url('orders/printDiv/'.$bill_no) .">E-Bill.</a>";
		
		$mail -> send();

		
		// update the table status
		// $this->load->model('model_tables');
		// $this->model_tables->update($this->input->post('table_name'), array('available' => 2));
		// mail("murtaz1996@gmail.com","Thank you for placing your new order " . $bill_no, "Your order has been placed! Here's your E-Bill. Save trees!!" . base_url('orders/printDiv/'.$bill_no));
		return ($order_id) ? $order_id : false;
	}

	public function countOrderItem($order_id)
	{
		if ($order_id) {
			$sql = "SELECT * FROM order_items WHERE order_id = ?";
			$query = $this->db->query($sql, array($order_id));
			return $query->num_rows();
		}
	}

	public function update($id)
	{
		if ($id) {
			$user_id = $this->session->userdata('id');
			$user_data = $this->model_users->getUserData($user_id);
			$store_id = $user_data['store_id'];
			// update the table info

			$order_data = $this->getOrdersData($id);
			$data = $this->model_tables->update($order_data['table_id'], array('available' => 1));

			if ($this->input->post('paid_status') == 1) {
				$this->model_tables->update($this->input->post('table_name'), array('available' => 1));
			} else {
				$this->model_tables->update($this->input->post('table_name'), array('available' => 2));
			}

			$data = array(
				'gross_amount' => $this->input->post('gross_amount_value'),
				'service_charge_rate' => $this->input->post('service_charge_rate'),
				'service_charge_amount' => ($this->input->post('service_charge_value') > 0) ? $this->input->post('service_charge_value') : 0,
				'vat_charge_rate' => $this->input->post('vat_charge_rate'),
				'vat_charge_amount' => ($this->input->post('vat_charge_value') > 0) ? $this->input->post('vat_charge_value') : 0,
				'net_amount' => $this->input->post('net_amount_value'),
				'discount' => $this->input->post('discount'),
				'paid_status' => $this->input->post('paid_status'),
				'user_id' => $user_id,
				'table_id' => $this->input->post('table_name'),
				'store_id' => $store_id
			);

			$this->db->where('id', $id);
			$update = $this->db->update('orders', $data);

			// now remove the order item data 
			$this->db->where('order_id', $id);
			$this->db->delete('order_items');

			$count_product = count($this->input->post('product'));
			for ($x = 0; $x < $count_product; $x++) {
				$items = array(
					'order_id' => $id,
					'product_id' => $this->input->post('product')[$x],
					'qty' => $this->input->post('qty')[$x],
					'rate' => $this->input->post('rate_value')[$x],
					'amount' => $this->input->post('amount_value')[$x],
				);
				$this->db->insert('order_items', $items);
			}




			return true;
		}
	}

	public function createCustomer($data = array())
	{
		if ($data) {
			$create = $this->db->insert('customer', $data);
			return ($create == true) ? true : false;
		}
	}

	public function remove($id)
	{
		if ($id) {
			$this->db->where('id', $id);
			$delete = $this->db->delete('orders');

			$this->db->where('order_id', $id);
			$delete_item = $this->db->delete('order_items');
			return ($delete == true && $delete_item) ? true : false;
		}
	}

	public function countTotalPaidOrders()
	{
		$sql = "SELECT * FROM orders WHERE paid_status = ?";
		$query = $this->db->query($sql, array(1));
		return $query->num_rows();
	}
}
