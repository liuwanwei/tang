<?php
/* @var $this CommentController */
/* @var $dataProvider CActiveDataProvider */

$this->layout="main-tang";

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
		<li><span class="title">电话:</span><span><?php echo $restaurant->phone; ?></span></li>
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
					<span class="rating-widget-lable"><?php echo !yii::app()->user->isGuest? '我的评分' :'我要打分'?>:</span><!--<span class="rating-imdb " style="width: 0px; display:block;"></span>-->
					<div class="rating-list m" data-rating-default="0" 
					data-clicknum="0" 
					data-user="<?php echo Yii::app()->user->id ?>"
					data-id="<?php echo CHtml::encode($restaurant->id);?>"
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
			<div class="clear"><!--清除浮动--></div>
		</li>
	</ul>

	<?php if (empty($restaurant->coordinate)) { ?>
	<div id="main_small_map  hide-visibility"><div id="map_container"></div><div class="main-small-map-footer  hide-visibility">点击放大地图</div></div><div class="clear"></div>
	<?php }else{ ?>
	<div id="main_small_map"><div id="map_container"></div><div class="main-small-map-footer">点击放大地图</div></div><div class="clear"></div>	
	<?php } ?>
</div>

<div class="user-comment-list">
	<div class="comment-list-title">汤馆评论</div>
	<?php
	$this->widget('zii.widgets.CListView', array(
		'dataProvider'=>$dataProvider,
		'itemView'=>'_view',
		'cssFile' => '',
		'enablePagination'=>true,
		'emptyText'=>'还没有人评论，客官您先来吐个槽吧.',
		'summaryText' => '共收到 {count} 条评论',
		)); 
		?>

		<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
</div>
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
 var rating_item1=rating_list_dome.eq(0); 
 <?php if (!yii::app()->user->isGuest) {?>

 	$.get("<?php echo $this->createUrl('vote/query'); ?>",{restaurantId:rating_item1.attr("data-id"),userId:rating_item1.attr("data-user")},function(data){
 		if (data.msg) {
 			rating_item1.attr("data-rating-default",0);
 			$(".rating-rating>.value",rating_item1).text("-");
 		}else{
 			rating_item1.attr("data-rating-default",data.rating);
 			$(".rating-rating>.value",rating_item1).text(data.rating);
			rating_item1.attr('voteid',data.id);//将voteid邦定到dom对象上
			$(".rating-cancel",rating_item1).removeClass('rating-pending').addClass("rating-icon rating-your");
			$(".rating-cancel",rating_item1).one('click',function(){
				$(".rating-cancel",rating_item1).removeClass('rating-icon rating-your').addClass("rating-pending");
				$.post("/vote/delete",{Vote:{id:rating_item1.attr("voteid")}},function(rating_cancel_result){								
					if (rating_cancel_result.msg==="0") {
						rating_item1.removeAttr('voteid');
						$(".rating-cancel",rating_item1).removeClass('rating-pending');
						rating_item1.attr("data-clicknum","0");
						$(".rating-rating>.value",rating_item1).text(rating_item1.attr("data-rating-default"));
									//console.log(rating_cancel_result+"abc");
									ratingInit(rating_item1,"rating-icon star-on",1,$(".rating-rating>.value",rating_item1));
								}else{
									//服务器出错
								}
							},"json");
			});
		}

		if (data.msg) {
			rating_item1.attr("data-rating-default",0);
			$(".rating-rating>.value",rating_item1).text("-");
		}else{
			rating_item1.attr("data-rating-default",data.rating);
			$(".rating-rating>.value",rating_item1).text(data.rating);
	rating_item1.attr('voteid',data.id);//将voteid邦定到dom对象上
	$(".rating-cancel",rating_item1).removeClass('rating-pending').addClass("rating-icon rating-your");
	$(".rating-cancel",rating_item1).one('click',function(){
		$(".rating-cancel",rating_item1).removeClass('rating-icon rating-your').addClass("rating-pending");
		$.post("<?php echo $this->createUrl('vote/delete'); ?>",{Vote:{id:rating_item1.attr("voteid")}},function(rating_cancel_result){								
			if (rating_cancel_result.msg==="0") {
				rating_item1.removeAttr('voteid');
				$(".rating-cancel",rating_item1).removeClass('rating-pending');
				rating_item1.attr("data-clicknum","0");
				$(".rating-rating>.value",rating_item1).text(rating_item1.attr("data-rating-default"));
	//console.log(rating_cancel_result+"abc");
	ratingInit(rating_item1,"rating-icon star-on",1,$(".rating-rating>.value",rating_item1));
}else{
	//服务器出错
}
},"json");
	});
}

ratingfnc();
	//rating_list_dome.eq(0).attr("data-rating-default");
},"json");

<?php }else{
	echo "ratingfnc();";
} ?>

