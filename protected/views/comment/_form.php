<?php
/* @var $this CommentController */
/* @var $model Comment */
/* @var $form CActiveForm */
?>

<div>
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'comment-form',
	'enableAjaxValidation'=>false,
	'action'=>Yii::app()->createUrl('/comment/create',array('restaurantId'=>$restaurantId))
)); ?>

	<?php echo $form->errorSummary($model); ?>

	<div>
		<?php echo $form->textArea($model,'content',array('id'=>'comment_content', 'rows'=>6, 'cols'=>120)); ?>
		<?php echo $form->error($model,'content'); ?>
	</div>

	<div class="buttons">
		<?php echo CHtml::submitButton('发布评论',array('id'=>'commit')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->

<script>	
$().ready(function(){
	$('#comment_content').val("");
	
	$('#commit').click(function(){
		var block = false;
		var message;
		var guest = <?php if(Yii::app()->user->isGuest == 1) {
			echo 1;}else {echo 2;}?>;
		if(guest == 1) {
			block = true;
			message = "请先登陆";
		}else if($('#comment_content').val() == "") {
			block = true;
			message = "请填写您的评论";	
		}
		
		if(block == true) {
			alert(message);
			return false;
		}
	});
});
</script>