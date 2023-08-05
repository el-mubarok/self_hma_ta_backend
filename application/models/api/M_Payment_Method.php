<?php

class M_Payment_Method extends CI_Model
{
	protected $paymentMethod;

	public function __construct()
	{
		$this->paymentMethod = 'payment_method';
	}

	// get payment method by id
	function getPaymentMethod($id)
	{
		$this->db->where('id', $id);
		$data = $this->db->get($this->paymentMethod);

		return $data->row();
	}
}
