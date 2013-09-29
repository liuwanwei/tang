<?php
/* @var $this RestaurantTypeController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Restaurant Types',
);

$this->menu=array(
	array('label'=>'Create RestaurantType', 'url'=>array('create')),
	array('label'=>'Manage RestaurantType', 'url'=>array('admin')),
);
?>

<h1>Restaurant Types</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
