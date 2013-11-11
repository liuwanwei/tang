<?php
/* @var $this RestaurantController */
/* @var $model Restaurant */

$this->breadcrumbs=array(
	'Restaurants'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'汤馆列表', 'url'=>array('index')),
	array('label'=>'汤馆管理', 'url'=>array('admin')),
);
?>

<h1>Create Restaurant</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model, 'selectors'=>$selectors)); ?>
<div style="text-align:right; width:900px;">
<input id="address" type="textbox" value="" style="width:300px;">
<button onclick="codeAddress()">search</button>
</div>
<div style="width:900px;height:400px;" id="container"></div>
<div style="width:603px;" id="latLng"></div>
<script charset="utf-8" src="http://api.map.soso.com/v1.0/main.js"></script>
<script>
var geocoder,map,marker = null;
var init = function() {
     map = new soso.maps.Map(document.getElementById("container"),{
        center: new soso.maps.LatLng(39.916527,116.397128),
        zoomLevel: 13
    });
    geocoder = new soso.maps.Geocoder();
    soso.maps.Event.addListener(map, 'click', function(event) {
        //alert('您点击的位置为: [' + event.latLng.getLat() + ', ' +
        //event.latLng.getLng() + ']');
    var latLng = event.latLng,
            lat = latLng.getLat().toFixed(5),
            lng = latLng.getLng().toFixed(5);
        document.getElementById("Restaurant_coordinate").value=lat+","+lng;
    	//document.getElementById("Restaurant_latitude").value=event.latLng.getLat();//维度
    	//document.getElementById("Restaurant_longitude").value=event.latLng.getLng();//经度
    });
    soso.maps.Event.addListener(map,'mousemove',function(event) {
        var latLng = event.latLng,
            lat = latLng.getLat().toFixed(5),
            lng = latLng.getLng().toFixed(5);
        document.getElementById("latLng").innerHTML = lat+','+lng;



    });

    codeAddress();
}

function codeAddress() {
    var address = '洛阳市';//document.getElementById("address").value;
    geocoder.geocode({'address': address}, function(results, status) {
        if (status == soso.maps.GeocoderStatus.OK) {
            map.setCenter(results.location);
            if (marker != null) {
                marker.setMap(null);
            }
            marker = new soso.maps.Marker({
                map: map,
                position:results.location
            });
        } else {
            alert("检索没有结果，原因: " + status);
        }
    });
}

window.onload=init;
</script>