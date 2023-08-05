<?php

/**
 * @property M_Common $modelCommon
 * @property CI_Input $input
 */
class Cron_job extends CI_Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->load->model('api/M_Common', 'modelCommon');
  }

  public function index()
  {
    echo 'nothing here :p';
  }

  /**
   * - get current session
   * - get reminder time data
   * - get payment details
   * - get all user
   * - 
   * - 
   */
  /* #region userReminder, send notification to user */
  public function userReminder()
  {
    $playId = (object)[
      'play_id' => '1f675ce4-3ad9-4668-96c6-971db66cbc0d'
    ];
    $month = date('m');
    $year = date('Y');
    $day = date('d');
    $notifyUsers = [];
    $alreadyPay = [];

    $session = $this->modelCommon->get(
      TBL_BILLING_SESSION,
      "MONTH(from_date) = $month AND YEAR(from_date) = $year"
    );

    $sessionDayFrom = date('d', strtotime($session->from_date));
    $sessionDayTo = date('d', strtotime($session->to_date));

    if ($day < $sessionDayFrom) {
      echo 'today is not in schedule';
      return;
    }

    if ($day > $sessionDayTo) {
      echo 'today is not in schedule';
      return;
    }

    if ($session) {
      // echo $session->id."\n";

      // reminder time
      $reminderTime = $this->modelCommon->get(
        TBL_BILLING_SESSION_TIME,
        ['billing_session_id' => $session->id],
        false
      );

      if (count($reminderTime) > 0) {
        // payment details
        $pdw = "billing_session_id = $session->id AND (status_va = 'SUCCESS' OR status_ewallet = 'SUCCEEDED')";
        $paymentDetails = $this->modelCommon->get(
          TBL_PAYMENT_DETAILS,
          $pdw,
          false
        );

        if (count($paymentDetails) > 0) {
          // user that not yet pay for current session
          $user = $this->modelCommon->get(
            TBL_USER,
            null,
            false
          );

          if (count($user) > 0) {
            // loop through user and payment details
            for ($i = 0; $i < count($user); $i++) {
              $temp = array_search($user[$i]->id, array_column($paymentDetails, 'user_id'));

              if (is_bool($temp)) {
                if (!$temp) {
                  array_push($notifyUsers, $user[$i]->play_id);
                }
              }
            }
          }
        }

        // HelperUtilsReturnJSON($this, 200, $notifyUsers);

        if (count($notifyUsers) > 0) {
          $name = $session->name;
          $yEnd = date('Y', strtotime($session->to_date));
          $mEnd = date('m', strtotime($session->to_date));
          $dEnd = date('d', strtotime($session->to_date));
          $currentTime = (int)str_replace(':', '', date('H:i'));
          $endDate = date('d F Y', strtotime($session->to_date));

          if (count($reminderTime) > 0) {
            for ($i = 0; $i < count($reminderTime); $i++) {
              $a = (int)str_replace(':', '', date('H:i', strtotime('9:8')));
              $b = (int)str_replace(':', '', date('H:i', strtotime('9:12')));

              $time = (int)str_replace(
                ':',
                '',
                date('H:i', strtotime($reminderTime[$i]->time_stamp))
              );

              // echo abs($a - $b);
              $diff = abs($time - $currentTime);

              // add different time less than 5 minutes
              if ($diff < 5) {
                // do notify users
                $playId->play_id = $notifyUsers;
                echo "remind\n";
                $notif = notificationSend(
                  $playId,
                  'Pengingat pembayaran iuran',
                  "Silahkan melakukan pembayaran $name sebelum tanggal $endDate."
                );
                var_dump($notif);
                break;
              } else {
                echo "skip\n";
              }
            }
          }
        }
      }
    }

    // notificationSend(
    //   $playId, 
    //   'Pengingat pembayaran iuran', 
    //   'Silahkan melakukan pembayaran iuran Bulan Agustus 2023 sebelum tanggal 24 Agustus 2023.'
    // );
  }
  /* #endregion */

  /* #region sweepPaymentDetails, check and update expired payment details */
  public function sweepPaymentDetails()
  {
    echo "sweeping..";
    $currentDate = date('Y-m-d H:i:s');
    // get payment details
    $paymentDetails = $this->modelCommon->get(
      TBL_PAYMENT_DETAILS,
      "(status_va = 'ACTIVE' OR status_va = 'PENDING' OR status_ewallet = 'PENDING')",
      false
    );

    foreach($paymentDetails as $payment) {
      
      // virtual account
      if($payment->method_type == 'va') {
        $paymentExp = date('Y-m-d H:i:s', strtotime($payment->expiry_date));
        $user = $this->modelCommon->get(
          TBL_USER,
          ['id' => $payment->user_id]
        );
        $session = $this->modelCommon->get(
          TBL_BILLING_SESSION,
          ['id' => $payment->billing_session_id]
        );
        
        if($currentDate > $paymentExp) {
          echo "\n change this payment to inactive";
          
          // update to failed
          $this->modelCommon->update(
            TBL_PAYMENT_DETAILS,
            ['id' => $payment->id],
            ['status_va' => 'FAILED']
          );

          $playId = (object)[
            'play_id' => $user->play_id
          ];
          $paymentName = $session->name;
          $paymentDate = date('d/m/Y H:i:s', strtotime($payment->created_at));
          $notif = notificationSend(
            $playId,
            'Pembayaran dibatalkan',
            "Pembayaran $paymentName pada tanggal $paymentDate telah melebihi batas waktu."
          );
          echo json_encode($notif);
        }
      }

    }

    echo "\ndone.";

    // HelperUtilsReturnJSON($this, 200, $paymentDetails);
  }
  /* #endregion */
}
