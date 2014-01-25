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
$this->pageTitle="老汤馆-汤馆审核";
?>
<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'restaurant-grid',
	'dataProvider'=>$model->searchUnchecked(),
	'cssFile' => Yii::app()->request->baseUrl. '/css/tang_uc_style.css',
	'itemsCssClass'=>'table table-hover table-uc',
	'emptyText'=>'没有数据',
	'filter'=>$model,
	'columns'=>array(
		'name',
		// 'phone',
		// 'business_hour',
		array(
			'name'=>'address',
			'value'=>'$data->address',
			'filter'=>false,
			),
		array(
			'name'=>'county_id',
			'value'=>'$data->county->name',
			'filter'=>false,
		),
		array(
			'name'=>'is_checked',
			'value'=>'$data->is_checked==1?"通过":"未通过"',
			'filter'=>false,
		),
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
			'viewButtonUrl'=>'Yii::app()->createUrl("details/$data->id")',
			'updateButtonImageUrl'=>false,
			'updateButtonLabel'=>'',
			'updateButtonOptions'=>array('class'=>'fa fa-pencil big-fontcss'),
			'deleteButtonImageUrl'=>false,
			'deleteButtonLabel'=>'',
			'deleteButtonOptions'=>array('class'=>'fa fa-times-circle big-fontcss'),
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
)); ?>
