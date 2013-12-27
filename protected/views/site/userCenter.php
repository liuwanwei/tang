<?php
/* @var $this RestaurantController */
/* @var $model Restaurant */
?>
<div class="user-header alert alert-danger">我的未审核通过的汤馆</div>
<?php 
$this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'restaurant-grid-unchecked',
	'dataProvider'=>$model->searchCreatedByMe(0),
	'filter'=>$model,
	'itemsCssClass'=>'table table-hover table-uc',
	'emptyText'=>'没有数据',
	'summaryCssClass'=>'display-none',
		'columns'=>array(
		array(
			'name'=>'id',
			'value'=>'$data->id',
			'filter'=>false,
			),
		'name',
		// 'phone',
		// 'business_hour',
		array(
			'name'=>'address',
			'value'=>'$data->address',
			'filter'=>false,
			),
		//'county_id',	// TODO 直接显示中文的“区”，如涧西区。
		array(
			'name'=>'county_id',
			'value'=>'$data->county_id==0 ? "" : $data->county->name',
			'filter'=>false,
		),
		array(
			'name'=>'is_checked',
			'value'=>'$data->is_checked==1?"通过":"未通过"',
			'filter'=>false,
		),	// TODO 直接显示中文的‘通过’或“未通过'。
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
			'updateButtonOptions'=>array('class'=>'fa fa-pencil big-fontcss'),
			'deleteButtonImageUrl'=>false,
			'deleteButtonLabel'=>'',
			'deleteButtonOptions'=>array('class'=>'fa fa-times-circle big-fontcss'),
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
	'pagerCssClass'=>'tang-pager',
	'pager'=>array('header'=>'',
			'prevPageLabel'=>'«',
			'nextPageLabel'=>'»',
			'firstPageLabel'=>'首页',
			'lastPageLabel'=>'末页',
			'cssFile'=>false,
			),
)); 

?>

<div class="user-header alert alert-success">我的已审核通过的汤馆</div>
<?php 
$this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'restaurant-grid-checked',
	'dataProvider'=>$model->searchCreatedByMe(1),
	'cssFile' => Yii::app()->request->baseUrl. '/css/tang_uc_style.css',
	'itemsCssClass'=>'table table-hover table-uc',
	'filter'=>$model,
	'summaryCssClass'=>'display-none',
	'columns'=>array(
		array(
			'name'=>'id',
			'value'=>'$data->id',
			'filter'=>false,
			),
		'name',
		// 'phone',
		// 'business_hour',
		array(
			'name'=>'address',
			'value'=>'$data->address',
			'filter'=>false,
			),
		//'county_id',	// TODO 直接显示中文的“区”，如涧西区。
		array(
			'name'=>'county_id',
			'value'=>'$data->county_id==0 ? "" : $data->county->name',
			'filter'=>false,
		),
		array(
			'name'=>'is_checked',
			'value'=>'$data->is_checked==1?"通过":"未通过"',
			'filter'=>false,
		),	// TODO 直接显示中文的‘通过’或“未通过'。
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
			'viewButtonOptions'=>array('class'=>'fa fa-search big-fontcss'),
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
