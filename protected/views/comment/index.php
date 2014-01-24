<?php
/* @var $this CommentController */
/* @var $dataProvider CActiveDataProvider */

$this->layout="column_main";

$attribute = $restaurant->attributeLabels();

//动态给layout添加css文件
$cs=Yii::app()->clientScript;
$cs->registerCssFile(Yii::app()->request->baseUrl.'/css/_comment_detail.css');


?>
<div class="tang-tooltip">
	<div class="bottomtitle"></div>
	<div class="content">

	</div>
</div>

<div class="layer1"></div>
<div id="big_map_clone">
	<div class="big-map-header">注：地图位置坐标仅供参考，具体情况以实际道路标识信息为准<span class="close" title="关闭">X</span></div>
	<div id="big_map"></div>
</div>

<div class="comment_detail">
	<ul>
		<li><span class="title">店名:</span><span><?php echo $restaurant->name; ?></span></li>
		<li><span class="title">地址:</span><span><?php echo $restaurant->address; ?></span></li>
		<li><span class="title">区域:</span><span><?php echo $restaurant->county->name.' '.$restaurant->area->name; ?></span></li>
		<li><span class="title">营业时间:</span><span><?php echo $restaurant->business_hour; ?></span></li>
		<?php if (!empty($restaurant->features)) {
			?>
			<li><span class="title">特色:</span>
				<?php  foreach ($restaurant->features as $value) {
					echo '<span class="feature">'.CHtml::encode($value->details->name).'</span>';
				} ?></li>
				<?php
			} ?>
			<li >
				<span class="rating-widget-lable title">平均得分:</span>
				<div class="rating-widget-avg">
					<div>
						<?php $ratingSelectedCount=(int)$restaurant->average_points;
						for ($i=0; $i < $ratingSelectedCount; $i++) { 
							echo '<a href="#" class="rating-icon rating-avg-init"></a>';
						}
						for ($j=0; $j < 5-$ratingSelectedCount; $j++) { 
							echo '<a href="#" class="rating-icon star-avg-on"></a>';
						}
						?>

					</div>
					<span class="rating-avg"><?php echo $restaurant->average_points==0?"-":$restaurant->average_points; ?></span>

				</div>

			</li>
			<li>
				<!--详细页评分组件-->
				<div class="rating-widget">
					<span class="rating-widget-lable"><?php echo !yii::app()->user->isGuest? '我的评分' :'我要评分'?>:</span><!--<span class="rating-imdb " style="width: 0px; display:block;"></span>-->
					<div class="rating-list m" data-rating-default="0" 
					data-clicknum="0" 
					data-user="<?php echo Yii::app()->user->id ?>"
					data-id="<?php echo CHtml::encode($restaurant->id);?>" 
					data-name="<?php echo CHtml::encode($restaurant->name);?>" 
					>
					<span class="rating-stars">
						<a class="rating-icon star-on" data-title="不推荐"><span>1</span></a>
						<a class="rating-icon star-on" data-title="聊胜于无"><span>2</span></a>
						<a class="rating-icon star-on" data-title="日常饮食"><span>3</span></a>
						<a class="rating-icon star-on" data-title="值得品尝"><span>4</span></a>
						<a class="rating-icon star-on" data-title="汤中一绝"><span>5</span></a>		
					</span>
					<span class="rating-rating"><!-- echo sprintf("%.1f",CHtml::encode($restaurant->average_points)); -->
						<span class="fonttext-shadow-2-3-5-000 value">-</span>
						<span class="grey">/</span>
						<span class="grey">5</span>
					</span>
					<span class="rating-cancel ">
						<a title="删除">
							<span>X</span>
						</a>
					</span>

				</div>
				
			</div>
			<div class="fenxiang show"><a class="sina" href="http://v.t.sina.com.cn/share/share.php?url=http://www.laotangguan.com<?php echo $this->createUrl('comment/index',array('restaurantId'=>$restaurant->id)); ?>&pic=<?php echo empty($restaurant->image_url)?'':'http://www.laotangguan.com'.$restaurant->image_url;?>&title=我发现了一个非常不错的汤馆：“<?php echo $restaurant->name;?>”。(@洛阳老汤馆) &appkey=3495571392&ralateUid=&searchPic=false" target="_blank"><i class="fa fa-share"></i> 分享</a></div>
			<span class="rating-error-loading"></span>
			<div class="clear"><!--清除浮动--></div>
		</li>
		<li>
			<?php if(!empty($restaurant->image_url)) {?>
			<a href="<?php echo $restaurant->image_url; ?>" class="restaurant_img" style="margin-left:0px;" title="<?php echo $restaurant->name;?>"><img src="<?php echo $restaurant->image_url; ?>"></a>
			<?php }?>
		</li>
	</ul>

	<?php if (empty($restaurant->coordinate)) { ?>
	<div id="main_small_map  hide-visibility"><div id="map_container"></div><div class="main-small-map-footer  hide-visibility">点击放大地图</div></div><div class="clear"></div>
	<?php }else{ ?>
	<div id="main_small_map"><div id="map_container"></div><div class="main-small-map-footer">点击放大地图</div></div><div class="clear"></div>	
	<?php } ?>
