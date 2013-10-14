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
	
	public function actionAdmin()
	{
		if (parent::isAdmin())
		{
			parent::actionAdmin();
			$this->render('index');
		}
		else 
		{
			$this->actionLogin();
		}
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
	
					Yii::app()->user->id = $user->id;
					Yii::app()->user->name = $user->nick_name;
					
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
	
}