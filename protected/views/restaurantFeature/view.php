<?php
/* @var $this RestaurantFeatureController */
/* @var $model RestaurantFeature */

$this->breadcrumbs=array(
	'Restaurant Features'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List RestaurantFeature', 'url'=>array('index')),
	array('label'=>'Create RestaurantFeature', 'url'=>array('create')),
	array('label'=>'Update RestaurantFeature', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete RestaurantFeature', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage RestaurantFeature', 'url'=>array('admin')),
);
?>

<h1>View RestaurantFeature #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'name',
		'image_url',
	),
)); ?>
