<?php
/* @var $this FeatureController */
/* @var $model Feature */

$this->breadcrumbs=array(
	'Features'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List Feature', 'url'=>array('index')),
	array('label'=>'Create Feature', 'url'=>array('create')),
	array('label'=>'Update Feature', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Feature', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Feature', 'url'=>array('admin')),
);
?>

<h1>View Feature #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'restaurant_id',
		'feature_id',
	),
)); ?>
