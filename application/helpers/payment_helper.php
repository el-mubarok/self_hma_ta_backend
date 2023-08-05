<?php

use Ramsey\Uuid\Uuid;
use Xendit\Xendit;

// require "vendor/autoload.php";
define('XDEV_SECRET', 'xnd_development_Vg2HohPA6yEr8RZtRtgxlCVpbKydjPuUheFJFTExLYhIJCQLZXHfjCY9tawM6rA');
define('SUCCESS_REDIRECT', 'https://el-mubarok.github.io/self-success-page/?bg=#DF3526&color=#fff');

function paymentCreateVa($code, $data)
{
	Xendit::setApiKey(XDEV_SECRET);

	$id = $data->id;
	$nominal = $data->nominal;
	$user = $data->user;
	$vaNumber = $data->vaNumber;

	try {
		$params = [
			"external_id" => $id,
			"bank_code" => $code,
			"name" => $user,
			"virtual_account_number" => $vaNumber,
			"is_single_use" => false,
			"expected_amount" => $nominal,
			"is_closed" => true,
			// "expiration_date" => (new DateTime(date('Y-m-d H:i:s')))->modify('+1 day')->format('Y-m-d H:i:s'),
			"expiration_date" => (new DateTime(date('Y-m-d H:i:s')))->modify('+5 minutes')->format('Y-m-d H:i:s'),
		];

		return \Xendit\VirtualAccounts::create($params);
	} catch (Exception $e) {
		return null;
	} catch (\Xendit\Exceptions\ApiException $e) {
		return null;
	}
}

function paymentCreateEwallet($code, $data)
{
	Xendit::setApiKey(XDEV_SECRET);

	$id = $data->id;
	$nominal = $data->nominal;
	$phoneNumber = $data->phoneNumber;

	try {
		$params = [
			'reference_id' => $id,
			'currency' => 'IDR',
			'amount' => $nominal,
			'checkout_method' => 'ONE_TIME_PAYMENT',
			'channel_code' => $code,
			'ewallet_type' => 'EWALLET'
		];

		if ($code == 'ID_OVO') {
			$params['channel_properties']['mobile_number'] = $phoneNumber;
		} else {
			$params['channel_properties']['success_redirect_url'] = SUCCESS_REDIRECT;
		}

		return \Xendit\EWallets::createEWalletCharge($params);
	} catch (Exception $e) {
		// echo json_encode($e);
		return null;
	} catch (\Xendit\Exceptions\ApiException $e) {
		// var_dump($e->getMessage());
		return null;
	}
}

/**
 * Get virtual account status
 */
function paymentGetVa($id)
{
	Xendit::setApiKey(XDEV_SECRET);

	try {
		return \Xendit\VirtualAccounts::retrieve($id);
		// return \Xendit\VirtualAccounts::getFVAPayment($id);
	} catch (Exception $e) {
		return null;
	} catch (\Xendit\Exceptions\ApiException $e) {
		return null;
	}
}

/**
 * Get e-wallet status
 */
function paymentGetEwallet($id)
{
	Xendit::setApiKey(XDEV_SECRET);

	try {
		return \Xendit\EWallets::getEWalletChargeStatus($id);
	} catch (Exception $e) {
		return null;
	} catch (\Xendit\Exceptions\ApiException $e) {
		return null;
	}
}

function paymentCreateUUID()
{
	$uuid = Uuid::uuid4();
	return $uuid->toString();
}

/**
 * check is payment calculation
 */
function paymentCalulate($method, $amount, $baseAmount)
{
	$ppn = $method->ppn;
	$calcAmount = 0;

	// ppn is percentage
	if ($method->ppn_percentage == 1) {
		// get percentage
		$_ppn = ($baseAmount * $ppn) / 100;
		// add 11%
		$__ppn = round(($_ppn * 11) / 100);
		$ppn = $_ppn + $__ppn;
		$calcAmount = $baseAmount + $ppn;
	} else {
		// add 11%
		$_ppn = round(($ppn * 11) / 100);
		$ppn = $_ppn + $ppn;
		$calcAmount = $baseAmount + $ppn;
	}

	return (object) [
		"amount" => round($calcAmount),
		"base_amount" => $baseAmount * 1,
		"ppn" => round($ppn * 1),
		"is_equal" => $calcAmount == $amount,
	];
}

function prepareDetailsData($paymentMethod, $uuid, $rawData, $calculate, $result)
{
	if ($paymentMethod->type == 'va') {
		$resultData = [
			'uuid' => $uuid,
			'billing_session_id' => $rawData['billing_session_id'],
			'user_id' => $rawData['user_id'],
			'payment_method_id' => $rawData['payment_method_id'],
			'method_name' => $paymentMethod->name,
			'method_type' => $paymentMethod->type,
			'method_code' => $paymentMethod->code,
			'bank_fee' => $calculate->ppn,
			'bill_amount' => $calculate->base_amount,
			'grand_total' => $calculate->amount,
			'payment_id' => $result['id'],
			'status_va' => $result['status'],
			'merchant_code' => $result['merchant_code'],
			'va_number' => $result['account_number'],
			'expiry_date' => $result['expiration_date']
		];
	} else if ($paymentMethod->type == 'ewallet') {
		$resultData = [
			'uuid' => $uuid,
			'billing_session_id' => $rawData['billing_session_id'],
			'user_id' => $rawData['user_id'],
			'payment_method_id' => $rawData['payment_method_id'],
			'method_name' => $paymentMethod->name,
			'method_type' => $paymentMethod->type,
			'method_code' => $paymentMethod->code,
			'ppn_percentage' => 1,
			'ppn_total' => $calculate->ppn,
			'bill_amount' => $calculate->base_amount,
			'grand_total' => $calculate->amount,
			'payment_id' => $result['id'],
			'status_ewallet' => $result['status']
		];
	}

	return $resultData;
}
