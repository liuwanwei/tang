<?php
/* @var $this RestaurantController */
/* @var $model Restaurant */
?>

<?php 

	// 自定义搜索结果操作菜单项外观。
	$actionMenu = array(
		'class'=>'CButtonColumn',
		'viewButtonImageUrl'=>false,
		'viewButtonLabel'=>'',
		'viewButtonOptions'=>array('class'=>'fa fa-search big-fontcss', 'target'=>'_blank'),
		'viewButtonUrl'=>'Yii::app()->createUrl("details/$data->id")',
		'updateButtonImageUrl'=>false,
		'updateButtonLabel'=>'',
		'updateButtonOptions'=>array('class'=>'fa fa-pencil big-fontcss', 'target'=>'_blank'),
		'deleteButtonImageUrl'=>false,
		'deleteButtonLabel'=>'',
		'deleteButtonOptions'=>array('class'=>'fa fa-times-circle big-fontcss', 'target'=>'_blank'),
	);

	// 只有管理员有编辑和删除操作入口，其他人只有查看入口。
	if (Yii::app()->user->isAdmin) {
		$actionMenu['template'] = '{view}{update}{delete}';
	}else{
		$actionMenu['template'] = '{view}';
	}

	$columns = array(
		'name',
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
		$actionMenu,
	);

	$this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'restaurant-grid',
	'dataProvider'=>$model->search(),
	'cssFile' => Yii::app()->request->baseUrl. '/css/tang_uc_style.css',
	'itemsCssClass'=>'table table-hover table-uc',
	// 'filter'=>$model,	// 屏蔽CGridView中的筛选输入框。
	'columns'=> $columns,
	'pagerCssClass'=>'tang-pager',
	'pager'=>array('header'=>'',
			'prevPageLabel'=>'«',
			'nextPageLabel'=>'»',
			'firstPageLabel'=>'首页',
			'lastPageLabel'=>'末页',
			'cssFile'=>Yii::app()->request->baseUrl.'/css/pager.css'
			),

)); ?>



