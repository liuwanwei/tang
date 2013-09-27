<?php
/* @var $this CommentController */
/* @var $dataProvider CActiveDataProvider */

$attribute = $restaurant->attributeLabels();

$this->widget('zii.widgets.CDetailView', array(
		'data'=>$restaurant,
		'attributes'=>array(
// 				'id',
				'name',
				array(
						'label'=>$attribute['address'],
						'type'=>'raw',
						'value'=>CHtml::label($restaurant->address."&nbsp &nbsp &nbsp &nbsp  &nbsp  &nbsp".$attribute['phone']
								."&nbsp  &nbsp".$restaurant->phone, ''),
				),
				
				array(
						'label'=>$attribute['county.name'],
						'type'=>'raw',
						'value'=>CHtml::label($restaurant->county->name."&nbsp &nbsp &nbsp"
								.$restaurant->area->name, ''),
				),
				
// 				'phone',
				'business_hour',
				array(
						'label'=>'评分',
						'type'=>'raw',
						'value'=>'等小梁填充',
				),
						
// 				'county.name',
// 				'area.name',
// 				'status.name',
// 				'image_url',
// 				'latitude',
// 				'longitude',
// 				'description',
		),
));

$this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
	'enablePagination'=>false,
)); ?>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>