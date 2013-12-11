<?php
/* @var $this RestaurantController */
/* @var $model Restaurant */

$this->breadcrumbs=array(
	'Restaurants'=>array('index'),
	'Create',
    );

// $this->menu=array(
// 	array('label'=>'汤馆列表', 'url'=>array('index')),
// 	array('label'=>'汤馆管理', 'url'=>array('admin')),
// );
    ?>

<?php echo $this->renderPartial('_form', array('model'=>$model, 'selectors'=>$selectors)); ?>

<script charset="utf-8" src="http://map.qq.com/api/js?v=2.exp"></script>
<script>
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
    geoCoder = new qq.maps.Geocoder({
        complete : function(result){
            map.setCenter(result.detail.location);
            var marker = new qq.maps.Marker({
                map:map,
                position: result.detail.location
            });
        }
    });
    geoCoder.getLocation("洛阳");
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


window.onload=init;
</script>