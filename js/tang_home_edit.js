/*当用户角色是管理员，就显示编辑功能*/
function editbutton(restaurantFeatureQuery,featureAddRestaurantFeature){
	var btnedit_div=$(".view-edit-btn");
	$(".view-edit-header",btnedit_div).hover(function(){
		var d_this=$(this),p_this=d_this.parent();
		d_this.find("ul").show();
		d_this.find(".feature-btn").bind("click",function(){
		var feature_selected_items=$(".feature-content",p_this).attr('data-selected-items').split(',');
			//ajax加载数据
			$.get(restaurantFeatureQuery,{},function(data){

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
		hide_edit_btn_div($(this));
		
	});

	$("#feature-edit-submit",btnedit_div).click(function(){
		var d_this=$(this);
		var parent_edit_dom=$(this).parent().parent();
		
		var features_items_str="";
		$("input:checked",parent_edit_dom.find(".feature-content-content")).each(function(){
			features_items_str+=$(this).val()+",";		
		});
		features_items_str=features_items_str.substring(0,features_items_str.length-1);
		//console.log("parent_content="+parent_edit_dom.attr("data-item-id"));
		$.post(featureAddRestaurantFeature,{Feature:{restaurant_id:parent_edit_dom.attr("data-item-id"),features:features_items_str}},function(data){
			if (data.success) {
				//当提交成功时关闭窗体
				hide_edit_btn_div(d_this);
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
}

function hide_edit_btn_div(a)
{
	a.parent().parent().hide(100,function(){
		$(this).css({'width':'100px','min-height':'50px','left':$(this).parent().offset().left,'top':$(this).parent().offset().top+25});
		$(".feature-content-content",a.parent().parent()).html('');
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