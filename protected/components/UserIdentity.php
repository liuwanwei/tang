<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{
	// 当前登录用户在user表中的id。
	public $userId;

	/**
	 * Authenticates a user.
	 * The example implementation makes sure if the username and password
	 * are both 'demo'.
	 * In practical applications, this should be changed to authenticate
	 * against some persistent user identity storage (e.g. database).
	 * @return boolean whether authentication succeeds.
	 */
	public function authenticate()
	{
		$user = User::model()->getUser($this->username);
		if ($user !== null) {
			$this->userId = $user->id;
			$this->errorCode=self::ERROR_NONE;
		}else {
			$this->errorCode=self::ERROR_USERNAME_INVALID;
		}
		
		return $this->errorCode == self::ERROR_NONE;;
	}
}