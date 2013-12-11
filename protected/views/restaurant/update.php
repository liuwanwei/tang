<?php
/* @var $this RestaurantController */
/* @var $model Restaurant */

// $this->breadcrumbs=array(
// 	'Restaurants'=>array('index'),
// 	$model->name=>array('view','id'=>$model->id),
// 	'Update',
// );

// $this->menu=array(
// 	array('label'=>'List Restaurant', 'url'=>array('index')),
// 	array('label'=>'Create Restaurant', 'url'=>array('create')),
// 	array('label'=>'View Restaurant', 'url'=>array('view', 'id'=>$model->id)),
// 	array('label'=>'Manage Restaurant', 'url'=>array('admin')),
// );
?>

<h4><?php echo $model->name; ?></h4>

<?php echo $this->renderPartial('_form', array('model'=>$model, 'selectors'=>$selectors)); ?>
<script charset="utf-8" src="http://map.qq.com/api/js?v=2.exp"></script>
<script>
var geoCoder,cityLocation,map,marker = null;
var init = function() {
    var center = new qq.maps.LatLng(39.916527,116.397128);
    map = new qq.maps.Map(document.getElementById('mapContainer'),{
        center: center,
        zoom: 16
    });
    
    qq.maps.event.addListener(map,'mousemove',function(event) {
        var latLng = event.latLng;
        var lat = latLng.getLat().toFixed(5);
        var lng = latLng.getLng().toFixed(5);
        document.getElementById("latLng").innerHTML = lat+','+lng;
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

    qq.maps.event.addListener(map,'click',function(event) {
        var latLng = event.latLng;
        var lat = latLng.getLat().toFixed(5);
        var lng = latLng.getLng().toFixed(5);
        document.getElementById("Restaurant_coordinate").value=lat+","+lng;
        $('#mapModal').modal('hide');
    });

    cityLocation = new qq.maps.CityService({
        complete : function(result){
            map.setCenter(result.detail.latLng);
        }
    });

    cityLocation.searchLocalCity();

    <?php if($model->coordinate!=0){ 
        echo 'codeLatLng("'.$model->coordinate.'");';
    }?>
}

function codeAddress() {
    var address = document.getElementById("mapAddress").value;
    geoCoder.getLocation(address);
}

function codeLatLng(coordinate) {
    var input = coordinate;
    var latlngStr = input.split(",",2);
    var lat = parseFloat(latlngStr[0]);
    var lng = parseFloat(latlngStr[1]);
    var latLng = new qq.maps.LatLng(lat, lng);
    var info = new qq.maps.InfoWindow({map: map});
    geoCoder.getAddress(latLng);
}

window.onload=init;
</script>