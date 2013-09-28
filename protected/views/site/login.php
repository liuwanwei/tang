<?php
/* @var $this SiteController */
/* @var $model LoginForm */
/* @var $form CActiveForm  */

$this->pageTitle=Yii::app()->name . ' - Login';
$this->breadcrumbs=array(
	'Login',
);
?>

<h1>Please Login</h1>
<?php if(Yii::app()->user->isGuest) {
				$this->widget('ext.oauthLogin.OauthLogin',array(
           			'itemView'=>'medium_login', //效果样式
					'back_url'=>Yii::app()->request->url,
 	));
}?>
		