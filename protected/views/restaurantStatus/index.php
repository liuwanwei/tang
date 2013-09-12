<?php
/* @var $this RestaurantStatusController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Restaurant Statuses',
);

$this->menu=array(
	array('label'=>'Create RestaurantStatus', 'url'=>array('create')),
	array('label'=>'Manage RestaurantStatus', 'url'=>array('admin')),
);
?>

<h1>Restaurant Statuses</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
