
function commentRatingInit(rating_list_dome,rating_item,voteQueryUrl,voteCreateUrl,voteDeleteUrl,isAdmin)
{
	if (isAdmin) {
		commentRatingInitByLogin(rating_list_dome,rating_item,voteQueryUrl,voteCreateUrl,voteDeleteUrl);
	}else{
		tang_main_rating(rating_list_dome,true,voteCreateUrl,voteDeleteUrl,"rating-icon rating-off");
	}
}

function commentRatingInitByLogin(rating_list_dome,rating_item,voteQueryUrl,voteCreateUrl,voteDeleteUrl)
{
	$.get(voteQueryUrl,{restaurantId:rating_item.attr("data-id"),userId:rating_item.attr("data-user")},function(data){
		if (data.msg) {
			rating_item.attr("data-rating-default",0);
			$(".rating-rating>.value",rating_item).text("-");
		}else{
			rating_item.attr("data-rating-default",data.rating);
			$(".rating-rating>.value",rating_item).text(data.rating);
			rating_item.attr('voteid',data.id);//将voteid邦定到dom对象上
			$(".rating-cancel",rating_item).removeClass('rating-pending').addClass("rating-icon rating-your");
			$(".rating-cancel",rating_item).one('click',function(){
				$(".rating-cancel",rating_item).removeClass('rating-icon rating-your').addClass("rating-pending");
				$.post(voteDeleteUrl,{Vote:{id:rating_item.attr("voteid")}},function(rating_cancel_result){								
					if (rating_cancel_result.msg==="0") {
						rating_item.removeAttr('voteid');
						$(".rating-cancel",rating_item).removeClass('rating-pending');
						rating_item.attr("data-clicknum","0");
						$(".rating-rating>.value",rating_item).text(rating_item.attr("data-rating-default"));
									//console.log(rating_cancel_result+"abc");
									ratingInit(rating_item,"rating-icon star-on",1,$(".rating-rating>.value",rating_item));
								}else{
									//服务器出错
								}
							},"json");
			});
		}

		if (data.msg) {
			rating_item.attr("data-rating-default",0);
			$(".rating-rating>.value",rating_item).text("-");
		}else{
			rating_item.attr("data-rating-default",data.rating);
			$(".rating-rating>.value",rating_item).text(data.rating);
			rating_item.attr('voteid',data.id);//将voteid邦定到dom对象上
			$(".rating-cancel",rating_item).removeClass('rating-pending').addClass("rating-icon rating-your");
			$(".rating-cancel",rating_item).one('click',function(){
				$(".rating-cancel",rating_item).removeClass('rating-icon rating-your').addClass("rating-pending");
				$.post(voteDeleteUrl,{Vote:{id:rating_item.attr("voteid")}},function(rating_cancel_result){								
					if (rating_cancel_result.msg==="0") {
						rating_item.removeAttr('voteid');
						$(".rating-cancel",rating_item).removeClass('rating-pending');
						rating_item.attr("data-clicknum","0");
						$(".rating-rating>.value",rating_item).text(rating_item.attr("data-rating-default"));
						//console.log(rating_cancel_result+"abc");
						ratingInit(rating_item,"rating-icon star-on",1,$(".rating-rating>.value",rating_item));
					}else{
						//服务器出错
					}
				},"json");
			});
		}

		tang_main_rating(rating_list_dome,true,voteCreateUrl,voteDeleteUrl,"rating-icon rating-off");
			//rating_list_dome.eq(0).attr("data-rating-default");
	},"json");
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