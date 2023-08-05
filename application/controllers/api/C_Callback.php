<?php
defined('BASEPATH') or exit('No direct script access allowed');

define('TITLE_PAYMENT_SUCCESS', 'Pembayaran anda berhasil');
define('TITLE_PAYMENT_WAITING', 'Menunggu pembayaran');

/**
 * @property M_Payment_Method $modelPaymentMethod
 * @property M_Common $modelCommon
 * @property CI_Input $input
 */
class C_Callback extends CI_Controller
{
  public function __construct()
  {
    parent::__construct();

    $this->load->model('api/M_Payment_Method', 'modelPaymentMethod');
    $this->load->model('api/M_Common', 'modelCommon');
  }

  public function index()
  {
    echo ":p";
  }

  /* #region postCallback, used by xendit */
  public function postCallback()
  {
    $data = json_decode(file_get_contents('php://input'), true);
    $type = null;
    var_dump(
      paymentGetVa('64b7451bac2392719cadcb60')
    );

    // e-wallet
    if (isset($data['data']['reference_id'])) {
      $type = 'ewallet';
      $referenceId = $data['data']['reference_id'];
      $paymentMethod = $this->modelCommon->get(TBL_PAYMENT_METHOD, [
        'code' => $data['data']['channel_code']
      ]);
      $result = [
        'id' => $data['data']['id'],
        'status' => $data['data']['status']
      ];
    }
    // va
    else if (isset($data['external_id'])) {
      $type = 'va';
      $referenceId = $data['external_id'];
      $paymentMethod = $this->modelCommon->get(TBL_PAYMENT_METHOD, [
        'code' => $data['bank_code']
      ]);

      if (isset($data['status'])) {
        $_status =  $data['status'];
      } else if (isset($data['transaction_timestamp'])) {
        $_status =  'SUCCESS';
      } else {
        $_status =  'ERROR';
      }

      $result = [
        'id' => $data['id'],
        'status' => $_status,
        'merchant_code' => $data['merchant_code'],
        'account_number' => $data['account_number'],
      ];

      if (isset($data['expiration_date'])) {
        // 'expiration_date' => $data['expiration_date']
        $result['expiration_date'] = $data['expiration_date'];
      }
    }

    // return true;

    // $referenceId = $data['data']['reference_id'];
    // $paymentMethod = $this->modelCommon->get(TBL_PAYMENT_METHOD, [
    //   'code' => $data['data']['channel_code']
    // ]);
    // $result = [
    //   'id' => $data['data']['id'],
    //   'status' => $data['data']['status']
    // ];

    $paymentDetails = $this->modelCommon->get(TBL_PAYMENT_DETAILS, [
      'uuid' => $referenceId
    ]);
    $sessionDetails = $this->modelCommon->get(TBL_BILLING_SESSION, [
      'id' => $paymentDetails->billing_session_id
    ]);
    // var_dump($paymentDetails);
    $metaData = [
      'user_id' => $paymentDetails->user_id,
      'billing_session_id' => $paymentDetails->billing_session_id,
      'payment_method_id' => $paymentDetails->payment_method_id
    ];
    $nominal = (object)[
      'ppn' => $paymentDetails->ppn_total,
      'base_amount' => $paymentDetails->bill_amount,
      'amount' => $paymentDetails->grand_total
    ];

    // update payment details
    if ($type != null) {
      $detailsData = prepareDetailsData(
        $paymentMethod,
        $referenceId,
        $metaData,
        $nominal,
        $result
      );

      // filter updated data
      if ($type == 'ewallet') {
        $detailsData = [
          "status_ewallet" => $detailsData['status_ewallet']
        ];
        $methodEwallet = $this->modelCommon->get(TBL_PAYMENT_STATUS_EWALLET, [
          'code' => $detailsData['status_ewallet']
        ]);
      } else if ($type == 'va') {
        echo json_encode($detailsData);
        $detailsData = [
          "status_va" => $detailsData['status_va'],
          "expiry_date" => $detailsData['expiry_date']
        ];
        $methodVa = $this->modelCommon->get(TBL_PAYMENT_STATUS_VA, [
          'code' => $detailsData['status_va']
        ]);
      }

      if ($type != null) {
        $this->modelCommon->update(TBL_PAYMENT_DETAILS, [
          'uuid' => $referenceId
        ], $detailsData);

        // send notification to specific user
        $user = $this->modelCommon->get(TBL_USER, [
          'id' => $paymentDetails->user_id
        ]);

        if ($type == 'va') {
          if (isset($data['transaction_timestamp'])) {
            notificationSend(
              $user,
              TITLE_PAYMENT_SUCCESS,
              messageBill($this, $type, 'SUCCESS', $sessionDetails->name)
            );
          } else if (isset($data['status'])) {
            $stat = $data['status'];
            $title = "";
            $msg = "";
            $xp = date('D d M Y h:i a', strtotime($paymentDetails->expiry_date));

            switch ($stat) {
              case 'PENDING':
                $title = "Pembayaran diproses";
                $msg = "Pembayaran $sessionDetails->name sedang diproses oleh bank terkait";
                break;
              case 'INACTIVE':
                $title = "Pembayaran dibatalkan";
                $msg = "Silahkan melakukan pembayaran ulang untuk $sessionDetails->name.";
                break;
              case 'ACTIVE':
                $title = "Menunggu pembayaran";
                $msg = "Silahkan melakukan pembayaran $sessionDetails->name sebelum $xp.";
                break;
              case 'SUCCESS':
                $title = "Pembayaran berhasil";
                $msg = "$sessionDetails->name telah dibayarkan, terima kasih.";
                break;
            }

            notificationSend(
              $user,
              $title,
              messageBill($this, $type, $data['status'], $msg)
            );
          }
        } else if ($type == 'ewallet') {
          $stat = $data['status'];
          $title = "";
          $msg = "";

          switch ($stat) {
            case 'PENDING':
              $title = "Menunggu pembayaran";
              $msg = "Menunggu pembayaran $sessionDetails->name.";
              break;
            case 'FAILED':
              $title = "Pembayaran gagal";
              $msg = "Silahkan melakukan pembayaran ulang untuk $sessionDetails->name.";
              break;
            case 'SUCCEEDED':
              $title = "Pembayaran berhasil";
              $msg = "$sessionDetails->name telah dibayarkan, terima kasih.";
              break;
            case 'VOIDED':
              $title = "Pembayaran dibatalkan";
              $msg = "Pembayaran untuk $sessionDetails->name telah dibatalkan";
              break;
            case 'REFUNDED':
              $title = "Dana telah dikembalikan";
              $msg = "Dana untuk pembayaran $sessionDetails->name telah dikembalikan.";
              break;
          }
          notificationSend(
            $user,
            $title,
            messageBill($this, $type, $data['status'], $msg)
          );
        }
      } else {
        // return error
        HelperUtilsReturnJSON($this, 400, [
          "message" => "need resend request"
        ]);

        return true;
      }
    } else {
      HelperUtilsReturnJSON($this, 400, [
        "message" => "need resend request"
      ]);

      return true;
    }

    HelperUtilsReturnJSON($this, 200, [
      'message' => 'success'
    ]);
  }
  /* #endregion */
}
