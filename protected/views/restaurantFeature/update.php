<?php
/* @var $this RestaurantFeatureController */
/* @var $model RestaurantFeature */

$this->breadcrumbs=array(
	'Restaurant Features'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List RestaurantFeature', 'url'=>array('index')),
	array('label'=>'Create RestaurantFeature', 'url'=>array('create')),
	array('label'=>'View RestaurantFeature', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage RestaurantFeature', 'url'=>array('admin')),
);
?>

<h1>Update RestaurantFeature <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>