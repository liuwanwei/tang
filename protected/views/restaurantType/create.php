<?php
/* @var $this RestaurantTypeController */
/* @var $model RestaurantType */

$this->breadcrumbs=array(
	'Restaurant Types'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List RestaurantType', 'url'=>array('index')),
	array('label'=>'Manage RestaurantType', 'url'=>array('admin')),
);
?>

<h1>Create RestaurantType</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>