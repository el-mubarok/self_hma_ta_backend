<?php

/**
 * @property M_Common $modelCommon
 * @property M_Billing $modelBilling
 * @property CI_Input $input
 */
class C_Billing extends CI_Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->load->model('api/M_Common', 'modelCommon');
    $this->load->model('api/M_Billing', 'modelBilling');
  }

  /* #region index */
  public function index($id = null)
  {
    $detail = false;

    if ($this->input->get('detail')) {
      $detail = true;
    }

    // billing
    if ($id == null) {
      $session = $this->modelCommon->get(TBL_BILLING_SESSION);
    } else {
      $session = $this->modelCommon->get(TBL_BILLING_SESSION, ['id' => $id]);
    }

    if (!$session) {
      HelperUtilsReturnJSON($this, 200, null);
      return;
    }

    $allUser = $this->modelCommon->get(TBL_USER);
    $totalUser = count($allUser);

    // add session time
    if (!$id) {
      // multiple session data
      for ($i = 0; $i < count($session); $i++) {
        // session time/reminder
        $sessionTime = $this->modelCommon->get(TBL_BILLING_SESSION_TIME, [
          'billing_session_id' => $session[$i]->id
        ], false);
        $session[$i]->reminder = $sessionTime;

        if ($detail) {
          // payment details
          $_w = "billing_session_id = " . $session[$i]->id . " AND (status_ewallet = 'SUCCEEDED' OR status_va = 'SUCCESS')";

          $paymentDetails = $this->modelCommon->get(TBL_PAYMENT_DETAILS, $_w, false);
          $session[$i]->payments = $paymentDetails;

          for ($ii = 0; $ii < count($paymentDetails); $ii++) {
            // add user to payment details
            $paymentUser = $this->modelCommon->get(TBL_USER, [
              'id' => $paymentDetails[$ii]->user_id,
            ], true);
            unset($paymentUser->password);
            $paymentDetails[$ii]->user = $paymentUser;

            // add payment method info
            $paymentMethod = $this->modelCommon->get(TBL_PAYMENT_METHOD, [
              'id' => $paymentDetails[$ii]->payment_method_id
            ], true);
            $paymentDetails[$ii]->payment_method = $paymentMethod;
          }

          // check completion percentage
          // payed user
          $completeUser = count($paymentDetails);

          if ($completeUser > 0) {
            $completionPercentage = ($totalUser / $completeUser) * 100;
            $session[$i]->payment_progress = $completionPercentage;
            $session[$i]->total_user = $totalUser;
            $session[$i]->complete_user = $completeUser;
          } else {
            $session[$i]->payment_progress = 0;
            $session[$i]->total_user = $totalUser;
            $session[$i]->complete_user = $completeUser;
          }
        }
      }
    } else {
      $sessionTime = $this->modelCommon->get(TBL_BILLING_SESSION_TIME, [
        'billing_session_id' => $id
      ], false);
      $session->reminder = $sessionTime;

      if ($detail) {
        // payment details
        $_w = "billing_session_id = " . $session->id . " AND (status_ewallet = 'SUCCEEDED' OR status_va = 'SUCCESS')";

        $paymentDetails = $this->modelCommon->get(TBL_PAYMENT_DETAILS, $_w, false);
        $session->payments = $paymentDetails;

        for ($ii = 0; $ii < count($paymentDetails); $ii++) {
          // add user to payment details
          $paymentUser = $this->modelCommon->get(TBL_USER, [
            'id' => $paymentDetails[$ii]->user_id
          ], true);
          unset($paymentUser->password);
          $paymentDetails[$ii]->user = $paymentUser;

          // add payment method info
          $paymentMethod = $this->modelCommon->get(TBL_PAYMENT_METHOD, [
            'id' => $paymentDetails[$ii]->payment_method_id
          ], true);
          $paymentDetails[$ii]->payment_method = $paymentMethod;
        }

        // check completion percentage
        // payed user
        $completeUser = count($paymentDetails);
        $allUser = $this->modelCommon->get(TBL_USER);
        $totalUser = count($allUser);

        if ($completeUser > 0) {
          $completionPercentage = ($totalUser / $completeUser) * 100;
          $session->payment_progress = $completionPercentage;
          $session->total_user = $totalUser;
          $session->complete_user = $completeUser;
        } else {
          $session->payment_progress = 0;
          $session->total_user = $totalUser;
          $session->complete_user = $completeUser;
        }
      }
    }

    HelperUtilsReturnJSON($this, 200, $session);
  }
  /* #endregion */

  public function getUserActiveSession($userId)
  {
    $currMonth = date('m');
    $currYear = date('Y');

    // active/current month billing
    $billingSession = $this->modelCommon->get(
      TBL_BILLING_SESSION,
      "MONTH(from_date) = $currMonth AND YEAR(from_date) = $currYear",
      true
    );

    if ($billingSession) {
      $sessionId = $billingSession->id;
      // check is current user already pay
      $paymentDetails = $this->modelCommon->get(
        TBL_PAYMENT_DETAILS,
        "billing_session_id = $sessionId AND user_id = $userId AND (status_va = 'SUCCESS' OR status_ewallet = 'SUCCEEDED')",
        true
      );

      if ($paymentDetails) {
        $billingSession->is_paid = true;
      } else {
        $billingSession->is_paid = false;
      }
    }

    HelperUtilsReturnJSON($this, 200, $billingSession);
  }

  public function getActiveSession()
  {
    // echo password_hash('direktur', PASSWORD_BCRYPT);
    $currMonth = date('m');
    $currYear = date('Y');

    // active/current month billing
    $session = $this->modelCommon->get(
      TBL_BILLING_SESSION,
      "MONTH(from_date) = $currMonth AND YEAR(from_date) = $currYear",
      true
    );

    if ($session) {
      $sessionId = $session->id;
      // check is current user already pay
      $_w = "billing_session_id = " . $session->id . " AND (status_ewallet = 'SUCCEEDED' OR status_va = 'SUCCESS')";

      $paymentDetails = $this->modelCommon->get(TBL_PAYMENT_DETAILS, $_w, false);
      // $session->payments = $paymentDetails;

      for ($ii = 0; $ii < count($paymentDetails); $ii++) {
        // add user to payment details
        $paymentUser = $this->modelCommon->get(TBL_USER, [
          'id' => $paymentDetails[$ii]->user_id
        ], true);
        unset($paymentUser->password);
        $paymentDetails[$ii]->user = $paymentUser;

        // add payment method info
        $paymentMethod = $this->modelCommon->get(TBL_PAYMENT_METHOD, [
          'id' => $paymentDetails[$ii]->payment_method_id
        ], true);
        $paymentDetails[$ii]->payment_method = $paymentMethod;
      }

      // check completion percentage
      // payed user
      $completeUser = count($paymentDetails);
      $allUser = $this->modelCommon->get(TBL_USER);
      $totalUser = count($allUser);

      if ($completeUser > 0) {
        $completionPercentage = ($completeUser / $totalUser) * 100;
        $session->payment_progress = round($completionPercentage);
        $session->total_user = $totalUser;
        $session->complete_user = $completeUser;
      } else {
        $session->payment_progress = 0;
        $session->total_user = $totalUser;
        $session->complete_user = $completeUser;
      }
    }

    HelperUtilsReturnJSON($this, 200, $session);
  }

  public function getUserActivePayment()
  {
    $userId = $this->input->get('user_id');
    $billingSessionId = $this->input->get('billing_session_id');
    /**
     * VA -> ACTIVE status
     * Ewallet -> PENDING status
     */
    $paymentDetails = $this->modelCommon->get(
      TBL_PAYMENT_DETAILS,
      "user_id = $userId AND (status_va = 'ACTIVE' OR status_va = 'PENDING' OR status_ewallet = 'PENDING')",
      true
    );

    if ($paymentDetails) {
      if ($paymentDetails->method_type == 'ewallet') {
        // get payment info
        $paymentInfo = paymentGetEwallet($paymentDetails->payment_id);
        $paymentDetails->payment_actions = $paymentInfo['actions'];

        if ($paymentInfo['status'] != $paymentDetails->status_ewallet) {
          // reupdate local
          $this->modelCommon->update(TBL_PAYMENT_DETAILS, [
            'id' => $paymentDetails->id
          ], [
            'status_ewallet' => $paymentInfo['status']
          ]);

          // recall
          $paymentDetails = $this->modelCommon->get(
            TBL_PAYMENT_DETAILS,
            "user_id = $userId AND (status_va = 'ACTIVE' OR status_va = 'PENDING' OR status_ewallet = 'PENDING')",
            true
          );
          $paymentDetails->payment_actions = $paymentInfo['actions'];
        }
      } else if ($paymentDetails->method_type == 'va') {
        // get payment info
        $paymentInfo = paymentGetVa($paymentDetails->payment_id);
        $paymentStatus = $paymentInfo['status'];

        // var_dump($paymentInfo);

        if (isset($paymentInfo['transaction_timestamp'])) {
          // transaction done/finished
          if ($paymentStatus != $paymentDetails->status_ewallet) {
            // update db
            $this->modelCommon->update(
              TBL_PAYMENT_DETAILS,
              ['id' => $paymentDetails->id],
              ['status_va' => 'SUCCESS']
            );
          }
        } else if (isset($paymentInfo['status'])) {
          // status exists
          if ($paymentStatus != $paymentDetails->status_ewallet) {
            // update db
            $this->modelCommon->update(
              TBL_PAYMENT_DETAILS,
              ['id' => $paymentDetails->id],
              ['status_va' => $paymentStatus]
            );
          }
        }

        $paymentDetails = $this->modelCommon->get(
          TBL_PAYMENT_DETAILS,
          "user_id = $userId AND (status_va = 'ACTIVE' OR status_va = 'PENDING' OR status_ewallet = 'PENDING')",
          true
        );
      }

      HelperUtilsReturnJSON($this, 200, $paymentDetails);
      return true;
    }

    HelperUtilsReturnJSON($this, 200, null);
  }

  /* #region postSession */
  public function postSession()
  {
    $requiredData = [
      'admin_id',
      'name',
      'description',
      'price',
      'from_date',
      'to_date'
    ];
    $rawData = requestPostData($this, $requiredData);

    $insertResult = $this->modelCommon->insert(TBL_BILLING_SESSION, $rawData);

    if ($insertResult) {
      HelperUtilsReturnJSON($this, 200, [
        'message' => 'session created'
      ]);
    } else {
      HelperUtilsReturnJSON($this, 400, [
        'message' => 'failed to create session'
      ]);
    }
  }
  /* #endregion */

  /* #region patchSession */
  public function patchSession()
  {
    $requiredData = [
      'id',
      'name',
      'description',
      'price',
      'from_date',
      'to_date'
    ];
    $rawData = requestPostData($this, $requiredData, true);
    $id = $rawData['id'];

    unset($rawData['id']);
    $updateResult = $this->modelCommon->update(TBL_BILLING_SESSION, [
      'id' => $id
    ], $rawData);

    if ($updateResult) {
      HelperUtilsReturnJSON($this, 200, [
        'message' => 'session updated'
      ]);
    } else {
      HelperUtilsReturnJSON($this, 400, [
        'message' => 'failed to update session'
      ]);
    }
  }
  /* #endregion */

  // session time
  /* #region getSessionTime */
  public function getSessionTime()
  {
    $sessionTime = $this->modelCommon->get(TBL_BILLING_SESSION_TIME);
    HelperUtilsReturnJSON($this, 200, $sessionTime);
  }
  /* #endregion */

  /* #region postSessionTime */
  public function postSessionTime()
  {
    $requiredData = [
      'billing_session_id',
      'admin_id',
      'time_stamp'
    ];
    $rawData = requestPostData($this, $requiredData);

    $insertResult = $this->modelCommon->insert(
      TBL_BILLING_SESSION_TIME,
      $rawData
    );

    if ($insertResult) {
      HelperUtilsReturnJSON($this, 200, [
        'message' => 'session time created'
      ]);
    } else {
      HelperUtilsReturnJSON($this, 400, [
        'message' => 'failed to create session time'
      ]);
    }
  }
  /* #endregion */

  /* #region patchSessionTime */
  public function patchSessionTime()
  {
    $requiredData = [
      'id',
      'time_stamp'
    ];
    $rawData = requestPostData($this, $requiredData, true);

    $data = [];
    $dataLen = count($rawData['id']);

    for ($i = 0; $i < $dataLen; $i++) {
      array_push($data, [
        'id' => $rawData['id'][$i],
        'time_stamp' => $rawData['time_stamp'][$i]
      ]);
    }

    foreach ($data as $d) {
      $updateResult = $this->modelCommon->update(TBL_BILLING_SESSION_TIME, [
        'id' => $d['id']
      ], [
        'time_stamp' => $d['time_stamp']
      ]);
    }


    if ($updateResult) {
      HelperUtilsReturnJSON($this, 200, [
        'message' => 'session time updated'
      ]);
    } else {
      HelperUtilsReturnJSON($this, 400, [
        'message' => 'failed to update session time'
      ]);
    }
  }
  /* #endregion */

  /* #region deleteSessionTime */
  public function deleteSessionTime($id)
  {
    $deleteResult = $this->modelCommon->fullDelete(
      TBL_BILLING_SESSION_TIME,
      $id
    );

    if ($deleteResult) {
      HelperUtilsReturnJSON($this, 200, [
        'message' => 'session time deleted'
      ]);
    } else {
      HelperUtilsReturnJSON($this, 400, [
        'message' => 'failed to delete session time'
      ]);
    }
  }
  /* #endregion */
}
