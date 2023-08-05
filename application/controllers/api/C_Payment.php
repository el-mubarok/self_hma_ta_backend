<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property M_Payment_Method $modelPaymentMethod
 * @property M_Common $modelCommon
 * @property CI_Input $input
 */
class C_Payment extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();

		$this->load->model('api/M_Payment_Method', 'modelPaymentMethod');
		$this->load->model('api/M_Common', 'modelCommon');
	}

	// get all user
	public function index()
	{
		$data = [
			"message" => "user api works!",
		];

		HelperUtilsReturnJSON($this, 200, $data);
	}

	/**
	 * post params:
	 * - user id
	 * - billing session id
	 * - payment method id
	 * - va_number
	 * - amount/total
	 */
	public function postRequestPayment()
	{
		$requiredData = [
			'user_id',
			'billing_session_id',
			'payment_method_id',
			'va_number',
			'base_amount',
			'amount',
			'phone_number'
		];
		$rawData = requestPostData($this, $requiredData);
		$rawData['uuid'] = paymentCreateUUID();

		$paymentMethod = $this->modelPaymentMethod->getPaymentMethod($rawData['payment_method_id']);
		$calculate = paymentCalulate($paymentMethod, $rawData['amount'], $rawData['base_amount']);
		$user = $this->modelCommon->get('user', ['id' => $rawData['user_id']]);
		$resultData = null;

		if ($calculate->is_equal == true) {
			$data = (object) [
				'id' => $rawData['uuid'],
				'nominal' => $calculate->amount,
				'user' => $user->full_name,
				'vaNumber' => $rawData['va_number'],
				'phoneNumber' => $rawData['phone_number']
			];

			if ($paymentMethod->type == 'va') {
				$result = paymentCreateVa($paymentMethod->code, $data);
			} else if ($paymentMethod->type == 'ewallet') {
				$result = paymentCreateEwallet($paymentMethod->code, $data);
			}
			
			if ($result != null) {
				$resultData = prepareDetailsData(
					$paymentMethod, $data->id, $rawData, $calculate, $result
				);
				
				// insert to database	
				$insertId = $this->modelCommon->insert(TBL_PAYMENT_DETAILS, $resultData);
				
				$responseData = responseRequestPayment($paymentMethod->type, $result);

				HelperUtilsReturnJSON($this, 200, $responseData);
			} else {
				HelperUtilsReturnJSON($this, 400, [
					"message" => "some error occured",
					"data" => $result
				]);
			}
		} else {
			// amount not same
			HelperUtilsReturnJSON($this, 400, null);
		}
	}

	public function paymentDetailsPaymentId($paymentId) {
		$paymentDetails = $this->modelCommon->get(
			TBL_PAYMENT_DETAILS, 
			['payment_id' => $paymentId]
		);

		HelperUtilsReturnJSON($this, 200, $paymentDetails);
	}

	public function postCalculate()
	{
		$requiredData = [
			'payment_method_id',
			'base_amount',
		];
		$rawData = requestPostData($this, $requiredData);
		$paymentMethod = $this->modelPaymentMethod->getPaymentMethod($rawData['payment_method_id']);

		$result = paymentCalulate($paymentMethod, 0, $rawData['base_amount']);

		HelperUtilsReturnJSON($this, 200, [
			"ppn" => $result->ppn,
			"base_amount" => $result->base_amount,
			"amount" => $result->amount,
		]);
	}

	public function getHistory($userId) {
		$paymentDetails = $this->modelCommon->get(
			TBL_PAYMENT_DETAILS, 
			"user_id = $userId AND (status_va = 'SUCCESS' OR status_ewallet = 'SUCCEEDED')",
			false
		);

		for($i=0; $i<count($paymentDetails); $i++) {
			$billing = $this->modelCommon->get(
				TBL_BILLING_SESSION, ["id" => $paymentDetails[$i]->billing_session_id]
			);

			$paymentDetails[$i]->name = $billing->name;
		}

		HelperUtilsReturnJSON($this, 200, $paymentDetails);
	}

	public function simulatePaymentVa() {
		$email = $this->input->get('email');
		$user = $this->modelCommon->get(
			TBL_USER, 
			['email' => $email]
		);
		
		if($user) {
			$userId = $user->id;

			$paymentDetails = $this->modelCommon->get(
				TBL_PAYMENT_DETAILS,
				"user_id = $userId AND (status_va = 'ACTIVE' OR status_va = 'PENDING' OR status_ewallet = 'PENDING')",
				true,
				'id DESC'
			);

			if($paymentDetails) {
				$externalId = $paymentDetails->uuid;
				paymentSimulateVa($externalId, $paymentDetails->grand_total);

				return;
			}
		}

		echo 'failed';
	}
}
