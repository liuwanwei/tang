<?php 
$this->layout="main-tang";
Yii::app()->clientScript->registerScriptFile('//code.jquery.com/jquery-1.10.2.min.js');
?>
<div class="g-alert">
	<div>
		<i class="fa fa-bullhorn"></i><span class="alertmsg"><?php echo $message; ?></span>
	</div>
<div>
	<span id="time">0</span>秒后，跳转
</div>
</div>
<script type="text/javascript">
var i=0;
var timejishi= setInterval(function(){
	i++;
	document.getElementById('time').innerText=i+"";
	if (i>=3) {
		clearInterval(timejishi);
		window.location="<?php echo $url; ?>";
	}
},1000);
$('.g-alert').animate({opacity:0.9},3000);
</script>
