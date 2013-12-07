<?php
/* @var $this RestaurantController */
/* @var $model Restaurant */

$this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'restaurant-grid-unchecked',
	'dataProvider'=>$model->searchCreatedByMe(0),
	'filter'=>$model,
	'itemsCssClass'=>'table table-hover table-uc',
	'emptyText'=>'没有数据',
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
			'updateButtonImageUrl'=>false,
			'updateButtonLabel'=>'',
			'updateButtonOptions'=>array('class'=>'fa fa-pencil'),
			'deleteButtonImageUrl'=>false,
			'deleteButtonLabel'=>'',
			'deleteButtonOptions'=>array('class'=>'fa fa-times-circle'),
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
	'id'=>'restaurant-grid-checked',
	'dataProvider'=>$model->searchCreatedByMe(1),
	'cssFile' => Yii::app()->request->baseUrl. '/css/tang_uc_style.css',
	'itemsCssClass'=>'table table-hover table-uc',
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
			'viewButtonImageUrl'=>false,
			'viewButtonLabel'=>'',
			'viewButtonOptions'=>array('class'=>'fa fa-search'),
			'template'=>'{view}',
			'buttons'=>array(
				'view'=>array(
					'url'=>'Yii::app()->createUrl("details/$data->id")'
				),
			),
			
		),
	),

	'pagerCssClass'=>'tang-pager',
	'pager'=>array('header'=>'',
			'prevPageLabel'=>'«',
			'nextPageLabel'=>'»',
			'firstPageLabel'=>'首页',
			'lastPageLabel'=>'末页',
			'cssFile'=>Yii::app()->request->baseUrl.'/css/pager.css'
			),
));

?>
