<?php
/* @var $this RestaurantController */
/* @var $model Restaurant */
/* @var $form CActiveForm */
//http://lib.sinaapp.com/js/jquery/1.10.2/jquery-1.10.2.min.js
//Yii::app()->clientScript->registerScriptFile('http://lib.sinaapp.com/js/jquery/1.10.2/jquery-1.10.2.min.js');
?>
<script type="text/javascript" src="http://cdn.jsdelivr.net/fancybox/2.1.5/jquery.fancybox.js"></script>
<link rel="stylesheet" type="text/css" href="http://cdn.jsdelivr.net/fancybox/2.1.5/jquery.fancybox.css" media="screen" />
<script type="text/javascript">
	$('.fancybox').fancybox();

	function fileChange(filename)
	{
		if (filename) {
			var file_arr=filename.split('\\');
			$("#client_img_url").text(file_arr[file_arr.length-1]);
			$("#client_img").show(100);
		}
	}
</script>


<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'restaurant-form',
	'htmlOptions' => array('enctype'=>'multipart/form-data','class'=>'form-horizontal'),
	'enableAjaxValidation'=>false,
)); ?>

<?php if (isset($returnUrl)) {
	echo '<input type="hidden" id="returnUrl" name="returnUrl" value="'.$returnUrl.'" />';
} ?>
<!-- <p class="note">带<span class="required">*</span>必填</p> -->
<div style="height:10px;clear:both;"></div>
<div class="form-group">
	<?php echo $form->labelEx($model,'image_url',array('class'=>"col-sm-2 control-label")); ?>
	<div class="col-sm-8">
		<?php if (!empty($model->image_url)) { ?>
		<span class="upload-image"><a class="fancybox" href="<?php echo $model->image_url;?>" data-fancybox-group="gallery" ><img src="<?php echo $model->image_url;?>" class="img-rounded" width="100px" height="100px" alt="" /></a></span>
		<?php } ?>
		<a href="javascript:;" class="a-upload fa fa-plus-square-o">
			<?php echo $form->fileField($model,'image_url',array('onchange'=>'fileChange(this.value);'));?>
		</a>
	</div>

</div>

<div class="form-group" id="client_img" style="display:none;">
<label class="col-sm-2 control-label">图片地址</label>
	<div class="col-sm-8">
		<span id="client_img_url"></span>
	</div>

</div>
<div style="width:100%;clear:both;">
	<div class="form-group">
		<?php echo $form->labelEx($model,'name',array('class'=>"col-sm-2 control-label")); ?>
		<div class="col-sm-8">
		<?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>128,'class'=>"form-control",'placeholder'=>'输入一个汤馆名称')); ?>
		<?php echo $form->error($model,'name'); ?>
		</div>
	</div>

	<div class="form-row-one">
	<div class="form-group">
		<?php echo $form->labelEx($model,'type_id',array('class'=>"col-sm-2 control-label")); ?>
		<!--<?php echo $form->textField($model,'type_id'); ?> -->
		<div class="col-sm-8">
		<?php echo $form->dropDownList($model, 'type_id',  $selectors['types'],array('class'=>"form-control"));?>
		<?php echo $form->error($model,'type_id'); ?>
	</div>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'county_id',array('class'=>"col-sm-2 control-label")); ?>
		<!--<?php echo $form->textField($model,'county_id'); ?> -->
		<div class="col-sm-8">
		<?php echo $form->dropDownList($model, 'county_id',  $selectors['counties'],array('class'=>"form-control"));?>
		<?php echo $form->error($model,'county_id'); ?>
	</div>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'area_id',array('class'=>"col-sm-2 control-label")); ?>
		<!--<?php echo $form->textField($model,'area_id'); ?>-->
		<div class="col-sm-8">
		<?php echo $form->dropDownList($model, 'area_id', $selectors['areas'],array('class'=>"form-control")); ?>
		<?php echo $form->error($model,'area_id'); ?>
	</div>
	</div>