</div>

<div class="user-comment-list">
	<!-- <div class="comment-list-title">汤馆评论</div> -->
	<?php
	$this->widget('zii.widgets.CListView', array(
		'dataProvider'=>$dataProvider,
		'itemView'=>'_view',
		'cssFile' => '',
		'enablePagination'=>true,
		'emptyText'=>'还没有人评论，客官您先来吐个槽吧.',
		'summaryCssClass'=>'comment-list-title',
		'summaryText' => '{count} 条评论',
		)); 
		?>

		<?php echo $this->renderPartial('_form', array('restaurantId'=>$restaurant->id,'model'=>$model)); ?>
</div>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl.'/js/tang_rating.js' ?>"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl.'/js/comment_detail.js' ?>"></script>
<script type="text/javascript">
$(function(){
var rating_list_dome=$(".rating-widget .rating-list");
var rating_item=rating_list_dome.eq(0); 
var voteQueryUrl="<?php echo $this->createUrl('vote/query'); ?>";
var voteDeleteUrl="<?php echo $this->createUrl('vote/delete');?>";
var voteCreateUrl="<?php echo $this->createUrl('vote/create'); ?>";
var isAdmin="<?php echo yii::app()->user->isAdmin;?>";
$(".restaurant_img").fancybox();
commentRatingInit(rating_list_dome,rating_item,voteQueryUrl,voteCreateUrl,voteDeleteUrl,isAdmin);
});

//异步加载SOSO地图JS库
<?php if (!empty($restaurant->coordinate)) {
	echo 'loadScript();';
}; ?>
/*
 *加载地图定位
 */
 var geoCoder,map, marker = null;
 var init = function(map_id) {
	var center = new soso.maps.LatLng(<?php echo CHtml::encode($restaurant->coordinate); ?>);//(39.916527,116.397128);
	map = new soso.maps.Map(document.getElementById(map_id),{
		center: center,
		zoom: 16
	});
	var info = new soso.maps.InfoWindow({map: map});
	geoCoder = new soso.maps.Geocoder({
        complete : function(result){
            map.setCenter(result.detail.location);
            var marker = new soso.maps.Marker({
                map:map,
                position: result.detail.location
            });
            soso.maps.event.addListener(marker, 'click', function() {
                info.open();
                info.setContent('<div style="width:150px;height:40px;"><?php echo $restaurant->name;  ?></div>');//'+result.detail.address+'
                info.setPosition(result.detail.location);
            });
        }
    });

	geoCoder.getAddress(center);
}


function loadScript() {
	var script = document.createElement("script");
	script.type = "text/javascript";
	script.src = "http://map.soso.com/api/v2/main.js?callback=init('map_container')";
	document.body.appendChild(script);
}
//window.onload = loadScript;
</script>

