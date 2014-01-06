<?php
/* @var $this RestaurantController */
/* @var $model Restaurant */
?>

<?php if($uncheckedItemsCount>0){ ?>
<div class="user-header">我的未审核通过的汤馆</div>
<?php 
$this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'restaurant-grid-unchecked',
	'dataProvider'=>$uncheckedDataProvider,
	'filter'=>$model,
	'itemsCssClass'=>'table table-hover table-uc',
	'cssFile' => false,
	'emptyText'=>false,
	'summaryCssClass'=>'display-none',
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
			'value'=>'$data->county_id==0 ? "" : $data->county->name',
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
<?php }?>

<?php if ($checkedItemsCount>0) {?>
	

<div class="user-header">我的已审核通过的汤馆</div>
<?php 
$this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'restaurant-grid-checked',
	'dataProvider'=>$checkedDataProvider,
	'cssFile' => Yii::app()->request->baseUrl. '/css/tang_uc_style.css',
	'itemsCssClass'=>'table table-hover table-uc',
	'filter'=>$model,
	'summaryCssClass'=>'display-none',
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
<?php } ?>
