<?php
/* @var $this RestaurantController */
/* @var $dataProvider CActiveDataProvider */
?>


<div class="tooltip">
	<div class="bottomtitle"></div>
	<div class="content">

	</div>
</div>


<div class="restaurant-content">
<?php if (! empty($areaMenu) &&  count($areaMenu) >= 1) { ?>
<div class="county-menu-title"><span>区域</span>
<div id="area-menu">
	<?php $this->widget('zii.widgets.CMenu',array('items'=>$areaMenu)); ?>
</div><!-- area-menu -->
</div>
<?php } ?>


<?php if (! empty($typeMenu)) { ?>
<div class="county-menu-title"><span>分类</span>
<div id="area-menu">
	<?php $this->widget('zii.widgets.CMenu',array('items'=>$typeMenu)); ?>
</div><!-- type-menu -->
</div>
<?php } ?>

<div>
	<div class="restaurant-left">



<?php

 $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
	'cssFile' => Yii::app()->request->baseUrl. '/css/_restaurant_item.css',
	'template' => "{pager}\n{summary}\n{items}\n{pager}",
	'ajaxUpdate'=> false,
	'pager'=>array('header'=>'',
			'prevPageLabel'=>'«',
			'nextPageLabel'=>'»',
			'firstPageLabel'=>'首页',
			'lastPageLabel'=>'末页',
			'cssFile'=>Yii::app()->request->baseUrl.'/css/pager.css'),

));
 ?>

 <button id="next">next</button>
</div>
<div class="right-content">
<?php
	print_r($lastVotes[0]->user->nick_name);print_r($lastVotes[0]->restaurant->name);
	print_r($lastComments[0]->user->nick_name);print_r($lastComments[0]->content);
?> 
</div>
<div class="clear"></div>
</div>
</div>
<script type="text/javascript">

$(function(){


	/*$("#next").click(function(){
	$.get("restaurant/index/Restaurant_page/2",{},function(data){
		console.log("data="+data);
		document.body.innerHTML=data;
	});
});*/

	tang_main_rating();

function tang_main_rating()
{


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
		$.post("/index.php?r=vote/create",{Vote:{user_id:a_this.attr("data-user"),restaurant_id:a_this.attr("data-id"),
			rating:raing_value.text()}},function(resultdata){
				//console.log("aa="+resultdata.voteid);
				if (resultdata.msg==="0") {
					a_this.attr('voteid',resultdata.voteid);//将voteid邦定到dom对象上
					rating_cancel.removeClass('rating-pending').addClass("rating-icon rating-your");
					var tooltip=$(".tooltip");
					rating_cancel.hover(function(){
						var a_offset=$(this).offset();						
						$("div:eq(0)",tooltip).removeClass().addClass("lefttitle");
						tooltip.find('.content').text("你要删除打分吗？");
						tooltip.css({'top':a_offset.top-$(this).height()/2,'left':a_offset.left+$(this).width()+10}).show();
					},function(){
						tooltip.hide();
					});			
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
		var a_offset=$(this).offset();
		var tooltip=$(".tooltip");
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
		$(".tooltip").hide();
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

<?php if (User::model()->isAdmin()) {
?>
/*当用户角色是管理员，就显示编辑功能*/

var btnedit_div=$(".view-edit-btn");

	$(".view-edit-header",btnedit_div).hover(function(){
		var d_this=$(this),p_this=d_this.parent();
		d_this.find("ul").show();
		d_this.find(".feature-btn").bind("click",function(){
			var feature_selected_items=$(".feature-content",p_this).attr('data-selected-items').split(',');
			//ajax加载数据
			$.get("index.php?r=restaurantFeature/query",{},function(data){
				
				var t="<ul>";
				if (data) {

					$.each(data,function(a){
						
						if (isContain(feature_selected_items,data[a].id)) {
							t+='<li><label><input type="checkbox" value='+data[a].id+' checked="true" />'+data[a].name+'</label> </li>';
						}
						else{
							t+='<li><label><input type="checkbox" value='+data[a].id+' />'+data[a].name+'</label> </li>';
						}
					});
				}
				t+="</ul>";

				$(".feature-content .feature-content-content",p_this).html(t);

			},"json");


			$(".feature-content",p_this).css({'display':'block',
				'top':p_this.offset().top+25,
				'left':p_this.offset().left}).animate(
				{	
					width:'200px',
					minHeight:'200px',
					left:$(this).offset().left-$(this).width()-200,
					top:$(this).offset().top-25
					
				},200);
		});
	},function(){
	$(this).find("ul").hide();
	$(this).find(".feature-btn").unbind("click");
	});

$("#feature-edit-close",btnedit_div).click(function(){
	btnedit_div_hide($(this));
	
});

$("#feature-edit-submit",btnedit_div).click(function(){
	var d_this=$(this);
	var parent_edit_dom=$(this).parent().parent();
	
	var features1="";
	var feature_arr=[];	
	$("input:checked",parent_edit_dom.find(".feature-content-content")).each(function(){
		features1+=$(this).val()+",";		
		//feature_arr.push({id:$(this).val(),name:$(this).parent().text()});
	});
	features1=features1.substring(0,features1.length-1);
	//console.log("parent_content="+parent_edit_dom.attr("data-item-id"));
	$.post("/index.php?r=feature/addrestaurantfeature",{Feature:{restaurant_id:parent_edit_dom.attr("data-item-id"),features:features1}},function(data){
		if (data.success) {
			//当提交成功时关闭窗体
			btnedit_div_hide(d_this);
			//刷新页面
			location="/index.php"+window.location.search;
			//当提交成功时动态更新特色数据


			//console.log("className="+$(".restaurant-detail>ul>li>ul:eq(0) li",parent_edit_dom.parent().parent()).eq(0).text());

			//$("<li>adfsadf</li>").appendTo($(".restaurant-detail>ul>li>ul:eq(0) li:eq(1)",parent_edit_dom.parent().parent()));
		}else
		{
			//提示错误信息
		}
	},"json");
});


function btnedit_div_hide(a)
{
a.parent().parent().hide(100,function(){
		$(this).css({'width':'100px','min-height':'50px','left':$(this).parent().offset().left,'top':$(this).parent().offset().top+25});
		$(".feature-content-content").html('');
	});
}

//数组中是否包含一个元素
function isContain(a,b)
{
	for(var i in a)
	{
		if (a[i]==b) {
			return true;
		}
	}
	return false;
}

<?php } ?>
});

</script>
