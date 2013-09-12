<?php
/* @var $this RestaurantStatusController */
/* @var $model RestaurantStatus */

$this->breadcrumbs=array(
	'Restaurant Statuses'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List RestaurantStatus', 'url'=>array('index')),
	array('label'=>'Manage RestaurantStatus', 'url'=>array('admin')),
);
?>

<h1>Create RestaurantStatus</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>