<?php /* @var $this Controller */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />

	<!-- blueprint CSS framework -->
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/screen.css" media="screen, projection" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/print.css" media="print" />
	<!--[if lt IE 8]>
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie.css" media="screen, projection" />
	<![endif]-->

	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/main.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/form.css" />

	<title><?php echo CHtml::encode($this->pageTitle); ?></title>

	<!-- Bootstrap
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href="<?php echo Yii::app()->request->baseUrl; ?>/css/bootstrap/bootstrap.min.css" rel="stylesheet" media="screen">
	 -->
</head>

<body>

<div class="container" id="page">

	<div id="header" style="height: 41px">
		<div class="logo"><?php echo CHtml::encode(Yii::app()->name); ?></div>
	</div><!-- header -->
	
	<?php
		 $menu = array();
		 $menu[] = array('label'=>'首页', 'url'=>array('/restaurant/index'));
		 $menu[] = array('label'=>'餐馆管理','url'=>array('/restaurant/admin'));
		 $menu[] = array('label'=> '餐馆状态管理','url'=>array('/restaurantStatus/admin'));
		 $menu[] = array('label'=>'县区管理','url'=>array('/county/admin'));
		 $menu[] = array('label'=>'区域管理','url'=>array('area/admin'));
		 $menu[] = array('label'=>'评论管理','url'=>array('comment/admin'));
		 $menu[] = array('label'=>'用户管理','url'=>array('user/admin'));
		 $menu[] = array('label'=>'gii',    'url'=>array('/gii/'));
		 $menu[] = array('label'=>'登出 ('.Yii::app()->user->name.')', 'url'=>array('/site/logout'), 'visible'=>!Yii::app()->user->isGuest);
	?>

	<div id="mainmenu">
		<?php $this->widget('zii.widgets.CMenu',array(
			'items'=>$menu
		)); 
	
		?>
	</div><!-- mainmenu -->
	<?php if(isset($this->breadcrumbs)):?>
		<?php $this->widget('zii.widgets.CBreadcrumbs', array(
			'links'=>$this->breadcrumbs,
		)); ?><!-- breadcrumbs -->
	<?php endif?>

	<?php echo $content; ?>

	<div class="clear"></div>

	<div id="footer">
		Copyright &copy; <?php echo date('Y'); ?> 曦光科技.<br/>
		All Rights Reserved.<br/>
		<?php echo Yii::powered(); ?>
	</div><!-- footer -->
	<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/bootstrap/bootstrap.min.js"></script>
</div><!-- page -->

</body>
</html>
