<?php
/* @var $this CommentController */
/* @var $dataProvider CActiveDataProvider */

 $this->layout="main-tang";

$attribute = $restaurant->attributeLabels();

//动态给layout添加css文件
$cs=Yii::app()->clientScript;
$cs->registerCssFile(Yii::app()->request->baseUrl.'/css/_comment_detail.css');


?>

<div class="comment_detail">
<ul>
<li><span>店名</span><span><?php echo $restaurant->name; ?></span></li>
<li><span>地址</span><span><?php echo $restaurant->address; ?></span></li>
<li><span>电话</span><span><?php echo $restaurant->phone; ?></span></li>
<li><span>区域</span><span><?php echo $restaurant->county->name.' '.$restaurant->area->name; ?></span></li>
<li><span>营业时间</span><span><?php echo $restaurant->business_hour; ?></span></li>
<li>
<!--详细页评分组件-->
<div class="rating-widget">
		<span class="rating-widget-lable">评分:</span><!--<span class="rating-imdb " style="width: 0px; display:block;"></span>-->
		<div class="rating-list m" data-rating-default="<?php echo sprintf("%.1f",CHtml::encode($restaurant->average_points)); ?>" 
			data-clicknum="0" 
			data-user="<?php echo Yii::app()->user->id ?>"
			data-id="<?php echo CHtml::encode($restaurant->id);?>"
			>
		<span class="rating-stars">
		<a class="rating-icon star-on"><span>1</span></a>
		<a class="rating-icon star-on"><span>2</span></a>
		<a class="rating-icon star-on"><span>3</span></a>
		<a class="rating-icon star-on"><span>4</span></a>
		<a class="rating-icon star-on"><span>5</span></a>
		<a class="rating-icon star-on"><span>6</span></a>
		<a class="rating-icon star-on"><span>7</span></a>
		<a class="rating-icon star-on"><span>8</span></a>
		<a class="rating-icon star-on"><span>9</span></a>
		<a class="rating-icon star-on"><span>10</span></a>
		</span>
		<span class="rating-rating">
		<span class="fonttext-shadow-2-3-5-000 value"><?php echo sprintf("%.1f",CHtml::encode($restaurant->average_points)); ?></span>
		<span class="grey">/</span>
		<span class="grey">10</span>
		</span>
		<span class="rating-cancel ">
			<a title="删除">
				<span>X</span>
			</a>
		</span>
		</div>
		</div><!--<span><?php echo $restaurant->average_points; ?></span>-->
			<div class="clear"><!--清除浮动--></div>


</li></ul>

<div id="map_container"></div><div class="clear"></div>
</div>
<?php

/*$this->widget('zii.widgets.CDetailView', array(
		'data'=>$restaurant,
		//'cssFile' => Yii::app()->request->baseUrl. '/css/_comment_detail.css',
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
*/



$this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
	'enablePagination'=>false,	
)); ?>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>