function ratingfnc(){
	rating_list_dome.each(function(){	
	var a_this=$(this);//当前遍历rating-list的jqueryDOM对象
	var a_arr=$(".rating-stars a",a_this);//取出当前rating-list下的所有a对象
	var raing_value=$(".rating-rating>.value",a_this);//评分的值
	var raing_default=a_this.attr("data-rating-default");//评分的默认值
		//raing_default=parseFloat(raing_default)==0? '-':raing_default;
		
		ratingInit(a_this,"rating-icon rating-off",Math.round(parseFloat(raing_default)),raing_value);


		var tooltip=$(".tang-tooltip");
		$(".rating-cancel",a_this).hover(function(){
			var a_offset=$(this).offset();						
			$("div:eq(0)",tooltip).removeClass().addClass("lefttitle");
			tooltip.find('.content').text("你要删除打分吗？");
			tooltip.css({'top':a_offset.top-$(this).height()/2,'left':a_offset.left+$(this).width()+10}).show();
		},function(){
			tooltip.hide();
		});			

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
		//console.log("tagname="+$(this)[0].tagName+" user_id="+a_this.attr("data-user")+"  data-id="+a_this.attr("data-id")+"  value="+raing_value.text());

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
		$.post("/vote/create",{Vote:{user_id:a_this.attr("data-user"),restaurant_id:a_this.attr("data-id"),
			rating:raing_value.text()}},function(resultdata){
		//console.log("aa="+resultdata.voteid);
		if (resultdata.msg==="0") {
		a_this.attr('voteid',resultdata.voteid);//将voteid邦定到dom对象上
		rating_cancel.removeClass('rating-pending').addClass("rating-icon rating-your");					
		rating_cancel.one('click',function(){
			rating_cancel.removeClass('rating-icon rating-your').addClass("rating-pending");
			$.post("/vote/delete",{Vote:{id:a_this.attr("voteid")}},function(rating_cancel_result){								
				if (rating_cancel_result.msg==="0") {
					a_this.removeAttr('voteid');
					rating_cancel.removeClass('rating-pending');
					a_this.attr("data-clicknum","0");
					raing_value.text("-");
		//console.log(rating_cancel_result+"abc");
		ratingInit(a_this,"rating-icon star-on",1,raing_value);
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
	var a_offset=$(this).offset();
	var tooltip=$(".tang-tooltip");
	$("div:eq(0)",tooltip).removeClass().addClass("bottomtitle");
	tooltip.find('.content').text($(this).attr('data-title'));
	tooltip.css({'top':a_offset.top-30,'left':a_offset.left-$(this).width()/2-20}).show();

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
		$(".tang-tooltip").hide();
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
			ratingInit(a_this,"rating-icon rating-off",Math.round(raing_default),raing_value);
			raing_value.text(raing_default);
		}
		else{
			ratingInit(a_this,"rating-icon rating-off",parseInt(clicknum),raing_value);
			raing_value.text(clicknum);
		}
	});


});
}

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

	


	//textarea鼠标点击去变大

	$("#comment_content").click(function(){
		$(this).animate({height:'150px'},200);
	});


	//地图的点击放大事件
	$(".main-small-map-footer").bind("click",function(){
		$(this).parent().addClass("visibility-hidden");
		var map_clone=$("#big_map_clone");
		var map_container_offset=$("#map_container").offset();
		map_clone.css({'left':$("#tang-content").offset().left+20,'top':$("#tang-content").offset().top+10}).show();
		
		var layer1=$(".layer1");
		layer1.show();
		layer1.click(function(){
			layerhide();
		});

		$(".big-map-header .close").bind("click",function(){
			layerhide();
		});
		init("big_map");
	});
	//关闭model层
	function layerhide()
	{
		$("#main_small_map").removeClass('visibility-hidden').addClass('visibility-visible');
		$(".layer1").hide();
		$("#big_map_clone").hide();

	}


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

