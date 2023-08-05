<?php

function responseRequestPayment($type, $data) {
  if($type == 'va') {
    $xp = $data['expiration_date'];
    // (new DateTime(date('Y-m-d H:i:s')))->modify('+1 day')->format('Y-m-d H:i:s')
    $temp = [
      'type' => $type,
      'payment_id' => $data['id'],
      'status' => $data['status'],
      'expiry_date' => $xp != null || isset($data['expiration_date']) ? $xp : (new DateTime(date('Y-m-d H:i:s')))->modify('+1 day')->format('Y-m-d H:i:s'),
      'merchant_code' => $data['merchant_code'],
      'va_number' => $data['account_number']
    ];

    return $temp;
  } else if($type == 'ewallet') {
    $temp = [
      'type' => $type,
      'payment_id' => $data['id'],
      'channel_code' => $data['channel_code'],
      'status' => $data['status'],
      'phone_number' => null,
      'actions' => [
        'checkout_url' => null
      ]
    ];

    if($data['channel_code'] == 'ID_OVO') {
      $temp['phone_number'] = $data['channel_properties']['mobile_number'];
    } else {
      if($data['is_redirect_required'] == true) {
        // dana and linkaja
        if($data['channel_code'] == 'ID_DANA' || $data['channel_code'] == 'ID_LINKAJA') {
          $temp['actions']['checkout_url'] = $data['actions']['mobile_web_checkout_url'];
        }
        // shopee pay
        else if($data['channel_code'] == 'ID_SHOPEEPAY') {
          $temp['actions']['checkout_url'] = $data['actions']['mobile_deeplink_checkout_url'];
        }
      }
    }

    return $temp;
  }
}