</div>
	<div class="form-row-one">
	<div class="form-group">
		<?php echo $form->labelEx($model,'coordinate',array('class'=>"col-sm-2 control-label")); ?>
		<div class="col-sm-7">
		<?php echo $form->textField($model,'coordinate',  array('readonly' => 'readonly','class'=>"form-control")); ?>
		<?php echo $form->error($model,'coordinate'); ?>
	</div>
	<div class="col-sm-1">
 <!-- Button trigger modal -->
		<!-- <button class="btn btn-primary" data-toggle="modal" data-target="#mapModal">
		  定位
		</button> -->
		<a href="javascript:void(0);" title="定位" class="a-text-none fa fa-map-marker" style="text-decoration: none;" data-toggle="modal" data-target="#mapModal"></a>
	</div>
	</div>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'address',array('class'=>"col-sm-2 control-label")); ?>
		<div class="col-sm-8">
		<?php echo $form->textField($model,'address',array('size'=>60,'maxlength'=>256,'class'=>"form-control",'placeholder'=>'输入地址')); ?>
		<?php echo $form->error($model,'address'); ?>
	</div>
	</div>

	<!-- <div class="form-row-one">
	<div class="form-group">
		<?php echo $form->labelEx($model,'phone',array('class'=>"col-sm-2 control-label")); ?>
		<div class="col-sm-8">
		<?php echo $form->textField($model,'phone',array('size'=>60,'maxlength'=>64,'class'=>"form-control",'placeholder'=>'输入电话')); ?>
		<?php echo $form->error($model,'phone'); ?>
	</div>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'business_hour',array('class'=>"col-sm-2 control-label")); ?>
		<div class="col-sm-8">
		<?php echo $form->textField($model,'business_hour',array('size'=>60,'maxlength'=>128,'class'=>"form-control")); ?>
		<?php echo $form->error($model,'business_hour'); ?>
	</div>
	</div>
	</div> -->

	<?php  if(User::isAdmin()) {
		echo '<div class="form-group">';		
		echo $form->labelEx($model, "is_checked",array('class'=>"col-sm-2 control-label"));
		echo '<div class="col-sm-8">';
		echo $form->dropDownList($model, 'is_checked', array('0'=>'未审核', '1'=>'已审核'),array('class'=>"form-control"));
		echo $form->error($model, 'is_checked');
		echo '</div>';
		echo '</div>';
	}
	?>
		<!--
		<div class="form-row-one">
		<div class="row">
			<?php echo $form->labelEx($model,'is_shutdown'); ?>
			<?php echo $form->dropDownList($model, 'is_shutdown', $selectors['statuses']);?>
			<?php echo $form->error($model,'is_shutdown'); ?>
		</div>
		-->

		<!-- 图像文件上传框 
		<div class="row">
			<?php echo $form->labelEx($model,'image_url'); ?>
			<?php echo CHtml::activeFileField($model, 'image_url'); ?>
			<?php echo $form->error($model,'image_url'); ?>
		</div>-->

		<!-- 已有图像显示框 
		<?php if($model->isNewRecord!='1' &&  !empty($model->image_url)){ ?>
		<div class="row">
			<?php echo CHtml::image(Yii::app()->request->baseUrl.$model->image_url,"image",array("width"=>300)); ?>
		</div>
		<?php } ?>
		</div>-->

	<!-- 
	<div class="row">
		<?php echo $form->labelEx($model,'weighted_points'); ?>
		<?php echo $form->textField($model,'weighted_points'); ?>
		<?php echo $form->error($model,'weighted_points'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'votes'); ?>
		<?php echo $form->textField($model,'votes'); ?>
		<?php echo $form->error($model,'votes'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'average_points'); ?>
		<?php echo $form->textField($model,'average_points'); ?>
		<?php echo $form->error($model,'average_points'); ?>
	</div> 

	<div class="row">
		<?php echo $form->labelEx($model,'description'); ?>
		<?php echo $form->textField($model,'description',array('size'=>60,'maxlength'=>256)); ?>
		<?php echo $form->error($model,'description'); ?>
	</div>-->

	<div class="row buttons aligin-c">
		<?php echo CHtml::submitButton($model->isNewRecord ? '新增提交' : '保存提交',array('class'=>'btn btn-primary btn-lg')); ?>
	</div>
</div>
<?php $this->endWidget(); ?>



<!-- Modal -->
<div class="modal fade" id="mapModal" tabindex="-1" role="dialog" aria-labelledby="mapModalLabel" aria-hidden="true">
  <div class="modal-dialog" style="width:700px;">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="mapModalLabel">在地图上选择坐标</h4>
      </div>
      <div class="modal-body">
		<div style="text-align:right; width:100%;margin-bottom:5px;" class="form-group">
			<input id="mapAddress" type="textbox"  style="width:300px;" value="" placeholder="例如：洛阳老城区"><button onclick="codeAddress()" style="height:24px;">查询城市</button>
			
		</div>
		<div style="width:100%;height:380px;" id="mapContainer"></div>
		<div style="width:303px;" id="latLng"></div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
        <!-- <button type="button" class="btn btn-primary">确定</button> -->
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
</div><!-- form -->

