<?php
/* @var $this RestaurantController */
/* @var $model Restaurant */

$this->breadcrumbs=array(
	'Restaurants'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Restaurant', 'url'=>array('index')),
	array('label'=>'Manage Restaurant', 'url'=>array('admin')),
);
?>

<h1>Create Restaurant</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>