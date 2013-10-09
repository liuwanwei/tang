<?php
/* @var $this RestaurantController */
/* @var $model Restaurant */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'restaurant-form',
	'htmlOptions' => array('enctype'=>'multipart/form-data'),
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<!-- <div class="row">
		<?php echo $form->labelEx($model,'id'); ?>
		<?php echo $form->textField($model,'id'); ?>
		<?php echo $form->error($model,'id'); ?>
	</div> -->

	<div class="row">
		<?php echo $form->labelEx($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>128)); ?>
		<?php echo $form->error($model,'name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'type_id'); ?>
		<!--<?php echo $form->textField($model,'type_id'); ?> -->
		<?php echo $form->dropDownList($model, 'type_id',  $selectors['types']);?>
		<?php echo $form->error($model,'type_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'county_id'); ?>
		<!--<?php echo $form->textField($model,'county_id'); ?> -->
		<?php echo $form->dropDownList($model, 'county_id',  $selectors['counties']);?>
		<?php echo $form->error($model,'county_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'area_id'); ?>
		<!--<?php echo $form->textField($model,'area_id'); ?>-->
		<?php echo $form->dropDownList($model, 'area_id', $selectors['areas']); ?>
		<?php echo $form->error($model,'area_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'address'); ?>
		<?php echo $form->textField($model,'address',array('size'=>60,'maxlength'=>128)); ?>
		<?php echo $form->error($model,'address'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'phone'); ?>
		<?php echo $form->textField($model,'phone',array('size'=>60,'maxlength'=>64)); ?>
		<?php echo $form->error($model,'phone'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'business_hour'); ?>
		<?php echo $form->textField($model,'business_hour',array('size'=>60,'maxlength'=>128)); ?>
		<?php echo $form->error($model,'business_hour'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'is_shutdown'); ?>
		<!--<?php echo $form->textField($model,'is_shutdown'); ?>-->
		<?php echo $form->dropDownList($model, 'is_shutdown', $selectors['statuses']);?>
		<?php echo $form->error($model,'is_shutdown'); ?>
	</div>

	<!-- 图像文件上传框 -->
	<div class="row">
		<?php echo $form->labelEx($model,'image_url'); ?>
		<?php echo CHtml::activeFileField($model, 'image_url'); ?>
		<?php echo $form->error($model,'image_url'); ?>
	</div>

	<!-- 已有图像显示框 -->
	<?php if($model->isNewRecord!='1' &&  !empty($model->image_url)){ ?>
	<div class="row">
		<?php echo CHtml::image(Yii::app()->request->baseUrl.$model->image_url,"image",array("width"=>300)); ?>
	</div>
	<?php } ?>
<!-- 
	<div class="row">
		<?php echo $form->labelEx($model,'weighted_points'); ?>
		<?php echo $form->textField($model,'weighted_points'); ?>
		<?php echo $form->error($model,'weighted_points'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'votes'); ?>
		<?php echo $form->textField($model,'votes'); ?>
		<?php echo $form->error($model,'votes'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'average_points'); ?>
		<?php echo $form->textField($model,'average_points'); ?>
		<?php echo $form->error($model,'average_points'); ?>
	</div> -->

	<div class="row">
		<?php echo $form->labelEx($model,'description'); ?>
		<?php echo $form->textField($model,'description',array('size'=>60,'maxlength'=>256)); ?>
		<?php echo $form->error($model,'description'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->