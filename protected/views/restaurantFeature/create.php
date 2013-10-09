<?php
/* @var $this RestaurantFeatureController */
/* @var $model RestaurantFeature */

$this->breadcrumbs=array(
	'Restaurant Features'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List RestaurantFeature', 'url'=>array('index')),
	array('label'=>'Manage RestaurantFeature', 'url'=>array('admin')),
);
?>

<h1>Create RestaurantFeature</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>