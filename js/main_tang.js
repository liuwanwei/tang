
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
	return true;

	// TODO: 会弹框两次，搜索功能调试通过后解决。
	if(document.getElementById('key').value){
		return true;
	}
	else{
		alert("请输入搜索关键词！");
		return false;
	}
}
