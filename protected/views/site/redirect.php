<?php 
$this->layout="main-tang";
?>
<div>
<?php echo $message; ?>
</div>
<span id="time"></span>
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
</script>