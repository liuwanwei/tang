<?php
/* @var $this RestaurantController */
/* @var $data Restaurant */
?>

<div class="view">
	<div class="restaurant-profile">
		<?php echo CHtml::image('images/default_profile.jpg'); ?>
	</div>
	<div class="restaurant-detail">
		<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
		<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
		<br />

		<b><?php echo CHtml::encode($data->getAttributeLabel('name')); ?>:</b>
		<?php echo CHtml::encode($data->name); ?>
		<br />

		<b><?php echo CHtml::encode($data->getAttributeLabel('phone')); ?>:</b>
		<?php echo CHtml::encode($data->phone); ?>
		<br />

		<b><?php echo CHtml::encode($data->getAttributeLabel('business_hour')); ?>:</b>
		<?php echo CHtml::encode($data->business_hour); ?>
		<br />

		<b><?php echo CHtml::encode($data->getAttributeLabel('address')); ?>:</b>
		<?php echo CHtml::encode($data->address); ?>
		<br />

		<b><?php echo CHtml::encode($data->getAttributeLabel('county_id')); ?>:</b>
		<?php echo CHtml::encode($data->county['name']); ?>
		<br />

		<b><?php echo CHtml::encode($data->getAttributeLabel('area_id')); ?>:</b>
		<?php echo CHtml::encode($data->area['name']); ?>
		<br />

		<?php /*
		<b><?php echo CHtml::encode($data->getAttributeLabel('is_shutdown')); ?>:</b>
		<?php echo CHtml::encode($data->is_shutdown); ?>
		<br />

		<b><?php echo CHtml::encode($data->getAttributeLabel('image_url')); ?>:</b>
		<?php echo CHtml::encode($data->image_url); ?>
		<br />

		<b><?php echo CHtml::encode($data->getAttributeLabel('latitude')); ?>:</b>
		<?php echo CHtml::encode($data->latitude); ?>
		<br />

		<b><?php echo CHtml::encode($data->getAttributeLabel('longitude')); ?>:</b>
		<?php echo CHtml::encode($data->longitude); ?>
		<br />

		<b><?php echo CHtml::encode($data->getAttributeLabel('description')); ?>:</b>
		<?php echo CHtml::encode($data->description); ?>
		<br />

		*/ ?>
	</div>
	<div style="clear:both;"></div>

</div>