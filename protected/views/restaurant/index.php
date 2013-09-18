<?php
/* @var $this RestaurantController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'汤馆列表',
);

$this->menu=array(
	array('label'=>'Create Restaurant', 'url'=>array('create')),
	array('label'=>'Manage Restaurant', 'url'=>array('admin')),
);
?>

<div id="county-menu">
	<?php $this->widget('zii.widgets.CMenu',array('items'=>$countyMenu)); ?>
</div><!-- countymenu -->

<?php if (! empty($areaMenu)) { ?>
<div id="area-menu">
	<?php $this->widget('zii.widgets.CMenu',array('items'=>$areaMenu)); ?>
</div><!-- area-menu -->
<?php } ?>

<h1>汤馆列表</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
	'cssFile' => Yii::app()->request->baseUrl. '/css/_restaurant_item.css',
)); ?>
