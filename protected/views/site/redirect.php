<?php 
$this->layout="column_main";
Yii::app()->clientScript->registerScriptFile('//code.jquery.com/jquery-1.10.2.min.js');
?>
<div class="g-alert">
	<div>
		<i class="fa fa-bullhorn"></i><span class="alertmsg">这是一个错误，我在测试这个错误的长度有多长可以换行!<?php //echo $message; ?></span>
	</div>
	<div class="clear"></div>
	<div>
		<span id="time">5</span>秒后，自动跳转
	</div>
</div>
<script type="text/javascript">
var i=5;
var timejishi= setInterval(function(){
	i--;
	document.getElementById('time').innerText=i+"";
	if (i<=1) {
		clearInterval(timejishi);
		window.location="<?php echo $url; ?>";
	}
},1000);
$('.g-alert').animate({opacity:0.9},3000);
</script>
