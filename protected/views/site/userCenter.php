<?php
/* @var $this RestaurantController */
/* @var $model Restaurant */
?>
<div style="width:100%;height:1px;clear:both;"></div>
<?php if($uncheckedItemsCount>0){ ?>
<div class="user-header">我添加的汤馆 - 未审核</div>
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
	

<div class="user-header">我添加的汤馆 - 已审核</div>
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

<?php if ($checkedItemsCount<=0 && $uncheckedItemsCount<=0) {?>
	<div class="error_403">
	<div class="error_403_text">
		<p><i class="fa fa-quote-left"></i><span>等等，你好像没有一个汤馆</span><i class="fa fa-quote-right"></i></p>
		<p>快把你周围的汤馆<a href="<?php echo $this->createUrl('restaurant/create') ;?>">添加</a>进来吧</p>
	</div>
	</div>
<?php }?>
