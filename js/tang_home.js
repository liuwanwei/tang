/*
*首页里的JS代码,整理到一个文件里（碎片整理）
*2014-01-22
*/
var tangHome={};
	tangHome.count=0;
	tangHome.area=-1;
	tangHome.type=-1;
	tangHome.county=-1;
	tangHome.pageCurrent=1;
	tangHome.limit=10;
	tangHome.itemIndex=10;
	tangHome.voteCreateUrl="";
	tangHome.voteDeleteUrl="";
	tangHome.restaurantFeatureQueryUrl="";
	tangHome.featureAddRestaurantFeatureUrl="";
	tangHome.restaurantIndexByPageUrl="";
	tangHome.commentIndexUrl="";
	tangHome.userId=-1;
	tangHome.isdataload=true;
	tangHome.isAdmin=false;

	tangHome.initScroll=function(){
		var tang_this=this;
		if (tang_this.count>tang_this.limit) {
			$(window).scrollTop(0);
			$(window).scroll(function(event){
				event.preventDefault();
				if (tang_this.itemIndex>=tang_this.count) {
					return false;
				}
				if (tang_this.isdataload && $(window).scrollTop()+10 >= $(document).height() - $(window).height()){
					if (tang_this.isdataload) {
						tang_this.isdataload=false;
						tang_this.nextPage();
					}
				}
			});
		}
	};
	
	tangHome.nextPage=function(){
		var tang_this=this;
		if (tang_this.count>tang_this.limit) 
		{
			$(".list-footer-load>span").show();
			dataLoadPrompt(tang_this.count,tang_this.itemIndex,tang_this.limit);
			$.get(tang_this.restaurantIndexByPageUrl,{county:tang_this.county,area:tang_this.area,type:tang_this.type,page:tang_this.pageCurrent,limit:tang_this.limit},function(data){
				if (data.length<tang_this.limit){
				    if(data!=null){
				    	setTimeout(function(){
				    		tang_this.loadData(data);
				    		//dataLoadPrompt(data.length);
				    		$(".list-footer-load>span").hide();
				    		tang_this.isdataload=false;
				    		tang_this.pageCurrent++;
				    	},1000);
				    }
				}else{
				    if(data!=null){
				        setTimeout(function(){
				    		tang_this.loadData(data);
				    		//dataLoadPrompt(data.length);
				    		$(".list-footer-load>span").hide();
					        tang_this.isdataload=true;
					        tang_this.pageCurrent++;
				    	},1000);
				    }
				}
			},"json");
		}
	};

