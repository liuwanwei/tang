<?php
/* @var $this CountyController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Counties',
);

$this->menu=array(
	array('label'=>'Create County', 'url'=>array('create')),
	array('label'=>'Manage County', 'url'=>array('admin')),
);
?>

<h1>Counties</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
