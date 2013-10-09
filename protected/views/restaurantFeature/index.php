<?php
/* @var $this RestaurantFeatureController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Restaurant Features',
);

$this->menu=array(
	array('label'=>'Create RestaurantFeature', 'url'=>array('create')),
	array('label'=>'Manage RestaurantFeature', 'url'=>array('admin')),
);
?>

<h1>Restaurant Features</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
