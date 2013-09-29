<?php /* @var $this Controller */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />

	<!-- blueprint CSS framework -->
	<!--<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/screen.css" media="screen, projection" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/print.css" media="print" />-->
	<!--[if lt IE 8]>
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie.css" media="screen, projection" />
	<![endif]-->

	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/tang-main.css" />
	<!--<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/form.css" />-->

	<title><?php echo CHtml::encode($this->pageTitle); ?></title>

	<!-- Bootstrap
	<meta name="viewport" content="width=device-width, initial-scale=1.0">	
	<link href="<?php echo Yii::app()->request->baseUrl; ?>/css/bootstrap/bootstrap.min.css" rel="stylesheet" media="screen">
	 -->
</head>

<body>
<div class="container" id="page">

	
<?php
		 $menu = array();
		 $menu[] = array('label'=>'首页', 'url'=>array('/restaurant/index'));
		 $counties = County::model()->getCountries(0);
		 $areas=county::model()->getCountries(1);
		 //echo count($areas);
		 foreach ($counties as $key => $value)
		 {
		 	$menu[] = array('label' => $value, 'url' => array('/restaurant/index&county='.$key));
		 }
		 $menu[] = array('label'=>'县区','url'=>'');
// 		 $menu[] = array('label'=>'状态', 'url'=>array('/restaurantstatus/index'));
// 		 $menu[] = array('label'=>'评分测试', 'url'=>array('/vote/create'));
// 		 $menu[] = array('label'=>'gii',    'url'=>array('/gii/'));
		 $menu[] = array('label'=>'登出 ('.Yii::app()->user->name.')', 'url'=>array('/site/logout'), 'visible'=>!Yii::app()->user->isGuest);
		 $menu[] = array('label'=>'登陆','url'=>'','visible'=>yii::app()->user->isGuest, 
		 	'linkOptions'=>array('class'=>'login'));
	?>

	<div id="mainmenu">

	<?php $this->widget('zii.widgets.CMenu',array(
			'items'=>$menu
		)); 
		?>
		
	</div><!-- mainmenu -->

<!--登陆的模态窗口-->
<div id="myModal" class="modal hide fade in" aria-hidden="true">
<div class="modal-header">
<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
<h3 id="myModalLabel">选择登陆</h3>
</div>
<div class="modal-body">
<?php if(Yii::app()->user->isGuest) {
				$this->widget('ext.oauthLogin.OauthLogin',array(
           			'itemView'=>'medium_login', //效果样式
					'back_url'=>Yii::app()->request->url,
 				));
		}?>

		<a class="qq-login"  href="#" title="QQ登陆"><span>QQ登陆</span></a>
</div>
</div>
<div class="modal-backdrop hide"></div>
	<!--主体内容部分-->
	<div class="tang-content" id="tang-content">
	<?php echo $content; ?>
	</div>
	<div class="clear"></div>

	
	<!--<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/bootstrap/bootstrap.min.js"></script>-->
</div><!-- page -->
<div id="footer">
		Copyright &copy; <?php echo date('Y'); ?> 曦光科技.<br/>
		All Rights Reserved.<br/>
		<?php echo Yii::powered(); ?>
	</div><!-- footer -->
<script type="text/javascript">
$(function(){
							var footerHeight = 0,
									footerTop = 0,
									$footer = $("#footer");
							positionFooter();
							//定义positionFooter function
							function positionFooter() {
								//取到div#footer高度
								footerHeight = $footer.height();
								//div#footer离屏幕顶部的距离
								footerTop = ($(window).scrollTop()+$(window).height()-footerHeight)+"px";								
								//如果页面内容高度小于屏幕高度，div#footer将绝对定位到屏幕底部，否则div#footer保留它的正常静态定位
								if ( ($(document.body).height()+footerHeight) < $(window).height()) {
									$footer.css({
										position: "absolute"
										,top: footerTop});
									/*.stop().animate({
										top: footerTop
									});*/
								} else {
									$footer.css({
										position: "static"
									});

								}
							}
							$(window).scroll(positionFooter).resize(positionFooter);
						
				


//点击登陆弹出模态窗口
$(".login").click(function(){
	$(".modal-backdrop").show();
	$("#myModal").slideDown(200);
	$(".close").one("click",function(){
		$(".modal-backdrop").hide();
		$("#myModal").slideUp(100);

	});
});


});
</script>

</body>
</html>
