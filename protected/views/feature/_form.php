<?php
/* @var $this FeatureController */
/* @var $model Feature */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'feature-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'restaurant_id'); ?>
		<?php echo $form->textField($model,'restaurant_id'); ?>
		<?php echo $form->error($model,'restaurant_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'feature_id'); ?>
		<?php echo $form->textField($model,'feature_id'); ?>
		<?php echo $form->error($model,'feature_id'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->