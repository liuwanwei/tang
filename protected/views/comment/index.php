<?php
/* @var $this CommentController */
/* @var $dataProvider CActiveDataProvider */
 
$this->widget('zii.widgets.CDetailView', array(
		'data'=>$restaurant,
		'attributes'=>array(
				'id',
				'name',
				'phone',
				'business_hour',
				'address',
				'county.name',
				'area.name',
				'status.name',
				'image_url',
				'latitude',
				'longitude',
				'description',
		),
));

$this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
	'enablePagination'=>false,
)); ?>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>