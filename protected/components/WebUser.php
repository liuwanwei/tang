<?php

// How to extending CWebUser: http://www.yiiframework.com/wiki/60/

class WebUser extends CWebUser{

	public function init(){		
		parent::init();

		// 尝试从HTTP Header中获取认证信息，以实现RestFul API。
		$this->initFromHeader();
	}

	/*
	 * 从HTTP Header中加载认证信息，以支持Restful风格的HTTP请求。
	 */
	private function initFromHeader(){
		// getallheaders() only available in PHP version equal or higher than 5.4, 
		// and only effective for apache server, so we need to check it exists first.
		if (!function_exists('getallheaders')) { 
		    function getallheaders(){ 
		       $headers = '';
		       foreach ($_SERVER as $name => $value) { 
		           if (substr($name, 0, 5) == 'HTTP_') { 
		               $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value; 
		           } 
		       } 
		       return $headers;
		    } 

		    $headers = getallheaders();
		}else{
			$headers = getallheaders();
		}
	
		if (! empty($headers['USER_AUTH_DATA'])) {

			list($username, $password) = explode(',', $headers['USER_AUTH_DATA']);
			// 以下内容源自LoginForm::login()
			$identity = new UserIdentity($username,$password);
			if($identity->authenticate()){
				$duration = 0;
				$this->login($identity, $duration);
				
				return true;
			}else {
				$this->logout();
				return false;
			}
		}else{
			return false;
		}
	}

	public function setImageUrl($value) {
		$this->setState('__imageUrl',$value);
	}

	public function getImageUrl() {
		if(($value = $this->getState('__imageUrl'))!==null)
			return $value;
		else
			return "";
	}

	public function setIsAdmin($value) {
		$this->setState('__IsAdmin',$value);
	}

	public function getIsAdmin() {
		if(($value = $this->getState('__IsAdmin'))!==null)
			return $value;
		else
			return false;
	}

	/*
	*	重构父类函数,登录成功后需要将WebUser的image_url、name、isAdmin初始化
	*/
	protected function afterLogin($fromCookie) {
			$user = User::model()->findByPk($this->id);
			if ($user !== null) {
				$this->name = $user->nick_name;
				$this->imageUrl = $user->image_url;
				$this->isAdmin = $user->isAdmin();
			}
	}

}