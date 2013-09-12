<?php
/* @var $this RestaurantStatusController */
/* @var $model RestaurantStatus */

$this->breadcrumbs=array(
	'Restaurant Statuses'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List RestaurantStatus', 'url'=>array('index')),
	array('label'=>'Create RestaurantStatus', 'url'=>array('create')),
	array('label'=>'View RestaurantStatus', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage RestaurantStatus', 'url'=>array('admin')),
);
?>

<h1>Update RestaurantStatus <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>