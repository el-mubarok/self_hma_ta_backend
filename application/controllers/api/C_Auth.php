<?php

/**
 * @property M_Common $modelCommon
 */
class C_Auth extends CI_Controller
{
  public function __construct()
  {
    parent::__construct();

    $this->load->model('api/M_Common', 'modelCommon');
  }

  public function postLogin() {
    $requiredData = [
      'username', 'password', 'play_id'
		];
		$rawData = requestPostData($this, $requiredData);

    $username = $rawData['username'];
    $password = $rawData['password'];
    $playId = $rawData['play_id'];

    // check account type
    $account = $this->_checkAccount($username);

    if($account) {
      $type = $account->type;
      $userData = $account->data;

      if(isset($userData->deleted_at)){
        if($userData->deleted_at != null) {
          HelperUtilsReturnJSON($this, 400, [
            'message' => 'invalid username or password'
          ]);
          return;
        }
      }

      // check password is valid
      if(password_verify($password, $userData->password)) {
        // valid
        // remove unused data
        unset($userData->password);

        if($type == 'user') {
          unset($userData->deleted_at);
          unset($userData->account_bri_enabled);
          unset($userData->account_mandiri_enabled);
          unset($userData->account_bni_enabled);
          unset($userData->account_permata_enabled);
          unset($userData->account_bca_enabled);
          unset($userData->account_bsi_enabled);
          unset($userData->qr_code);
          unset($userData->play_id);
        }

        $userData->type = $type;

        // update play id
        $this->modelCommon->update(TBL_USER, [
          'id' => $userData->id,
        ], [
          'play_id' => $playId
        ]);

        HelperUtilsReturnJSON($this, 200, $userData);
        return true;
      }else{
        // invalid
        HelperUtilsReturnJSON($this, 400, [
          'message' => 'invalid username or password'
        ]);
      }
    }else{
      HelperUtilsReturnJSON($this, 400, [
        'message' => 'invalid username or password'
      ]);
    }
  }

  private function _checkAccount($username) {
    $user = $this->modelCommon->get(TBL_USER, [
      'email' => $username
    ]);

    if($user) {
      return (object)[
        'data' => $user,
        'type' => 'user'
      ];
    }

    $admin = $this->modelCommon->get(TBL_ADMIN, [
      'username' => $username
    ]);

    if($admin) {
      return (object)[
        'data' => $admin,
        'type' => 'admin'
      ];
    }

    return null;
  }
}