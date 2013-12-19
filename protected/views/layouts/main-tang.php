<?php /* @var $this Controller */ ?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
	<title><?php echo CHtml::encode("老汤馆-分享洛阳老滋味"); ?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />
	<meta property="wb:webmaster" content="e90b5cef4e51c718" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">	
	<link href="/favicon.ico" rel="icon" type="image/x-icon" />
	<!-- <link rel="shortcut icon" href="favicon.ico" type="image/x-icon" /> -->
 	<!-- Bootstrap -->
	<link rel="stylesheet" href="http://cdn.bootcss.com/twitter-bootstrap/3.0.2/css/bootstrap.min.css">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="http://cdn.bootcss.com/html5shiv/3.7.0/html5shiv.min.js"></script>
        <script src="http://cdn.bootcss.com/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->

	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/tang-main.css" />
	<!--<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/form.css" />-->
	
	<!-- <link rel="stylesheet" href="//netdna.bootstrapcdn.com/font-awesome/4.0.2/css/font-awesome.min.css"> -->
	<link rel="stylesheet" href="http://cdn.staticfile.org/font-awesome/4.0.3/css/font-awesome.min.css"> 
	<script type="text/javascript" src="//lib.sinaapp.com/js/jquery/1.8.3/jquery.min.js"></script>
</head>

<body>

<div class="t-container" id="page">
<?php
$menu = array();
$areamenu=array();
$counties = County::model()->getCountries(0);
$areas=county::model()->getCountries(1);
 //echo count($areas);
foreach ($counties as $key => $value)
{
	$menu[] = array('label' => $value, 'url' => $this->createUrl('/restaurant/index',array('county'=>$key)));
}
foreach ($areas as $key => $value) {
	$areamenu[]=array('label'=>$value,'url'=>$this->createUrl('/restaurant/index',array('county'=>$key)));
}
$menu[] = array('label'=>'县区','url'=>'','itemOptions'=>array('class'=>'areamenu'),'items'=>$areamenu);
?>

<div id="mainmenu">
<div class="mainmenu-content">
	<a href="<?php echo $this->createUrl('restaurant/index'); ?>" class="mainmenu-home"><img src="/images/icon/laotangguan.png" /></a>
	<?php $this->widget('zii.widgets.CMenu',array(	
		'items'=>$menu
		)); 
	?>

	<div class="rigth-menu">
		<?php if (yii::app()->user->isGuest) {?>
		<a href="#" class="login">登陆</a>
		<?php } else {?>
		<div class="user-panel show">
			<a href="javascript:void(0);" class="loginuser" target="_blank"><img src="<?php echo User::model()->getCurrentUserImageUrl(); ?>"/><span class="icon-caret-down"></span></a>
			<!--<a href="<?php echo $this->createUrl('site/logout'); ?>" class="logout">退出</a>-->
			<ul>
				<!-- <li><a href="http://weibo.com/u/<?php echo User::model()->findByPk(Yii::App()->user->id)->extension_user_id ?>">个人中心</a></li> -->
				<li><a href="<?php echo $this->createUrl('site/userCenter'); ?>">个人中心</a></li>
				<li><a href="<?php echo $this->createUrl('restaurant/create'); ?>">添加汤馆</a></li>
				<?php if(User::isAdmin()){ ?>
				<li><a href="<?php echo $this->createUrl('restaurant/check'); ?>">审核汤馆</a></li>
				<li><a href="<?php echo $this->createUrl('restaurant/admin'); ?>">管理汤馆</a></li>
				<?php } ?>
				<li><a href="<?php echo $this->createUrl('site/logout'); ?>" class="logout">退出</a></li>					
			</ul>
		</div>
		<?php	}?>
	</div>
	</div>
	</div><!-- mainmenu -->


