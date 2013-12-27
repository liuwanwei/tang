<?php 
$this->layout="column_main";
?>
<div class="g-alert">
	<div>
		<i class="fa fa-bullhorn"></i><span class="alertmsg"><?php echo $message; ?></span>
	</div>
	<div class="clear"></div>
	<div>
		<span id="time">3</span>秒后，自动跳转
	</div>
</div>
<script type="text/javascript">
var i=3;
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
