<?php
/* @var $this RestaurantTypeController */
/* @var $model RestaurantType */

$this->breadcrumbs=array(
	'Restaurant Types'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List RestaurantType', 'url'=>array('index')),
	array('label'=>'Create RestaurantType', 'url'=>array('create')),
	array('label'=>'View RestaurantType', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage RestaurantType', 'url'=>array('admin')),
);
?>

<h1>Update RestaurantType <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>