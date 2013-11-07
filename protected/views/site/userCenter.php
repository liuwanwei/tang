<?php
/* @var $this RestaurantController */
/* @var $model Restaurant */

// $this->breadcrumbs=array(
// 	'Restaurants'=>array('index'),
// 	'Manage',
// );

// $this->menu=array(
// 	array('label'=>'List Restaurant', 'url'=>array('index')),
// 	array('label'=>'Create Restaurant', 'url'=>array('create')),
// );

// Yii::app()->clientScript->registerScript('search', "
// $('.search-button').click(function(){
// 	$('.search-form').toggle();
// 	return false;
// });
// $('.search-form form').submit(function(){
// 	$('#restaurant-grid').yiiGridView('update', {
// 		data: $(this).serialize()
// 	});
// 	return false;
// });
// ");
?>
<?php 
$this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'restaurant-grid',
	'dataProvider'=>$model->searchCreatedByMe(0),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'name',
		// 'phone',
		// 'business_hour',
		'address',
		'county_id',	// TODO 直接显示中文的“区”，如涧西区。
		'is_checked',	// TODO 直接显示中文的‘通过’或“未通过'。
		/*
		'area',
		'is_shutdown',
		'image_url',
		'latitude',
		'longitude',
		'description',
		*/
		array(
			'class'=>'CButtonColumn',
			'template'=>'{update}{delete}',
			'buttons'=>array(
				'update'=>array(
					'url'=>'Yii::app()->createUrl("restaurant/update", array("id"=>$data->id))'
				),
				'delete'=>array(
					'url'=>'Yii::app()->createUrl("restaurant/delete", array("id"=>$data->id))'
				),
			),
			
		),
	),
)); 

$this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'restaurant-grid',
	'dataProvider'=>$model->searchCreatedByMe(1),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'name',
		// 'phone',
		// 'business_hour',
		'address',
		'county_id',	// TODO 直接显示中文的“区”，如涧西区。
		'is_checked',	// TODO 直接显示中文的‘通过’或“未通过'。
		/*
		'area',
		'is_shutdown',
		'image_url',
		'latitude',
		'longitude',
		'description',
		*/
		array(
			'class'=>'CButtonColumn',
			'template'=>'{view}',
			'buttons'=>array(
				'view'=>array(
					'url'=>'Yii::app()->createUrl("details/$data->id")'
				),
			),
			
		),
	),
));

?>
