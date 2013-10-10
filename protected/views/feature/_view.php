<?php
/* @var $this FeatureController */
/* @var $data Feature */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('restaurant_id')); ?>:</b>
	<?php echo CHtml::encode($data->restaurant_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('feature_id')); ?>:</b>
	<?php echo CHtml::encode($data->feature_id); ?>
	<br />


</div>