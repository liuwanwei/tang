/*
*首页里的JS代码,整理到一个文件里（碎片整理）
*评分的组件
*2014-01-22
*/
function tang_main_rating(rating_list,ismouseover,voteCreateUrl,voteDeleteUrl)
{
/*
 *评分组件 @rating_list 为评分组件集，@ismouseover是否加载鼠移上去事件
 *@当鼠标移到星星上（A标签），就给小于等于当前鼠标位置的元素加上选中的样式，
 *大于当前位置的元素为原始样式，同时给class=value的span(评分值)赋值
 *@当鼠标移出rating-list（星星的父容器）时，判断是否评分成功，给给定数量的星星加上评分的样式，
 *如果未评分就还原默认的数字
 */

rating_list.each(function(){
	var a_this=$(this);//当前遍历rating-list的jqueryDOM对象
	var a_arr=$(".rating-stars a",a_this);//取出当前rating-list下的所有a对象 
	var raing_value=$(".rating-rating>.value",a_this);//评分的值
	var raing_default=a_this.attr("data-rating-default");//评分的默认值
	//raing_default=parseFloat(raing_default)==0? '-':raing_default;
	ratingInit(a_this,"rating-icon rating-init",Math.round(parseFloat(raing_default)),raing_value);

	if (ismouseover) {
		a_this.unbind("hover");
		a_this.parent().parent().parent().parent().parent().hover(function(){
			$(this).find(".fenxiang").show();
		},function(){
			$(this).find(".fenxiang").hide();
		});
		a_arr.unbind('click'); 
		//单击星星时发生
		a_arr.live("click",function(event){
			event.stopPropagation();
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
		//增加过渡窗口
		var alertModalDialog=$(".alertModal-dialog");
		var alertModalTitle=$(".alertModal-header .alertModal-title");
		var alertModalBody=$(".alertModal-dialog .alertModal-body");
		var alertModalBody_ratinglist=alertModalBody.find(".rating-list");
		var commentContent=$("#commentContent",alertModalBody);
		commentContent.val("");
		//alert(alertModalBody_ratinglist.find("a").length);
		var selected_a=$("a:lt("+i+")",alertModalBody_ratinglist);
		selected_a.removeClass();
		selected_a.addClass("rating-icon rating-off");
		alertModalBody.find(".rating-value").text(i);
		alertModalBody.find(".value-desc").text($(this).attr('data-title'));
		var no_selected_a=$("a:gt("+(i-1)+")",alertModalBody_ratinglist);
		no_selected_a.removeClass();
		no_selected_a.addClass("rating-icon star-on");

		alertModalTitle.text(a_this.attr('data-name'));
		//alertModalBody.text('您将对'+a_this.attr('data-name')+'打'+raing_value.text()+'分('+$(this).attr('data-title')+')');
		alertModalDialog.show();
		$(".alertModal-footer #alertModalClose").click(function(){
			alertModalDialog.hide();
			rating_cancel.removeClass('rating-pending');
			a_this.attr("data-clicknum","0");
			raing_value.text(raing_default);
			//console.log(rating_cancel_result+"abc");
			ratingInit(a_this,"rating-icon rating-init",Math.round(parseFloat(raing_default)),raing_value);
		});

		$(".alertModal-header .close").click(function(){
			alertModalDialog.hide();
			rating_cancel.removeClass('rating-pending');
			a_this.attr("data-clicknum","0");
			raing_value.text(raing_default);
			ratingInit(a_this,"rating-icon rating-init",Math.round(parseFloat(raing_default)),raing_value);
			$(".alertModal-footer #alertModalSubmit").removeAttr('disabled');
			$(".alertModal-footer #alertModalSubmit").html('提交');
		});
		$(".alertModal-footer #alertModalSubmit").unbind('click');
		$(".alertModal-footer #alertModalSubmit").bind("click",function(event){
			event.stopPropagation();
			var btnsubmit_this=$(this);
			btnsubmit_this.attr("disabled","disabled");//增加按钮状态锁定
			btnsubmit_this.html('<span class="btn-loading"><i class="fa fa-spinner fa-spin fa-2" id="icon-load"></i> 正在提交中...</span>');
			//提交评分的开始
			$.post(voteCreateUrl,{Vote:{user_id:a_this.attr("data-user"),restaurant_id:a_this.attr("data-id"),
				rating:raing_value.text()},Comment:commentContent.val()},function(resultdata){

				if (resultdata.code==0) {//0：成功，-2：频率过快,点评失败：-3
					a_this.attr('voteid',resultdata.voteid);//将voteid邦定到dom对象上
					rating_cancel.removeClass('rating-pending').addClass("rating-icon rating-your");
					var tooltip=$(".tang-tooltip"); 
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
						$.post(voteDeleteUrl,{Vote:{id:a_this.attr("voteid")}},function(rating_cancel_result){								
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
					//还原默认值
					btnsubmit_this.removeAttr('disabled');
					btnsubmit_this.html('提交');
					alertModalDialog.hide();

				}else if(resultdata.code==-2){
					var i=resultdata.delay;
					var votetime=setInterval(function(){
						i--;
						btnsubmit_this.html('<span class="btn-loading">'+resultdata.msg+'('+i+')秒</span>');
						if (i<=0) {
							clearInterval(votetime);
							btnsubmit_this.removeAttr('disabled');
							btnsubmit_this.html('提交');
						};
					},1000);
				}else{
				//服务器出错
				}
			},"json");
			//提交评分的结束
			a_this.attr("isclick","true");
		});
		event.stopPropagation();
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
		}else if(clicknum=="0" && parseInt(raing_default)>0){
			ratingInit(a_this,"rating-icon rating-init",Math.round(raing_default),raing_value);
			raing_value.text(raing_default);
		}
		else{
			ratingInit(a_this,"rating-icon rating-off",parseInt(clicknum),raing_value);
			raing_value.text(clicknum);
		}
	});
		}
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