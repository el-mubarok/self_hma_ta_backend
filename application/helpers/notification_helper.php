<?php

define('ONESIGNAL_RESTAPI', 'MzIwZDVhYzktYWUxMi00ZTZjLTkyMWEtYjkxMzk1YThlN2Yx');
define('ONESIGNAL_APP_ID', '25cb7e7c-7b91-45d8-bdc0-b283744bf441');
define('ONESIGNAL_API_PATH', 'https://onesignal.com/api/v1/notifications');

function notificationSend($user, $title, $message)
{

  $player_id = $user->play_id;

  if ($player_id == null) return false;

  $fields = array(
    'app_id' => ONESIGNAL_APP_ID,
    # target user device id
    # need add play_id field to user database
    'include_player_ids' => is_array($player_id) ? $player_id : array($player_id),
    // 'data' => array("foo" => "bar"),
    // "delayed_option" => "last-active",
    'contents' => [
      "en" => $message
    ],
    'headings' => [
      "en" => $title
    ]
  );

  // if ($avatar) {
  //   $fields['large_icon'] = $avatar;
  // }

  // if ($picture) {
  //   $fields['big_picture'] = $picture;
  // }

  $fields = json_encode($fields);
  // print("\nJSON sent:\n");
  // print($fields);

  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
  curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    "Content-Type: application/json; charset=utf-8",
    // "Authorization: Basic $restapi"
  ));
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
  curl_setopt($ch, CURLOPT_HEADER, FALSE);
  curl_setopt($ch, CURLOPT_POST, TRUE);
  curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

  $response = curl_exec($ch);
  curl_close($ch);
  // print_r($response);
  return $response;
}
