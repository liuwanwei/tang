<?php
/* @var $this RestaurantController */
/* @var $model Restaurant */
/* @var $form CActiveForm */
//http://lib.sinaapp.com/js/jquery/1.10.2/jquery-1.10.2.min.js
//Yii::app()->clientScript->registerScriptFile('http://lib.sinaapp.com/js/jquery/1.10.2/jquery-1.10.2.min.js');
?>
<!-- <script type="text/javascript" src="http://cdn.jsdelivr.net/fancybox/2.1.5/jquery.fancybox.js"></script>
<link rel="stylesheet" type="text/css" href="http://cdn.jsdelivr.net/fancybox/2.1.5/jquery.fancybox.css" media="screen" /> -->
<script type="text/javascript">
	$('.fancybox').fancybox();

	function fileChange(filename)
	{
		if (filename) {
			var file_arr=filename.split('\\');
			$("#clientImgUrl").text(file_arr[file_arr.length-1]);
			$("#clientImg").show(100);
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
		<span class="upload-image"><a class="fancybox" href="<?php echo $model->image_url;?>" data-fancybox-group="gallery" ><img src="<?php echo $model->image_url;?>" class="img-rounded" width="50px" height="50px" alt="" /></a></span>
		<?php } ?>
		<a href="javascript:;" class="a-upload fa fa-plus">
			<?php echo $form->fileField($model,'image_url',array('onchange'=>'fileChange(this.value);'));?>
		</a>
	</div>

</div>

<div class="form-group" id="clientImg" style="display:none;">
<label class="col-sm-2 control-label">图片地址</label>
	<div class="col-sm-8">
		<span id="clientImgUrl"></span>
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
		<?php echo $form->labelEx($model,'type_id',array('class'=>"col-sm-2 control-label"));?>
		<!--<?php echo $form->textField($model,'type_id'); ?> -->
		<div class="col-sm-8">
		<div id="checkbox_type">
			<?php 
			foreach ($selectors['types'] as $key=>$value) {
				if (!empty($model->type_id) && strpos($model->type_id,','.$key.',')!==false) {
					echo '<label><input type="checkbox" name="restaurantType" checked value="'.$key.'" > '.$value.'</label>';	
				}else{
					echo '<label><input type="checkbox" name="restaurantType" value="'.$key.'" > '.$value.'</label>';
			}} ?>
		</div>
		<input type="hidden" id="Restaurant_type_id" name="Restaurant[type_id]">
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
		<a href="javascript:void(0);" title="定位" class="a-text-none fa fa-map-marker" id="mapPosition" style="text-decoration: none;" data-toggle="modal" ></a>
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
	<?php  if(Yii::app()->user->isAdmin) {
		echo '<div class="form-group">';		
		echo $form->labelEx($model, "is_checked",array('class'=>"col-sm-2 control-label"));
		echo '<div class="col-sm-8">';
		echo $form->dropDownList($model, 'is_checked', array('0'=>'未审核', '1'=>'已审核'),array('class'=>"form-control"));
		echo $form->error($model, 'is_checked');
		echo '</div>';
		echo '</div>';
	}
	?>
	<div class="row buttons aligin-c">
		<?php echo CHtml::submitButton($model->isNewRecord ? '新增提交' : '保存提交',array('class'=>'btn btn-primary btn-lg','id'=>'restaurantForm')); ?>
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
<script charset="utf-8" src="http://map.qq.com/api/js?v=2.exp"></script>
<script type="text/javascript">
$(function(){
	$("#restaurantForm").click(function(){
		if ($("#Restaurant_name").val()=="") {
			alert("请输入汤馆名称！");
			$("#Restaurant_name").focus();
			return false;
		}
		var restaurantTypes=$("#checkbox_type input[type='checkbox']:checked");
		var restaurantTypesId="";
		if (restaurantTypes.length>0) {
			restaurantTypes.each(function(){
				restaurantTypesId+=$(this).val()+","
			});
			$("#Restaurant_type_id").val(restaurantTypesId);
		}else{
			alert("您还没有选择分类！");
			return false;
		}
		if ($("#Restaurant_address").val()=="") {
			alert("请输入汤馆地址！");
			$("#Restaurant_name").focus();
			return false;
		}
		return true;
	});

var geoCoder,map,marker = null;
var init = function() {
    var center = new qq.maps.LatLng(39.916527,116.397128);
    map = new qq.maps.Map(document.getElementById('mapContainer'),{
        center: center,
        zoom: 13
    });
    qq.maps.event.addListener(map,'mousemove',function(event) {
        var latLng = event.latLng;
        var lat = latLng.getLat().toFixed(5);
        var lng = latLng.getLng().toFixed(5);
        document.getElementById("latLng").innerHTML = lat+','+lng;
    });
    qq.maps.event.addListener(map,'click',function(event) {
        var latLng = event.latLng;
        var lat = latLng.getLat().toFixed(5);
        var lng = latLng.getLng().toFixed(5);
        document.getElementById("Restaurant_coordinate").value=lat+","+lng;
        $('#mapModal').modal('hide');
    });
    var info = new qq.maps.InfoWindow({map: map});
    geoCoder = new qq.maps.Geocoder({
        complete : function(result){
            map.setCenter(result.detail.location);
            var marker = new qq.maps.Marker({
                map:map,
                position: result.detail.location
            });
            qq.maps.event.addListener(marker, 'click', function() {
                info.open();
                info.setContent('<div style="width:280px;height:100px;">'+
                result.detail.address+'</div>');
                info.setPosition(result.detail.location);
            });
        }
    });
    
    
}

function codeAddress() {
    var address = document.getElementById("mapAddress").value;
    geoCoder.getLocation(address);
}

$('#mapAddress').keyup(function(event){
	//if(event.ctrlKey && event.which == 13)       //13等于回车键(Enter)键值,ctrlKey 等于 Ctrl
	//alert("按了ctrl+回车键!")
	if(event.keyCode==13)
	    codeAddress();
});

function codeLatLng(coordinate) {
    var input = coordinate;
    var latlngStr = input.split(",",2);
    var lat = parseFloat(latlngStr[0]);
    var lng = parseFloat(latlngStr[1]);
    var latLng = new qq.maps.LatLng(lat, lng);
    var info = new qq.maps.InfoWindow({map: map});
    geoCoder.getAddress(latLng);
}

$("#mapPosition").click(function(){
   if($.trim($("#Restaurant_coordinate").val())!="" && $.trim($("#Restaurant_coordinate").val())!=0){
    codeLatLng($.trim($("#Restaurant_coordinate").val()));
   }else{
    geoCoder.getLocation("洛阳");
   }
   $('#mapModal').modal('show');
});
   init();
});

</script>