<!--公共的模态窗口，提示信息用-->
<!-- Modal -->
  <div class="alertModal-dialog">
    <div class="alertModal-content">
      <div class="alertModal-header">
        <button type="button" class="close"  aria-hidden="true">&times;</button>
        <h4 class="alertModal-title" id="alertModalLabel">提示信息</h4>
      </div>
      <div class="alertModal-body">
        <div class="rating-confirm">
        	<span class="title">我的评分：</span>
        	<span class="rating-list">
			<a class="rating-icon star-on" data-title="不推荐"></a>
			<a class="rating-icon star-on" data-title="聊胜于无"></a>
			<a class="rating-icon star-on" data-title="日常饮食"></a>
			<a class="rating-icon star-on" data-title="值得品尝"></a>
			<a class="rating-icon star-on" data-title="汤中一绝"></a>
			</span>
			<span class="rating-value fonttext-shadow-2-3-5-000">0</span>
			<span class="value-desc"></span>
			<div class="clear"></div>
		</div>
		<form class="form-horizontal" role="form">
			<textarea class="form-control" id="commentContent" rows="3" placeholder="发表评论吧"></textarea>
		</form>
      </div>
      <div class="alertModal-footer">
        <button type="button" id="alertModalClose" class="btn btn-default">取消</button>
        <button type="button" id="alertModalSubmit"  class="btn btn-red">提交</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->




<!--登陆的模态窗口-->
<div id="myModal" class="modal1 fade in " >
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
<div class="modal-backdrop1"></div>
<!--主体内容部分-->
<div class="tang-content" id="tang-content">
	<?php echo $content; ?>
</div>
<div class="clear"></div>


<!--<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/bootstrap/bootstrap.min.js"></script>-->
</div><!-- page -->
<div id="footer">
	<a href="http://blog.laotangguan.com/?p=5" target="_blank">排名算法</a>
	<span class='dot'>•</span>
	<a href="http://blog.laotangguan.com/?p=9" target="_blank">建议反馈</a>
	<span class='dot'>•</span>
	<span>&copy; <?php echo date('Y'); ?>点滴科技</span>
</div><!-- footer -->
<div id="right_float_panel"><a class="top_up" href="javascript:void(0);" target="_self" title="回到顶部"><i class="fa fa-arrow-circle-up" ></i></a></div>

<script src="http://cdn.bootcss.com/twitter-bootstrap/3.0.2/js/bootstrap.min.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/main_tang.js"></script>
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
	,top: footerTop
	});
} else {
	$footer.css({
	position: "static"
	});

}
}
$(window).scroll(positionFooter).resize(positionFooter);

//点击登陆弹出模态窗口
$(".login").click(function(){
	loginModal();
});
$(".loginuser").bind("mouseover",function(){
/*$(this).parent().addClass('show').bind("mouseout",function(){

$(this).removeClass('show');
});*/

});

$(".modal-backdrop1").click(function(){
	$("#myModal").hide();
	$(this).hide();
});
$(document.body).keyup(function(event){
//if(event.ctrlKey && event.which == 13)       //13等于回车键(Enter)键值,ctrlKey 等于 Ctrl
//alert("按了ctrl+回车键!")
if(event.keyCode==27)
	$("#myModal").hide();
$(".modal-backdrop1").hide();
});


//县菜单鼠标放上去显示下级菜单
$(".areamenu").hover(function(){
	$(this).find('ul').show(100);
},function(){
	$(this).find('ul').hide(100);
});

//回到顶部ＪＳ
$(window).scroll(function(){

	if($(window).scrollTop()>($(document.body).height()/4))
	{
		$("#right_float_panel").show();
	}else
	{
		$("#right_float_panel").hide();
	}

});

//回到顶部功能
$(".top_up").click(function(){
	$('html,body').animate({scrollTop:'0px'},500);
});

/*
 *类型、区域的A标签加上点击激活状态的样式
 */
// var currentItem=null;
// $("#area-menu>ul>li>a").click(function(){
// 	if (currentItem) {
// 		currentItem.removeClass('active');
// 	}
// 	$(this).addClass('active');
// 	currentItem=$(this);

// });

});
</script>
</body>
</html>
