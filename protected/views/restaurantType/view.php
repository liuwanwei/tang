<?php
/* @var $this RestaurantTypeController */
/* @var $model RestaurantType */

$this->breadcrumbs=array(
	'Restaurant Types'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List RestaurantType', 'url'=>array('index')),
	array('label'=>'Create RestaurantType', 'url'=>array('create')),
	array('label'=>'Update RestaurantType', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete RestaurantType', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage RestaurantType', 'url'=>array('admin')),
);
?>

<h1>View RestaurantType #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'name',
	),
)); ?>
