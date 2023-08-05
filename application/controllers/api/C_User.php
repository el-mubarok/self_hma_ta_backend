<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property M_Common $modelCommon
 * @property CI_Input $input
 */
class C_User extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('api/M_Common', 'modelCommon');
	}

	// get all user
	public function index()
	{
		$data = $this->modelCommon->get(TBL_USER);

		for ($i = 0; $i < count($data); $i++) {
			unset($data[$i]->password);
			unset($data[$i]->play_id);
		}

		HelperUtilsReturnJSON($this, 200, $data);
	}

	public function getTotal() {
		$user = $this->modelCommon->get(TBL_USER, null, false);
		$lastUpdate = $this->modelCommon->get(TBL_USER, null, true, "updated_at DESC");

		if($lastUpdate) {
			$lastUpdate = $lastUpdate[0]->updated_at;
		}
		
		HelperUtilsReturnJSON($this, 200, [
			"total" => count($user),
			"last_update" => $lastUpdate
		]);
	}

	public function getUser($userId)
	{
		$data = $this->modelCommon->get(TBL_USER, ['id' => $userId]);
		$data->type = 'user';

		unset($data->password);
		unset($data->play_id);
		unset($data->account_bri_enabled);
		unset($data->account_mandiri_enabled);
		unset($data->account_bni_enabled);
		unset($data->account_permata_enabled);
		unset($data->account_bca_enabled);
		unset($data->account_bsi_enabled);
		unset($data->qr_code);

		HelperUtilsReturnJSON($this, 200, $data);
	}

	public function getUserAdmin($userId)
	{
		$data = $this->modelCommon->get(TBL_ADMIN, ['id' => $userId]);
		$data->type = 'admin';

		unset($data->password);
		unset($data->play_id);

		HelperUtilsReturnJSON($this, 200, $data);
	}

	/* #region postAddUser */
	public function postAddUser()
	{
		$requiredData = [
			'email',
			'password',
			'full_name',
			'phone_number',
			'avatar',
			'house_number',
			'house_block',
		];
		$rawData = requestPostData($this, $requiredData);
		$rawData['password'] = password_hash($rawData['password'], PASSWORD_BCRYPT);

		// insert common data
		$insertId = $this->modelCommon->insert('user', $rawData);

		// create user bank account
		$bankAccounts = $this->_createBankAccount($insertId);

		// update
		$this->modelCommon->update(TBL_USER, ["id" => $insertId], $bankAccounts);

		// show user data
		$user = $this->modelCommon->get(TBL_USER, ["id" => $insertId]);

		HelperUtilsReturnJSON($this, 200, $user);
	}
	/* #endregion */

	/* #region _createBankAccount */
	private function _createBankAccount($lastId)
	{
		$account = [
			'account_bri' => APP_BASE_REK_BRI + $lastId,
			'account_mandiri' => APP_BASE_REK_MANDIRI + $lastId,
			'account_bni' => APP_BASE_REK_BNI + $lastId,
			'account_permata' => APP_BASE_REK_PERMATA + $lastId,
			'account_bca' => APP_BASE_REK_BCA + $lastId,
			'account_bsi' => APP_BASE_REK_BSI + $lastId,
		];

		return $account;
	}
	/* #endregion */

	/**
	 * params: form url encode
	 */
	/* #region patchUser */
	public function patchUser()
	{
		$requiredData = [
			'user_id',
			'email',
			'password',
			'full_name',
			'phone_number',
			'house_number',
			'house_block',
		];
		$rawData = requestPostData($this, $requiredData, true);
		$rawData['id'] = $rawData['user_id'];

		// check password is exists
		if (isset($rawData['password'])) {
			if ($rawData['password'] == '') {
				unset($rawData['password']);
			} else {
				$rawData['password'] = password_hash($rawData['password'], PASSWORD_BCRYPT);
			}
		}

		// filter difference
		// get current user
		$currentUser = $this->modelCommon->get(TBL_USER, ['id' => $rawData['user_id']]);

		// stop if user not found
		if (!$currentUser) return HelperUtilsReturnJSON($this, 400, ['message' => 'access violation']);

		$userDataDiff = [];
		$currentUser = (array)$currentUser;

		// var_dump($userDataDiff);

		// return true;

		// filter edited data
		foreach ($rawData as $k => $v) {
			if (isset($currentUser[$k])) {
				if ($rawData[$k] != $currentUser[$k]) {
					$userDataDiff[$k] = $v;
				}
			}
		}

		// no data update
		if (count($userDataDiff) == 0) {
			HelperUtilsReturnJSON($this, 200, [
				'message' => 'no data changes'
			]);

			return;
		}

		// update data
		$updateResult = $this->modelCommon->update(
			TBL_USER,
			['id' => $rawData['user_id']],
			$userDataDiff
		);

		if ($updateResult) {
			HelperUtilsReturnJSON($this, 200, [
				'message' => 'user data updated',
				'update_data' => $userDataDiff
			]);
		} else {
			HelperUtilsReturnJSON($this, 400, [
				'message' => 'user update failed'
			]);
		}
	}
	/* #endregion */

	/* #region deleteUser */
	public function deleteUser($id)
	{
		$user = $this->modelCommon->get(TBL_USER, ['id' => $id]);

		if(!$user) {
			HelperUtilsReturnJSON($this, 400, ['message' => 'access violation']);
			return;
		}

		$deleteResult = $this->modelCommon->update(TBL_USER, ['id' => $id], [
			'deleted_at' => date('Y-m-d H:i:s'),
			'play_id' => null
		]);

		if($deleteResult) {
			HelperUtilsReturnJSON($this, 200, ['message' => 'user successfully deleted']);
		}else{
			HelperUtilsReturnJSON($this, 400, [
				'message' => 'failed to delete user data',
				'user_data' => [
					'full_name' => $user['full_name'],
					'email' => $user['email'],
					'house_number' => $user['house_number'],
					'house_block' => $user['house_block']
				]
			]);
		}

	}
	/* #endregion */

	/* #region retrieveUser */
	public function retrieveUser($id)
	{
		$user = $this->modelCommon->get(TBL_USER, ['id' => $id]);

		if(!$user) {
			HelperUtilsReturnJSON($this, 400, ['message' => 'access violation']);
			return;
		}

		$retrieveResult = $this->modelCommon->update(TBL_USER, ['id' => $id], [
			'deleted_at' => null
		]);

		if($retrieveResult) {
			HelperUtilsReturnJSON($this, 200, [
				'message' => 'user account successfully restored'
			]);
		}else{
			HelperUtilsReturnJSON($this, 400, [
				'message' => 'failed to retrieve user account'
			]);
		}

	}
	/* #endregion */

}
