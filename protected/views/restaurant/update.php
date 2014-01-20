<?php
/* @var $this RestaurantController */
/* @var $model Restaurant */


?>
<div class="clear"></div>
<h4 style="text-align:center;"><?php echo $model->name; ?></h4>

<?php echo $this->renderPartial('_form', array('model'=>$model, 'selectors'=>$selectors,'returnUrl'=>$returnUrl)); ?>
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
    
    <?php if($model->coordinate!=0){ 
        echo 'codeLatLng("'.$model->coordinate.'");';
    }else{
        echo 'geoCoder.getLocation("洛阳");';
    }?>
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

window.onload=init;
</script>