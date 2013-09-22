<?php
/* @var $this CommentController */
/* @var $dataProvider CActiveDataProvider */

$this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
	'enablePagination'=>false,
)); ?>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
