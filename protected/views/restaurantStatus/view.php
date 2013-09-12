<?php
/* @var $this RestaurantStatusController */
/* @var $model RestaurantStatus */

$this->breadcrumbs=array(
	'Restaurant Statuses'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List RestaurantStatus', 'url'=>array('index')),
	array('label'=>'Create RestaurantStatus', 'url'=>array('create')),
	array('label'=>'Update RestaurantStatus', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete RestaurantStatus', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage RestaurantStatus', 'url'=>array('admin')),
);
?>

<h1>View RestaurantStatus #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'name',
	),
)); ?>
