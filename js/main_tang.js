

$(function(){
	var footerHeight = 0,
	footerTop = 0,
	$footer = $("#footer");
	positionFooter();
	//定义positionFooter function
	function positionFooter() {
		//取到div#footer高度
		footerHeight = $footer.height();
		//div#footer离屏幕顶部的距离
		footerTop = ($(window).scrollTop()+$(window).height()-footerHeight)+"px";								
		//如果页面内容高度小于屏幕高度，div#footer将绝对定位到屏幕底部，否则div#footer保留它的正常静态定位
		if ( ($(document.body).height()+footerHeight) < $(window).height()) {
			$footer.css({
			position: "absolute"
			,top: footerTop
			});
		} else {
			$footer.css({
			position: "static"
			});
		}
	}
	$(window).scroll(positionFooter).resize(positionFooter);

	//菜单选中效果
	var hostUrl=window.location.href.split(window.location.hostname);
	var county="",area="",type="";
	if (hostUrl[1]=='/') {
		hostUrl[1]="<?php echo $this->createUrl('restaurant/index'); ?>";
	}
	county=hostUrl[1];
	var params=hostUrl[1];
	if (params.indexOf('area')>-1 && params.indexOf('type')==-1) {
		county=params.split('/area')[0]=="/county/0"?"<?php echo $this->createUrl('restaurant/index'); ?>":params.split('/area')[0];
		area=params;
	}

	if (params.indexOf('type')>-1) {
		county=params.split('/area')[0]=="/county/0"?"<?php echo $this->createUrl('restaurant/index'); ?>":params.split('/area')[0];
		area=params.split('/type')[0].indexOf("area/-1")>-1?county:params.split('/type')[0];
		type=params;
	}

	$("#mainmenu .mainmenu-content>ul>li>a").each(function(){
		if ($(this).attr('href')==county) {
			$(this).parent().attr('class','active');
		}
	});

	if (area=="") {
		$("#county-menu>ul>li>a").eq(0).parent().attr('class','active');
	}else{
		$("#county-menu>ul>li>a").each(function(){
			if ($(this).attr('href')==area) {
				$(this).parent().attr('class','active');
			}
		});
	}

	if (type=="") {
		$("#area-menu>ul>li>a").eq(0).parent().attr('class','active');
	}else{
		$("#area-menu>ul>li>a").each(function(){
			if ($(this).attr('href')==type) {
				$(this).parent().attr('class','active');
			}
		});
	}

	//点击登陆弹出模态窗口
	$(".login").click(function(){
		loginModal();
	});

	$(".modal-backdrop1").click(function(){
		$("#myModal").hide();
		$(this).hide();
	});
	
	$(document.body).keyup(function(event){
	//if(event.ctrlKey && event.which == 13)       //13等于回车键(Enter)键值,ctrlKey 等于 Ctrl
	//alert("按了ctrl+回车键!")
	if(event.keyCode==27)
		$("#myModal").hide();
	$(".modal-backdrop1").hide();
	});

	//县菜单鼠标放上去显示下级菜单
	$(".areamenu").hover(function(){
		$(this).find('ul').show(100);
	},function(){
		$(this).find('ul').hide(100);
	});

	//回到顶部ＪＳ
	$(window).scroll(function(){

		if($(window).scrollTop()>($(document.body).height()/4))
		{
			$("#right_float_panel").show();
		}else
		{
			$("#right_float_panel").hide();
		}
	});

	//回到顶部功能
	$(".top_up").click(function(){
		$('html,body').animate({scrollTop:'0px'},500);
	});
	//顶部搜索功能的表单事件
	$("#formSearch").click(function(){
		$(".column-main-form").submit();
	});
	//顶部搜索框按驾车时触发表单的提交
	$(".column-main-form>input[type=text]").keyup(function(event){
		if(event.keyCode==13)
			$(".column-main-form").submit();
	});
});

//弹出登陆窗口
function loginModal(){
	$(".modal-backdrop1").show();
	$("#myModal").slideDown(200);
	$(".close").one("click",function(){
		$(".modal-backdrop1").hide();
		$("#myModal").slideUp(100);
	});
}

// 检查搜索条件。
function checkSearchForm(){
	// TODO: 会弹框两次，搜索功能调试通过后解决。
	if(document.getElementById('key').value){
		return true;
	}
	else{
		alert("请输入搜索关键词！");
		return false;
	}
	return true;
}
