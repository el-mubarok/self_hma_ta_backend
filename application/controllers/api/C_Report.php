<?php

/**
 * @property M_Common $modelCommon
 * @property CI_Input $input
 */
class C_Report extends CI_Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->load->model('api/M_Common', 'modelCommon');
  }

  /**
   * report structure:
   * 
   * - session
   * --- payment
   * ------ user
   * 
   * - user
   * --- session
   * ------ payment
   */

  // get all payment data based on payment details
  // with add user data
  /* #region getAll */
  public function getAll()
  {
    $paymentDetails = $this->modelCommon->get(
      TBL_PAYMENT_DETAILS,
      "status_ewallet = 'SUCCEEDED' OR status_va = 'SUCCESS'",
      false,
      "id DESC"
    );

    for ($i = 0; $i < count($paymentDetails); $i++) {
      $user = $this->modelCommon->get(
        TBL_USER,
        ['id' => $paymentDetails[$i]->user_id]
      );

      $paymentDetails[$i]->user = [
        "email" => $user->email,
        "full_name" => $user->full_name,
        "phone_number" => $user->phone_number,
        "house_number" => $user->house_number,
        "house_block" => $user->house_block,
        "created_at" => $user->created_at,
        "updated_at" => $user->updated_at
      ];
    }

    HelperUtilsReturnJSON($this, 200, $paymentDetails);
  }
  /* #endregion */

  public function getPerSession($sessionId)
  {
    // get all user
    $user = $this->modelCommon->get(
      TBL_USER
    );

    // get session
    $session = $this->modelCommon->get(
      TBL_BILLING_SESSION,
      ['id' => $sessionId]
    );

    // get all success payment
    $paymentDetails = $this->modelCommon->get(
      TBL_PAYMENT_DETAILS,
      "billing_session_id = $sessionId AND (status_ewallet = 'SUCCEEDED' OR status_va = 'SUCCESS')",
      false
    );

    // assign all user to each session
    for($i=0; $i<count($user); $i++) {
      for($ii=0; $ii<count($paymentDetails); $ii++) {
        if($user[$i]->id == $paymentDetails[$ii]->user_id) {
          $user[$i]->payment = $paymentDetails[$ii];
        }
      }
    }

    $session->user = $user;

    HelperUtilsReturnJSON($this, 200, $session);
  }
}