//加载分页时，动态DOM
tangHome.loadData=function(data)
{
	var strData='';
	for(var i in data){
		this.itemIndex++;
		var item=data[i];
		//console.log("a="+item["name"]);
		strData+=	'<div class="view-item">';
		if (item["restaurant"]["image_url"]) {
			strData+='<a href="'+item["restaurant"]["image_url"]+'" class="restaurant_img" ><img src="'+item["restaurant"]["image_url"]+'"></a>';
		}else{
			strData+='<span class="restaurant_defalut_img"><i class="fa fa-smile-o"></i></span>';
		}
		strData+='<div class="restaurant-detail">'+
		'<ul>'+
		'<li>'+
		'<strong>'+
		'<a href="'+this.commentIndexUrl+item["restaurant"]["id"]+'" target="_blank">'+item["restaurant"]["name"]+'</a>'+
		'</strong>'+
		'</li>'+
		'<li>'+
		'<span class="title">地址:</span>'+
		'<span class="detail-value">'+item["restaurant"]["address"]+'</span> </li>';
		//console.log("features="+(item["features"]));
		if(item["features"]){
			strData+='<li><span class="title">特色:</span>';
			for(var b in item["features"]){
				strData+='<span class="feature">'+item["features"][b]["name"]+'</span>';
			}
			strData+='</li>';
		}
		strData+='<li>'+
		'<div class="rating-widget">'+
		'<span class="rating-widget-lable">平均得分:</span>'+
		'<div class="rating-list m" isclick="false" data-rating-default="'+(new Number(item["restaurant"]["average_points"])).toFixed(1)+'" '+
		'data-clicknum="0" '+
		'data-user="'+this.userId+'"'+
		'data-id="'+item["restaurant"]["id"]+'" '+
		'data-name="'+item["restaurant"]["name"]+'">'+
		'<span class="rating-stars">'+
		'<a class="rating-icon star-on" data-title="不推荐"><span>1</span></a>'+
		'<a class="rating-icon star-on" data-title="聊胜于无"><span>2</span></a>'+
		'<a class="rating-icon star-on" data-title="日常饮食"><span>3</span></a>'+
		'<a class="rating-icon star-on" data-title="值得品尝"><span>4</span></a>'+
		'<a class="rating-icon star-on" data-title="汤中一绝"><span>5</span></a>'+
		'</span>'+
		'<span class="rating-rating">'+
		'<span class="fonttext-shadow-2-3-5-000 value">'+(new Number(item["restaurant"]["average_points"])).toFixed(1)+'</span>'+
		'<span class="grey">/</span>'+
		'<span class="grey">5</span>'+
		'</span>'+
		'<span class="rating-cancel ">'+
		'<a title="删除">'+
		'<span>X</span>'+
		'</a>'+
		'</span>'+
		'</div>';

		if (item["restaurant"]["votes"]>0){
			strData+='<div class="rating-count-p">'+
			'<span>'+item["restaurant"]["votes"]+'</span>个评分'+
			'</div>';
		}
		strData+='</div>'+
		'<div class="fenxiang"><a class="sina" href="http://v.t.sina.com.cn/share/share.php?url=http://www.laotangguan.com/details/'+item["restaurant"]["id"]+'&title=我发现了一个非常不错的汤馆：“'+item["restaurant"]["name"]+'”。(@洛阳老汤馆) &appkey=3495571392&pic='+(item["restaurant"]["image_url"]==""?"":"http://www.laotangguan.com"+item["restaurant"]["image_url"])+'&ralateUid=&searchPic=false"  target="_blank"><i class="fa fa-share"></i> 分享</a></div>'+
		'<div class="clear"><!--清除浮动--></div>'+
		'</li>'+
		'<div class="clear"></div>'+
		'</ul>'+
		'</div>';

		if (this.isAdmin) {
			strData+='<!--编辑功能-->';
			strData+='<div class="view-edit-btn" >'+
			'<div class="view-edit-header"><a title="'+item["restaurant"]["name"]+'" class="fa fa-pencil"></a>'+
			'<ul>'+
			'<li class="feature-btn">贴标</li>'+
			'<li class="itemEdit-btn" data-item-url="/restaurant/update/id/'+item["restaurant"]["id"]+'"><a href="/restaurant/update/id/'+item["restaurant"]["id"]+'" target="_blank">修改</a></li>'+
			'</ul>'+
			'</div>'+
			'<div class="feature-content" data-item-id="'+item["restaurant"]["id"]+'" data-selected-items="';
			for(var a in item["features"]){
				strData+=item["features"][a]["id"]+',';
			}
			strData+='">'+
			'<div class="feature-content-content"></div>'+
			'<div class="feature-content-footer"><button id="featureEditSubmit">提交</button><button id="feature-edit-close">关闭</button></div>'+
			'</div>'+
			'</div>';
			}
			strData+='<div style="clear:both;"></div>'+
			'</div>';
		}

	//console.log(strData);
	$(".list-view .items").append(strData);
	//var rating_list_dome1=$(strData).find(".rating-widget .rating-list");alert(rating_list_dome1.eq(0).html());
	var rating_list_dome1=$(".rating-widget .rating-list",$(".restaurant-left"));
	tang_main_rating(rating_list_dome1,true,this.voteCreateUrl,this.voteDeleteUrl,"rating-icon rating-init");
	loadFancyBox();//图片放大
	if (this.isAdmin) { //判断是否是管理员，给管理增加贴标功能
		editbutton(this.restaurantFeatureQueryUrl,this.featureAddRestaurantFeatureUrl);
	}
};
tangHome.initRating=function(){
	var rating_list_dome=$(".rating-widget .rating-list",$(".restaurant-left"));
	tang_main_rating(rating_list_dome,true,this.voteCreateUrl,this.voteDeleteUrl,"rating-icon rating-init");
	loadFancyBox();//图片放大

	if (this.isAdmin) { //判断是否是管理员，给管理增加贴标功能
		editbutton(this.restaurantFeatureQueryUrl,this.featureAddRestaurantFeatureUrl);
	}
};
//加载数据提示
function dataLoadPrompt(dataCount,itemIndex,limit)
{
	var loadCount=0;
	if ((dataCount-itemIndex)>limit) {
		loadCount=limit;
	}else{
		loadCount=dataCount-itemIndex;
	}
	$(".list-footer-load>span>span").text(loadCount);
}
//图片放大
function loadFancyBox()
{
	$(".restaurant_img").fancybox();
}