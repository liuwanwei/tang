<?php
/* @var $this RestaurantController */
/* @var $data Restaurant */
?>

<div class="view">
	<div class="restaurant-profile">
		<?php 
		if (! empty($data->image_url)) {
			echo CHtml::image(Yii::app()->baseUrl.$data->image_url, $data->name,array("width"=>75, 'height'=>75));
		}else{
			echo CHtml::image('images/default_profile.jpg'); 
		}
		?>
	</div>
	<div class="restaurant-detail">
		<!--
		<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
		<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
		<br />
		-->

		<b>
		<?php echo CHtml::link(CHtml::encode($data->name), array('comment/index', 'restaurant_id'=>$data->id)); ?></b>
		<br />

		<b><?php echo CHtml::encode($data->getAttributeLabel('address')); ?>:</b>
		<?php echo CHtml::encode($data->address); ?>
		<br />

		<!--
		<b><?php echo CHtml::encode($data->getAttributeLabel('county_id')); ?>:</b>
		<?php echo CHtml::encode($data->county['name']); ?>
		<br />

		<b><?php echo CHtml::encode($data->getAttributeLabel('area_id')); ?>:</b>
		<?php echo CHtml::encode($data->area['name']); ?>
		<br />
		-->

		<b><?php echo CHtml::encode($data->getAttributeLabel('phone')); ?>:</b>
		<?php echo CHtml::encode($data->phone); ?>
		<br />

		<b><?php echo CHtml::encode($data->getAttributeLabel('business_hour')); ?>:</b>
		<?php echo CHtml::encode($data->business_hour); ?>
		<br />

		<b><?php echo CHtml::encode($data->getAttributeLabel('is_shutdown')); ?>:</b>
		<?php echo CHtml::encode($data->status['name']); ?>
		<br />

		<b><?php echo CHtml::encode($data->getAttributeLabel('votes')); ?>:</b>
		<?php echo CHtml::encode($data->votes); ?>
		
		<b><?php echo CHtml::encode($data->getAttributeLabel('average_points')); ?>:</b>
		<?php echo CHtml::encode($data->average_points); ?>

		<b><?php echo CHtml::encode($data->getAttributeLabel('weighted_points')); ?>:</b>
		<?php echo CHtml::encode($data->weighted_points); ?>
		<br />

		<?php /*
		<b><?php echo CHtml::encode($data->getAttributeLabel('description')); ?>:</b>
		<?php echo CHtml::encode($data->description); ?>
		<br />

		*/ ?>
	</div>
	<div style="clear:both;"></div>

</div>