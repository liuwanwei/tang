<?php

class SiteController extends Controller
{	
	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'=>array(
				'class'=>'CViewAction',
			),
		);
	}

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
		);
	}
	
	public function accessRules()
	{
		
		return array(
				array('allow',
						'actions'=>array('login', 'wbLogin', 'logout', 'index', 'error', 'contact', 'userCenter','redirectLogin','redirectError'),
						'users'=>array('*')
				),
				array('allow', // allow admin user to perform 'admin' and 'delete' actions
						'actions'=>array('upVersion','admin', 'flushCache'),
						'expression'=>array($this,'isAdmin'),
				),
				array('deny',  // deny all usersk
						'users'=>array('*'),
				),
		);
	}

	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
		// renders the view file 'protected/views/site/index.php'
		// using the default layout 'protected/views/layouts/main.php'
		$this->render('index');
	}

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
		if($error=Yii::app()->errorHandler->error)
		{
			if(Yii::app()->request->isAjaxRequest)
				echo $error['message'];
			else 
				$this->render('error', $error);
		}
	}

	public function actionAdmin()
	{
		if (parent::isAdmin())
		{			
			$this->redirect($this->createUrl('/restaurant/admin'));
		}
		else 
		{
			$this->actionLogin();
		}
	}

	/**
	 * Displays the contact page
	 */
	public function actionContact()
	{
		$model=new ContactForm;
		if(isset($_POST['ContactForm']))
		{
			$model->attributes=$_POST['ContactForm'];
			if($model->validate())
			{
				$name='=?UTF-8?B?'.base64_encode($model->name).'?=';
				$subject='=?UTF-8?B?'.base64_encode($model->subject).'?=';
				$headers="From: $name <{$model->email}>\r\n".
					"Reply-To: {$model->email}\r\n".
					"MIME-Version: 1.0\r\n".
					"Content-type: text/plain; charset=UTF-8";

				mail(Yii::app()->params['adminEmail'],$subject,$model->body,$headers);
				Yii::app()->user->setFlash('contact','Thank you for contacting us. We will respond to you as soon as possible.');
				$this->refresh();
			}
		}
		$this->render('contact',array('model'=>$model));
	}
	

	/**
	 * Displays the login page
	 */
	public function actionLogin()
	{
		$model=new LoginForm;

		// if it is ajax validation request
		if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}

		// collect user input data
		if(isset($_POST['LoginForm']))
		{
			$model->attributes=$_POST['LoginForm'];
			// validate user input and redirect to the previous page if valid
			if($model->validate() && $model->login())
				$this->redirect(Yii::app()->user->returnUrl);
		}
		// display the login form
		$this->render('login',array('model'=>$model));
	}

	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}
	
	public function actionWbLogin()
	{
		if(isset($_REQUEST['state'])){
			if(isset($_REQUEST['code'])){
				Yii::import('ext.oauthLogin.sina.sinaWeibo',true);
				$keys = array();
				$keys['code'] = $_REQUEST['code'];
				$keys['redirect_uri'] = WB_CALLBACK_URL;
				try {
					$weibo = new SaeTOAuthV2(WB_AKEY,WB_SKEY);
					$sinaToken = $weibo->getAccessToken('code',$keys);
				} catch (CHttpException $e) {
	
				}
				//获取认证
				if (isset($sinaToken)) {
					Yii::app()->session->add('sinaToken',$sinaToken);
					//查询微博的账号信息
					$c = new SaeTClientV2( WB_AKEY , WB_SKEY ,Yii::app()->session['sinaToken']['access_token']);
					$userShow  = $c->getUserShow(Yii::app()->session['sinaToken']); // done
					//查询是否有绑定账号
					$user = User::model()->find('extension_user_id = :user_id',array(':user_id' =>Yii::app()->session['sinaToken']['uid']));
					//如果没有存在则创建账号及绑定
					if (!isset($user)){
						$user = new User;
	
						$user->extension_user_id = Yii::app()->session['sinaToken']['uid'];
						$user->nick_name = $userShow['screen_name'];
						$user->image_url = $userShow['profile_image_url'];
						$user->role	= 0;
						$user->source = 1;
	
						$user->save();
					}

					// 由于我们通过微博授权，所以没有用户名/密码登陆过程，但还是要模拟一下，以便Yii自动将登录状态保存到Cookie中。
					$identity = new UserIdentity($user->id, 'we_dont_need_password');
					$duration = Yii::app()->params['loginExpireTime'];
					if (empty($duration)) {
						$duration = 3600 * 24 * 7; // 默认一天内免登陆。
					}
					Yii::app()->user->login($identity, $duration);
	
					Yii::app()->user->id = $user->id;
					Yii::app()->user->name = $user->nick_name;
					Yii::app()->user->imageUrl = $user->image_url;
					Yii::app()->user->isAdmin = $user->isAdmin();
					
					$this->redirect($_REQUEST['state']);
				}  else {
					echo '认证失败';
				}
			}
		}
		else 
		{
			$this->redirect($_REQUEST['state']);
		}
	}

	public function actionUserCenter(){
		$this->layout='//layouts/column_admin';
		
		$model=new Restaurant('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Restaurant']))
			$model->attributes=$_GET['Restaurant'];

		$uncheckedDataProvider = $model->searchCreatedByMe(0);
		$checkedDataProvider = $model->searchCreatedByMe(1);

		$this->render('userCenter', array(
			'model'=> $model,
			'uncheckedDataProvider'=> $uncheckedDataProvider,
			'uncheckedItemsCount'=> $uncheckedDataProvider->getTotalItemCount(),
			'checkedDataProvider'=> $checkedDataProvider,
			'checkedItemsCount'=> $checkedDataProvider->getTotalItemCount(),
		));
	}

	public function actionUpVersion(){	
		$shell_script = Yii::getPathOfAlias('webroot') . '/etc/getcode.sh';
		$output = shell_exec("$shell_script 2>&1");
		echo "<pre>".date('H:i:s')."</pre>";
		echo "<pre>".$output."</pre>";		

		// 代码更新成功后，清除缓存。
		$error_prefix = "error:";
		if (strpos($output, $error_prefix) === false) {
			$this->actionFlushCache();
		}
	}

	public function actionFlushCache(){
		$success = Yii::app()->cache->flush();
		if ($success) {
			echo "<pre>缓存刷新成功</pre>";
		}

		echo '<pre><h1><a href="http://www.laotangguan.com">老汤馆</a></h1></pre>';
	}

	public function actionRedirectLogin() {
		$this->redirectPrompt(ERROR_CODE_LOGIN_REQUIRE, ERROR_CODE_MESSAGE_LOGIN_REQUIRE);
	}

	public function actionRedirectError() {
		$error = Yii::app()->errorHandler->error;	
		$code = $error['code'];
		$message = $error['message'];
		$url = Yii::app()->request->urlReferrer;
		
		$this->redirectPrompt($code, $message, $url);
	}
}