<script type="text/javascript">
$(function(){

/*
 *评分组件
 *@当鼠标移到星星上（A标签），就给小于等于当前鼠标位置的元素加上选中的样式，
 *大于当前位置的元素为原始样式，同时给class=value的span(评分值)赋值
 *@当鼠标移出rating-list（星星的父容器）时，判断是否评分成功，给给定数量的星星加上评分的样式，
 *如果未评分就还原默认的数字
 */
var rating_list_dome=$(".rating-widget .rating-list");
rating_list_dome.each(function(){

	var a_this=$(this);//当前遍历rating-list的jqueryDOM对象
	var a_arr=$(".rating-stars a",a_this);//取出当前rating-list下的所有a对象
	var raing_value=$(".rating-rating>.value",a_this);//评分的值
	var raing_default=a_this.attr("data-rating-default");//评分的默认值
		//raing_default=parseFloat(raing_default)==0? '-':raing_default;
	
	ratingInit(a_this,"rating-icon rating-init",Math.round(parseFloat(raing_default)),raing_value);



		//单击星星时发生
		a_arr.live("click",function(event){

			if (a_this.attr("isclick")=="true") {
				return false;
			}
			var i=parseInt($("span",$(this)).text());
		var selected_a=$(".rating-stars a:lt("+i+")",a_this);
			var no_selected_a=$(".rating-stars a:gt("+(i-1)+")",a_this);
			//event.preventDefault()
			//event.stopPropagation();
			console.log("tagname="+$(this)[0].tagName+" user_id="+a_this.attr("data-user")+"  data-id="+a_this.attr("data-id")+"  value="+raing_value.text());

			if (a_this.attr('data-user')=="") {
				//点击登陆弹出模态窗口
				loginModal();

				return false;
			}
		a_this.attr("data-clicknum",parseInt($("span",$(this)).text()));
		selected_a.removeClass();
		selected_a.addClass("rating-icon rating-off");
		var rating_cancel=$(".rating-cancel",a_this);
		rating_cancel.addClass('rating-pending');
		//执行评分的ajax
		//console.log("user_id="+a_this.attr("data-user")+"  data-id="+a_this.attr("data-id")+"  value="+raing_value.text());
		$.post("/index.php?r=vote/create",{Vote:{user_id:a_this.attr("data-user"),restaurant_id:a_this.attr("data-id"),
			rating:raing_value.text()}},function(resultdata){
				//console.log("aa="+resultdata.voteid);
				if (resultdata.msg==="0") {
					a_this.attr('voteid',resultdata.voteid);//将voteid邦定到dom对象上
					rating_cancel.removeClass('rating-pending').addClass("rating-icon rating-your");
					rating_cancel.one('click',function(){
						rating_cancel.removeClass('rating-icon rating-your').addClass("rating-pending");
						$.post("/index.php?r=vote/delete",{Vote:{id:a_this.attr("voteid")}},function(rating_cancel_result){								
								if (rating_cancel_result.msg==="0") {
									a_this.removeAttr('voteid');
									rating_cancel.removeClass('rating-pending');
									a_this.attr("data-clicknum","0");
									raing_value.text(raing_default);
									//console.log(rating_cancel_result+"abc");
									ratingInit(a_this,"rating-icon rating-init",Math.round(parseFloat(raing_default)),raing_value);
								}else{
									//服务器出错
								}
							},"json");
					});
				}else{
					//服务器出错
				}
		},"json");
	
		a_this.attr("isclick","true");
		});

	a_arr.hover(function(){


		//当鼠标移到a标签上时的事件
		var i=parseInt($("span",$(this)).text());
		var selected_a=$(".rating-stars a:lt("+i+")",a_this);
		selected_a.removeClass();
		selected_a.addClass("rating-icon rating-hover");

		
		var no_selected_a=$(".rating-stars a:gt("+(i-1)+")",a_this);
		no_selected_a.removeClass();
		no_selected_a.addClass("rating-icon star-on");

		raing_value.text(i);

		
		
	
	},function(){

		a_this.attr("isclick","flase");
	});

	

	//当鼠标移出rating-list的矩形时根据状态还原星星的样式
		$(".rating-stars",a_this).bind("mouseout",function(){	
			var clicknum=a_this.attr("data-clicknum");
		if (clicknum=="0" && parseInt(raing_default)==0) {
		a_arr.removeClass();
		a_arr.addClass("rating-icon star-on");
		raing_value.text(parseInt(raing_default)==0?'-':raing_default);		
		}else if(clicknum=="0" && parseInt(raing_default)>0)
		{
			ratingInit(a_this,"rating-icon rating-init",Math.round(raing_default),raing_value);
			raing_value.text(raing_default);
		}
		else{
			ratingInit(a_this,"rating-icon rating-off",parseInt(clicknum),raing_value);
			raing_value.text(clicknum);
		}
		});


});

function ratingInit(e_this,classname,i,evalue)
{	

	if (i==0) {
		evalue.text("-");
	}

	var selected_a=$(".rating-stars a:lt("+i+")",e_this);
	selected_a.removeClass();
	selected_a.addClass(classname);
	var no_selected_a=$(".rating-stars a:gt("+(i-1)+")",e_this);
		no_selected_a.removeClass();
		no_selected_a.addClass("rating-icon star-on");

}

	//清除评分
	function ratingCancelClick(event)
	{
		console.log("a="+event.data.rating);
	}

	//异步加载SOSO地图JS库
	<?php if ($restaurant->latitude!=0 && $restaurant->longitude!=0) {
		echo 'loadScript();';
	}; ?>


});

/*
 *加载地图定位
 */
var geocoder,map, marker = null;
var init = function() {
    var center = new soso.maps.LatLng(<?php echo CHtml::encode($restaurant->latitude).','.CHtml::encode($restaurant->longitude); ?>);//(39.916527,116.397128);
    map = new soso.maps.Map(document.getElementById('map_container'),{
        center: center,
        zoom: 16
    });
    var info = new soso.maps.InfoWindow({map: map});
    geocoder = new soso.maps.Geocoder({
        complete : function(result){
            map.setCenter(result.detail.location);
            var marker = new soso.maps.Marker({
                map:map,
                position: result.detail.location
            });
            soso.maps.event.addListener(marker, 'click', function() {
                info.open();
                info.setContent('<div style="width:150px;height:80px;">'+
                    result.detail.address+'</div>');
                info.setPosition(result.detail.location);
            });
        }
    });

geocoder.getAddress(center);
    
}


function loadScript() {
  var script = document.createElement("script");
  script.type = "text/javascript";
  script.src = "http://map.soso.com/api/v2/main.js?callback=init";
  document.body.appendChild(script);
}
//window.onload = loadScript;
</script>

